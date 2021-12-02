<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Champion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class PlayerService {

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

    public function getPlayer() :array {

        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_euw');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/summoner/v4/summoners/by-name/Ziarys', $riotBaseUrlEuw);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }

    public function addFavorite(array $params) {

        $player = $this->entityManager->getRepository(Player::class)->findOneByName($params['username']);
        if ($player === null) {
            $player = $this->storePlayer($params['username']);
        }

        

        $champions = $this->entityManager->getRepository(Champion::class)->getChampionsForIds($params['champions']);
        $champions = array_map(function($champion) {
            return $champion->getRiotId();
        }, $champions);
        // récup les favorites appartenant à un joueur
        $favoriteIds = [];
        foreach($player->getFavorite() as $favorite) {
            
            $favorite = $favorite->getRiotId();
            $favoriteIds[] = $favorite;
            
        }
        
        // si le player a déjà des favoris,
        if ($champions != null) {
            //comparer les favoris existants avec les favoris obtenus précedemment
            $addDifference = array_diff($champions, $favoriteIds);
            // une fois que les 2 tableaux sont comparés, les favoris qui ne sont pas présents dans les favoris existants seront ajoutés dans la BDD

            $newFavoriteChampions = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $addDifference]);

            foreach ($newFavoriteChampions as $newFavoriteChampion) {
                $player->addFavorite($newFavoriteChampion);
            }

            // on récupère les id présents sur les mêmes arrays
            $removeDifference = array_diff($favoriteIds, $params['champions']);


            // les favoris présents dans les favoris existants, mais plus dans les favoris envoyés dans les paramètres seront supprimés de la BDD
            $oldFavoriteChampions = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $removeDifference]);
            
            foreach ($oldFavoriteChampions as $oldFavoriteChampion) {
                $player->removeFavorite($oldFavoriteChampion);
            }    
            
        }
        
        else {
            // clear all
            $removeChampion = $this->entityManager->getRepository(Champion::class)->findBy(['riotId' => $favoriteIds]);
            foreach($removeChampion as $champion) {
                $player->removeFavorite($champion);
            }
        }

        
        $this->entityManager->persist($player);
        
        $this->entityManager->flush();

        return $player;

    }

    public function storePlayer(string $username) {
        $summoner = $this->getSummoner($username);
        $player = new Player();
        $player
            ->setName($username)
            ->setPuuid($summoner['puuid'])
            ->setSummonerId($summoner['id']);
            $this->entityManager->persist($player);
            $this->entityManager->flush();
        return $player;
    }

    public function getSummoner(string $username): array {
        $riotBaseUrlEuw = $this->parameterBag->get('riot_base_url_euw');
        $riotToken = $this->parameterBag->get('riot_token');

        $riotChallengerUrl = sprintf('%s/summoner/v4/summoners/by-name/%s', $riotBaseUrlEuw, $username);
        $response = $this->httpClient->request('GET', $riotChallengerUrl, [
            'body' => '',
            'headers' => [
                'X-Riot-Token' => $riotToken,
            ]
        ]);

        return json_decode($response->getContent(), true);
    }
}
