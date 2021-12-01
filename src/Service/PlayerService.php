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
        
        foreach ($champions as $champion) {
            $existingChampion = $this->entityManager->getRepository(Player::class)->getFavorite($champion);
            dd($existingChampion);
            if(!empty($existingChampion)) {
                continue;
            }

            $player->addFavorite($champion);
            $this->entityManager->persist($player);
        }

        
        $this->entityManager->flush();

        return $player;

        // Jdois déjà avoir un champion dans la BDD
        // On ajoute un champion dans un slot
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
