<?php

namespace App\Service;

use App\Service\ChampionService;
use App\Entity\BestMatch;
use App\Entity\BestMatchComposition;
use App\Entity\Champion;
use App\Entity\Composition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CompositionService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var ChampionService
     */
    private $championService;

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $httpClient,
        ParameterBagInterface $parameterBag,
        ChampionService $championService
    )
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
        $this->championService = $championService;
    }

    public function getChallengers() :array {
        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_euw');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/league/v4/challengerleagues/by-queue/RANKED_SOLO_5x5', $riotBaseUrlEuw);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function getMatchs( string $puuidId): array {
        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_europe');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/match/v5/matches/by-puuid/%s/ids?count=20', $riotBaseUrlEuw, $puuidId);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function getMatch(string $matchId): array {
        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_europe');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/match/v5/matches/%s', $riotBaseUrlEuw, $matchId);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function getSummoner(string $summonerId): array {
        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_euw');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/summoner/v4/summoners/%s', $riotBaseUrlEuw, $summonerId);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function getMatchsInfo($challengers): array
    {
        $returnArray = [];

        foreach ($challengers['entries'] as $index => $player) {

            //$player = $challengers['entries'][10];

            $summonerId = $player['summonerId'];
            $player = $this->getSummoner($summonerId);
            $puuid = $player['puuid'];

            $matchs = $this->getMatchs($puuid);
            $matchResult = [];

            foreach ($matchs as $matchId) {
                $match = $this->getMatch($matchId);

                $compositionsArrayWin = [];
                $compositionsArrayLosse = [];

                foreach ($match['info']['participants'] as $participant) {
                    if($participant['win']) {
                        $compositionsArrayWin[] = [
                            'id' => $participant['championId'],
                            'name' => $participant['championName'],
                        ];
                    }
                    else {
                        $compositionsArrayLosse[] = [
                            'id' => $participant['championId'],
                            'name' => $participant['championName'],
                        ];
                    }
                }
                $matchResult[] = [
                    'id' => $match['metadata']['matchId'],
                    'matchDate' => $match['info']['gameCreation'],
                    'compositions' => [
                        [
                            'champions' => $compositionsArrayWin,
                            'win' => true,
                        ],
                        [
                            'champions' => $compositionsArrayLosse,
                            'win' => false,
                        ],
                    ]
                ];
            }
            $returnArray[] = $matchResult;
            $this->storeMatch($matchResult);
            echo sprintf("Sleeping for 30s, fetched player %s \n", $index);
            sleep(30);
        }
        //return $matchResult;
        return $returnArray;
    }

    public function storeMatch($arrayMatchs) {
        foreach ($arrayMatchs as $match) {
            $existingMatch  = $this->entityManager->getRepository(BestMatch::class)->findOneByRiotId($match['id']);

            if (null !== $existingMatch) {
                continue;
            }
            $bestMatch = new BestMatch();
            $date = new \DateTime();
            $date->setTimestamp($match['matchDate'] / 1000);
            $bestMatch->setDateMatch($date);
            $bestMatch->setRiotId($match['id']);
            $compositions = $this->storeComposition($match);

            foreach ($compositions as $composition) {
                $bestMatchComposition = new BestMatchComposition();
                $bestMatchComposition
                    ->setBestMatch($bestMatch)
                    ->setComposition($composition['composition'])
                    ->setWin($composition['win']);
                $this->entityManager->persist($bestMatchComposition);
            }
            $this->entityManager->persist($bestMatch);
        }
        $this->entityManager->flush();
    }

    public function storeComposition($match) {
        $compositions = [];
        foreach ($match['compositions'] as $composition) {
            $this->championService->storeChampions($composition['champions']);

            $allChampionsId = array_map(function($champion) {
                return $champion['id'];
            }, $composition['champions']);

            $championEntities = $this->entityManager->getRepository(Champion::class)->getChampionsForIds($allChampionsId);
            if(empty($championEntities) || count($championEntities) < 5) {
                continue;
            }
            $string = sprintf('%s%s%s%s%s', ($championEntities[0])->getRiotId(), ($championEntities[1])->getRiotId(), ($championEntities[2])->getRiotId(), ($championEntities[3])->getRiotId(), ($championEntities[4])->getRiotId());
            $hash = sha1($string);

            $result = $this->entityManager->getRepository(Composition::class)->findByHash($hash);

            if(empty($result)) {
                $compositionEntity = new Composition();
                $compositionEntity->setHash($hash);

                foreach ($composition['champions'] as $champion) {
                    $championEntity = $this->entityManager->getRepository(Champion::class)->findOneByRiotId($champion['id']);
                    $compositionEntity
                        ->addChampion($championEntity);
                }
            }
            else {
                $compositionEntity = $this->entityManager->getRepository(Composition::class)->findOneById($result);
            }

            if($composition['win']) {
                $compositionEntityWins = $compositionEntity->getWins();
                $compositionEntityWins++;
                $compositionEntity->setWins($compositionEntityWins);
            }
            else {
                $compositionEntityLosses = $compositionEntity->getLosses();
                $compositionEntityLosses++;
                $compositionEntity->setLosses($compositionEntityLosses);
            }

            $this->entityManager->persist($compositionEntity);
            $compositions[] = [
                'composition' => $compositionEntity,
                'win' => $composition['win'],
            ];
        }
        $this->entityManager->flush();

        return $compositions;
    }
}