<?php

namespace App\Service;

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

    public function __construct(
        EntityManagerInterface $entityManager,
        HttpClientInterface $httpClient,
        ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
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

        //foreach ($challengers['entries'] as $index => $player) {

            $player = $challengers['entries'][3];

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
            //}
            $returnArray[] = $matchResult;
            $this->storeMatch($matchResult);
            //echo sprintf('Sleeping for 30s, fetched player %s', $index);
            //sleep(30);
        }
        return $matchResult;
        //return $returnArray;
    }

    public function storeMatch($arrayMatchs) {
        foreach ($arrayMatchs as $match) {
            //$bestMatch = new BestMatch();
            //$date = new \DateTime();
            //$date->setTimestamp($match['matchDate']);
            //$bestMatch->setDateMatch($date);
            $this->storeComposition($match);
        }
    }

    public function storeChampions(array $champions): array {
        $championsId = [];
        foreach ($champions as $champion) {
            $existingChampion = $this->entityManager->getRepository(Champion::class)->findOneByRiotId($champion['id']);
            if (null !== $existingChampion) {
                continue;
            }

            $championEntity = new Champion();
            $championEntity
                ->setRiotId($champion['id'])
                ->setName($champion['name']);
            $this->entityManager->persist($championEntity);
            $championsId[] = $champion['id'];
        }
        $this->entityManager->flush();
        return $championsId;
    }

    public function storeComposition($match) {
        foreach ($match['compositions'] as $composition) {
            // CREATE CHAMPIONS IF DOESN'T EXIST
            $this->storeChampions($composition['champions']);

            $allChampions = array_map(function($champion) {
                $championEntity = $this->entityManager->getRepository(Champion::class)->findOneByRiotId($champion['id']);
                return $championEntity;
            }, $composition['champions']);

            $string = sprintf('%s%s%s%s%s', $allChampions[0]->getId(), $allChampions[1]->getId(), $allChampions[2]->getId(), $allChampions[3]->getId(), $allChampions[4]->getId());
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

            $this->entityManager->persist($compositionEntity);
        }
        $this->entityManager->flush();
    }

    public function storePoint() {

        /*if($composition['win']) {
            $compositionEntityWins = $compositionEntity->getWins();
            $compositionEntity->setWins($compositionEntityWins);
        }
        else {
            $compositionEntityLosses = $compositionEntity->getLosses();
            $compositionEntity->setLosses($compositionEntityLosses);
        }*/
    }
}