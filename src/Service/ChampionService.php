<?php

namespace App\Service;

use App\Entity\Champion;
use App\Entity\Lane;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChampionService
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
        ParameterBagInterface $parameterBag
    )
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param array $champions
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function storeChampions(array $champions): array
    {
        $championsId = [];
        foreach ($champions as $champion) {
            $existingChampion = $this->entityManager->getRepository(Champion::class)->findOneByRiotId($champion['id']);
            if (null !== $existingChampion) {
                continue;
            }
            $riotStatic = $this->parameterBag->get('static_base_url');
            $lolVersion = $this->parameterBag->get('lol_version');
            $imageUrl = sprintf('%s/%s/img/champion/%s.png', $riotStatic, $lolVersion, $champion['name']);
            $imageLoadingUrl = sprintf('%s/img/champion/loading/%s_0.jpg', $riotStatic, $champion['name']);
            $imageSplashUrl = sprintf('%s/img/champion/splash/%s_0.jpg', $riotStatic, $champion['name']);
            $championEntity = new Champion();
            $championEntity
                ->setRiotId($champion['id'])
                ->setName($champion['name'])
                ->setImage($imageUrl)
                ->setImageLoading($imageLoadingUrl)
                ->setImageSplash($imageSplashUrl);
            $championsInfos = $this->getChampionInfos($champion['name']);
            $typesArray = $this->storeType($championsInfos['types']);
            $lanesArray = $this->storeLane($championsInfos['lanes']);

            foreach ($typesArray as $type)
            {
                $championEntity->addType($type);
            }

            foreach ($lanesArray as $lane) {
                $championEntity->addLane($lane);
            }

            $this->entityManager->persist($championEntity);

            $this->entityManager->flush();
            $championsId[] = $champion['id'];
        }

        return $championsId;
    }

    /**
     * @param string $name
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getChampionInfos(string $name): array {
        $championBase = $this->parameterBag->get('champion_base_url');

        $riotChampionUrl = sprintf('%s/all_champion.json', $championBase);
        $response = $this->httpClient->request('GET', $riotChampionUrl);
        $championsInfos = json_decode($response->getContent(), true);
        return $championsInfos[$name];
    }

    /**
     * @param array $types
     * @return array
     */
    public function storeType(array $types): array {
        $storeType = [];
        foreach ($types as $type) {
            $existingType =  $this->entityManager->getRepository(Type::class)->findOneByName($type);

            if(null !== $existingType) {
                $storeType[] = $existingType;
                continue;
            }

            $typeEntity = new Type();
            $typeEntity
                ->setName($type);

            $this->entityManager->persist($typeEntity);
            $storeType[] = $typeEntity;
        }

        $this->entityManager->flush();
        return $storeType;
    }

    /**
     * @param array $lanes
     * @return array
     */
    public function storeLane(array $lanes): array {
        $storeLane = [];
        foreach ($lanes as $lane)
        {
            $existingLane =  $this->entityManager->getRepository(Lane::class)->findOneByName($lane);

            if(null !== $existingLane) {
                $storeLane[] = $existingLane;
                continue;
            }

            $laneEntity = new Lane();
            $laneEntity
                ->setName($lane);

            $this->entityManager->persist($laneEntity);
            $storeLane[] = $laneEntity;
        }


        $this->entityManager->flush();
        return $storeLane;
    }

    public function setWinRate(array $champions, bool $win) {
        foreach ($champions as $champion) {
            $existingChampion = $this->entityManager->getRepository(Champion::class)->findOneByRiotId($champion['id']);
            if (null === $existingChampion) {
                continue;
            }
            $championEntityWins = $existingChampion->getWins();
            $championEntityLosses = $existingChampion->getLosses();
            if($win) {

                $championEntityWins++;
                $existingChampion->setWins($championEntityWins);
            }
            else {

                $championEntityLosses++;
                $existingChampion->setLosses($championEntityLosses);
            }
            $championEntityWins = $existingChampion->getWins();
            $championEntityLosses = $existingChampion->getLosses();

            if($championEntityWins !== null || $championEntityLosses !== null) {
                $winRate = $championEntityWins / ($championEntityLosses + $championEntityWins) * 100;
            }
            else {
                $winRate = null;
            }

            $existingChampion->setWinRate($winRate);
            $this->entityManager->persist($existingChampion);
        }

        $this->entityManager->flush();
    }
}