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
        $favoriteIds = [];
        if($playerEntity) {

            foreach($playerEntity->getFavorite() as $favorite) {

                $favorite = $favorite->getRiotId();
                $favoriteIds[] = $favorite;

            }
            //$lane = $this->entityManager->getRepository(Lane::class)->findOneByName('TOP');
            $champions = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $favoriteIds]);
            // ORDER BY winrate ASC
            //$selectedChampions = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $favoriteIds, 'lane' => $lane]);
            $championsByLane = [];

            // POSSIBLE DE FAIRE UNE REQUETE SQL ?
            foreach ($champions as $championLane) {
                foreach ($championLane->getLane() as $lane) {
                    if($lane->getName() !== $laneParams) {
                        continue;
                    }
                    $championsByLane[] = $championLane;
                }
            }

            $selectedChampions = $championsByLane;
        }
        else {
            // IF NO FAVORITES avoir les meilleurs champions pour la lane
            //ORDER BY winrate ASC
            //$selectedChampions = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $favoriteIds, 'lane' => $lane]);

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