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

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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

        $result = json_decode($response->getContent(), true);
        $returnArray = [];

        foreach ($result['entries'] as $player) {

            //$player = $result['entries'][4];

            $summonerId = $player['summonerId'];
            $player = $this->getSummoner($summonerId);
            $puuid = $player['puuid'];

            $matchs = $this->getMatchs($puuid);
            $matchResult = [];

            foreach ($matchs as $matchId) {
                $match = $this->getMatchInfo($matchId);

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
            }
            $returnArray[] = $matchResult;
        }
        //return $matchResult;
        return $returnArray;
    }

    /**
     * @param string $puuidId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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

    /**
     * @param string $matchId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getMatchInfo(string $matchId): array {
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

    /**
     * @param string $summonerId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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

    public function createComposition($arrayMatchs) {
        foreach ($arrayMatchs as $match) {
            //$bestMatch = new BestMatch();
            //$date = new \DateTime();
            //$date->setTimestamp($match['matchDate']);
            //$bestMatch->setDateMatch($date);

            foreach ($match['compositions'] as $composition) {
                // CREATE CHAMPIONS IF DOESN'T EXIST
                $this->createChampions($composition['champions']);

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

                /*if($composition['win']) {
                    $compositionEntityWins = $compositionEntity->getWins();
                    $compositionEntity->setWins($compositionEntityWins);
                }
                else {
                    $compositionEntityLosses = $compositionEntity->getLosses();
                    $compositionEntity->setLosses($compositionEntityLosses);
                }*/

                $this->entityManager->persist($compositionEntity);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param array $champions
     * @return array
     */
    public function createChampions(array $champions): array {
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
}