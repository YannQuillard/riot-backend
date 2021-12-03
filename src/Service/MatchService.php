<?php

namespace App\Service;

use App\Entity\Champion;
use App\Entity\Lane;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MatchService
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

    public function newMatch(array $params) {
        $playerParams = $params['username'];
        $laneParams = $params['lane'];
        //Enregistrer le match dans la bd


        //Récupérer les favoris des players en fonction de la lane choisit
        $playerEntity = $this->entityManager->getRepository(Player::class)->findOneByName($playerParams);
        $lane = $this->entityManager->getRepository(Lane::class)->findOneByName($laneParams);
        if($playerEntity) {

            foreach($playerEntity->getFavorite() as $favorite) {

                $favorite = $favorite->getRiotId();
                $favoriteIds[] = $favorite;

            }

            $selectedChampions = $this->entityManager->getRepository(Champion::class)->getChampionsForIdsByLane($favoriteIds, $lane->getId());
        }
        else {
            $selectedChampions = $this->entityManager->getRepository(Champion::class)->getChampionsByLane($lane->getId());
        }

        //$championsIdsCompositionWinRate = $this->getCompositionChampionWinRateByChampions($selectedChampions);
        //
    }

    public function storeMatch() {

    }

    public function storeBan() {

    }

    public function storePick() {

    }

    public function getCompositionWinRateByChampions(array $champions) {

    }

    public function getCompositionChampionWinRateByChampions(array $champions) {

    }

    public function setWeightChampion(array $array) {

    }

    public function getCompositionByOpponentComposition(array $champions) {

    }

    public function getCompositionByYourComposition(array $champions) {

    }
}