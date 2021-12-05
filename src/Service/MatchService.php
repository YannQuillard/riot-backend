<?php

namespace App\Service;

use App\Entity\Champion;
use App\Entity\Composition;
use App\Entity\Lane;
use App\Entity\Match;
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
        $championsNumber = $params['number'];
        $playerParams = $params['username'];
        $laneParams = $params['lane'];
        $pickParams = $params['pick'];
        $pickFromTeamRiotId = $pickParams['team'];
        $pickFromEnemyRiotId = $pickParams['enemy'];
        $banParams = $params['ban'];
        $player =  $this->entityManager->getRepository(Player::class)->findOneByName($playerParams);
        $lane = $this->entityManager->getRepository(Lane::class)->findOneByName($laneParams);
        $favoriteRiotId = $this->getFavoriteRiotId($player);

        $banChampions = $this->entityManager->getRepository(Champion::class)->getChampionsForids($banParams);
        $pickTeam = $this->entityManager->getRepository(Champion::class)->getChampionsForIds($pickFromTeamRiotId);
        $pickEnemy= $this->entityManager->getRepository(Champion::class)->getChampionsForIds($pickFromEnemyRiotId);

        if(empty($pickFromEnemyRiotId)) {
            $selectedChampions = $this->entityManager->getRepository(Champion::class)->getChampionsForIdsByLane($favoriteRiotId, $lane->getId());
            $bestChampions = $this->entityManager->getRepository(Champion::class)->getChampionsByLane($lane->getId());
            $championsIdsCompositionWinRate = $this->getCompositionWinRateByChampions($bestChampions, $pickTeam, $banChampions);
            $championsCompositions = $this->removeEmptyAndBan($championsIdsCompositionWinRate, $banParams);
            return $this->mergeArrayWithWinRate($selectedChampions, $bestChampions, $championsCompositions, $championsNumber);
        }
        else {
            $enemyPickBestCompo = $this->getCompositionEnemy($pickEnemy, $pickTeam, $banChampions);
            $getBestMatchCompo = $this->getBestMatchLossesComposition($enemyPickBestCompo, $pickTeam);
        }
    }

    public function getCompositionWinRateByChampions(array $champions, array $pick, array $ban): array
    {
        $pickHash = $this->createHash($pick);
        $banHash = $this->createHash($ban);
        $compositionByChampion = [];
        foreach ($champions as $champion) {

            $hash = sprintf("%s#%s#%s",'%', $champion->getRiotId(), '%');
            $selectedComposition = $this->entityManager->getRepository(Composition::class)->getBestCompositionChampions($hash, $pickHash, $banHash, 1);
            $compositionByChampion[] = [
                "champion" => $champion,
                "composition" => $selectedComposition
            ];
        }

        return $compositionByChampion;
    }

    public function getCompositionEnemy(array $enemyPick, array $teamPick, array $ban) {
        $teamHash = $this->createHash($teamPick);
        $banHash = $this->createHash($ban);
        $cantPickHash = array_merge($teamHash, $banHash);
        $compositionByChampion = [];
        foreach ($enemyPick as $champion) {
            $hash = sprintf("%s#%s#%s",'%', $champion->getRiotId(), '%');
            $selectedComposition = $this->entityManager->getRepository(Composition::class)->getBestCompositionChampions($hash, [], $cantPickHash, 0);
            $compositionByChampion[] = [
                "champion" => $champion,
                "composition" => $selectedComposition
            ];
        }
        return $compositionByChampion;
    }

    public function getBestMatchLossesComposition(array $enemyCompositions, array $teamPick) {
        foreach ($enemyCompositions as $enemyComposition) {
            $enemyCompositionsLosses = [];
            foreach ($enemyComposition['composition'] as $composition) {
                dd($composition);
            }
        }
    }

    public function createHash($champions) {
        $hashArray = [];
        foreach ($champions as $champion) {
            $hash = sprintf('%s#%s#%s', '%', $champion->getRiotId(), '%');
            $hashArray[] = $hash;
        }
        return $hashArray;
    }

    public function getFavoriteRiotId(Player $player) {
        $favoriteIds = [];
        foreach($player->getFavorite() as $favorite) {
            $favorite = $favorite->getRiotId();
            $favoriteIds[] = $favorite;
        }

        return $favoriteIds;
    }

    public function removeEmptyAndBan(array $array, array $ban) {
        $returnArray = [];
        foreach ($array as $key)
        {
            if(empty($key['composition']) || count($key['composition']) === 0){
                continue;
            }

            if(in_array($key['champion']->getRiotId(), $ban)) {
                continue;
            }

            $returnArray[] = $key;
        }

        return $returnArray;
    }

    public function mergeArrayWithWinRate(array $favorites, array $bestChampions, array $bestCompositions, int $limit) {
        dd($bestCompositions);
        foreach ($bestCompositions as $champion) {

        }
        //$merged = array_merge($array1, $array2);

        //usort($merged, [$this, 'sortArrayByKey']);
        //return array_slice($merged, 0, 5);
    }

    public function sortArrayByKey($a, $b): int
    {
        $al = $a->getWinRate();
        $bl = $b->getWinRate();
        if ($al == $bl) {
            return 0;
        }
        return ($al < $bl) ? +1 : -1;
    }
}