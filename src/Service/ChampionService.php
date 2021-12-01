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
        ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param array $champions
     * @return array
     */
    public function storeChampions(array $champions) {
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
            $championsInfos = $this->getChampionInfos($champion['name']);
            $typesArray = $this->storeType($championsInfos['types']);
            $lanesArray = $this->storeLane($championsInfos['lanes']);

            foreach ($typesArray as $type)
            {

                /*$existingType = $this->entityManager->getRepository(Champion::class)->findByType($type);
                if(null !== $existingType) {
                    continue;
                }*/

                $championEntity->addType($type);
            }

            foreach ($lanesArray as $lane) {

                /*$existingType = $this->entityManager->getRepository(Champion::class)->findByLane($lane);

                if(null !== $existingType) {
                    continue;
                }*/

                $championEntity->addLane($lane);
            }



            $this->entityManager->persist($championEntity);

            $this->entityManager->flush();
            $championsId[] = $champion['id'];
        }

        return $championsId;
    }

    public function getChampionInfos(string $name) {
        $championBase = $this->parameterBag->get('champion_base_url');

        $riotChallengerUrl = sprintf('%s/all_champion.json', $championBase);
        $response = $this->httpClient->request('GET', $riotChallengerUrl);
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
}