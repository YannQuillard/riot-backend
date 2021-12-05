<?php

namespace App\Service;

use App\Entity\BestMatchComposition;
use App\Entity\Champion;
use App\Entity\Composition;
use App\Entity\Lane;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $params
     * @return array
     */
    public function newMatch(array $params): array
    {
        $championsNumber = $params['maxNumber'];
        $playerParams = $params['username'];
        $laneParams = $params['lane'];
        $pickParams = $params['pick'];
        $pickFromTeamRiotId = $pickParams['team'];
        $pickFromEnemyRiotId = $pickParams['enemy'];
        $banParams = $params['ban'];
        $player =  $this->entityManager->getRepository(Player::class)->findOneByName($playerParams);
        $lane = $this->entityManager->getRepository(Lane::class)->findOneByName($laneParams);
        $favoriteRiotId = $this->getFavoriteRiotId($player);

        $selectedChampions = $this->entityManager->getRepository(Champion::class)->getChampionsForIdsByLane($favoriteRiotId, $lane->getId());
        $bestChampions = $this->entityManager->getRepository(Champion::class)->getChampionsByLane($lane->getId());
        $banChampions = $this->entityManager->getRepository(Champion::class)->getChampionsForids($banParams);
        $pickTeam = $this->entityManager->getRepository(Champion::class)->getChampionsForIds($pickFromTeamRiotId);
        $pickEnemy= $this->entityManager->getRepository(Champion::class)->getChampionsForIds($pickFromEnemyRiotId);

        $selectedChampions = $this->removeBanAndPick($selectedChampions, $pickEnemy, $pickTeam, $banChampions);
        $bestChampions = $this->removeBanAndPick($bestChampions, $pickEnemy, $pickTeam, $banChampions);

        if(empty($pickFromEnemyRiotId) || count($pickFromEnemyRiotId) === 0) {
            $championsIdsCompositionWinRate = $this->getCompositionWinRateByChampions($bestChampions, $pickTeam, $banChampions);
            $championsCompositions = $this->removeEmptyAndBan($championsIdsCompositionWinRate, $banParams);
            return $this->mergeArrayWithWinRate($selectedChampions, $bestChampions, $championsCompositions, $championsNumber);
        }
        else {
            $enemyPickBestComposition = $this->getCompositionEnemy($pickEnemy, $pickTeam, $banChampions);
            $getTeamWinComposition = $this->getBestMatchLossesComposition($enemyPickBestComposition, $pickTeam);
            $compareHashTeam = $this->compareHash($getTeamWinComposition, $pickTeam, $pickEnemy, $banChampions);
            return $this->mergeArrayWithWinRate($selectedChampions, $bestChampions, $compareHashTeam, $championsNumber);
        }
    }

    /**
     * @param array $champions
     * @param array $pick
     * @param array $ban
     * @return array
     */
    public function getCompositionWinRateByChampions(array $champions, array $pick, array $ban): array
    {
        $pickHash = $this->createHash($pick);
        $banHash = $this->createHash($ban);
        $compositionByChampion = [];
        foreach ($champions as $champion) {

            $hash = sprintf("%s#%s#%s",'%', $champion->getRiotId(), '%');
            $selectedComposition = $this->entityManager->getRepository(Composition::class)->getBestCompositionChampions($hash, $pickHash, $banHash, 1);
            $compositionByChampion[] = $selectedComposition;
        }

        return $compositionByChampion;
    }

    /**
     * @param array $enemyPick
     * @param array $teamPick
     * @param array $ban
     * @return array
     */
    public function getCompositionEnemy(array $enemyPick, array $teamPick, array $ban): array
    {
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

    /**
     * @param array $enemyCompositions
     * @param array $teamPick
     * @return array
     */
    public function getBestMatchLossesComposition(array $enemyCompositions, array $teamPick): array
    {
        $compositionsWin = [];
        foreach ($enemyCompositions as $enemyComposition) {
            foreach ($enemyComposition['composition'] as $composition) {
                $bestMatch = $this->entityManager->getRepository(BestMatchComposition::class)->findByComposition($composition);
                foreach ($bestMatch as $compositionHasWin) {
                    if($compositionHasWin->getWin()) {
                        continue;
                    }
                    $match = $this->entityManager->getRepository(BestMatchComposition::class)->findByBestMatch($compositionHasWin->getId());
                    foreach ($match as $compositionTeam) {
                        if(!$compositionTeam->getWin()) {
                            continue;
                        }
                        $compositionWin = $this->entityManager->getRepository(Composition::class)->findOneById($compositionTeam->getComposition()->getId());
                        $compositionsWin[] = $compositionWin;
                    }
                }
            }
        }
        return $compositionsWin;
    }

    /**
     * @param array $champions
     * @return array
     */
    public function createHash(array $champions): array
    {
        $hashArray = [];
        foreach ($champions as $champion) {
            $hash = sprintf('%s#%s#%s', '%', $champion->getRiotId(), '%');
            $hashArray[] = $hash;
        }
        return $hashArray;
    }

    /**
     * @param array $champions
     * @return string
     */
    public function createBanRegex(array $champions): string
    {
        $hash = "/^(?!.*(";
        foreach ($champions as $index => $champion) {

            if($index === 0) {
                $hash = sprintf('%s#%s#', $hash, $champion->getRiotId());
            } else {
                $hash = sprintf('%s|#%s#', $hash, $champion->getRiotId());
            }

        }

        return sprintf('%s)).*$/i', $hash);
    }

    /**
     * @param array $champions
     * @return string
     */
    public function createPickRegex(array $champions): string
    {

        $hash = "/";
        foreach ($champions as $index => $champion) {
            $hash = sprintf('%s(?=.*#%s#)', $hash, $champion->getRiotId());
        }

        return sprintf('%s/i', $hash);
    }

    /**
     * @param array $compositions
     * @param array $pick
     * @param array $pickEnemy
     * @param array $ban
     * @return array
     */
    public function compareHash(array $compositions, array $pick, array $pickEnemy, array $ban): array
    {
        $validComposition = [];
        $cantPick = array_merge($ban, $pickEnemy);
        $checkBanHash = $this->createBanRegex($cantPick);
        $checkPickHash = $this->createPickRegex($pick);
        foreach ($compositions as $composition) {

            $hash = $composition->getHash();
            if(!preg_match($checkBanHash, $hash)) {
                continue;
            }

            if(!preg_match($checkPickHash, $hash)) {
                continue;
            }

            $validComposition[] = $composition;
        }
        return $validComposition;
    }

    /**
     * @param Player $player
     * @return array
     */
    public function getFavoriteRiotId(Player $player): array
    {
        $favoriteIds = [];
        foreach($player->getFavorite() as $favorite) {
            $favorite = $favorite->getRiotId();
            $favoriteIds[] = $favorite;
        }

        return $favoriteIds;
    }

    /**
     * @param array $array
     * @param array $ban
     * @return array
     */
    public function removeEmptyAndBan(array $array, array $ban): array
    {
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

    /**
     * @param array $champions
     * @param array $enemies
     * @param array $picks
     * @param array $bans
     * @return array
     */
    public function removeBanAndPick(array $champions, array $enemies, array $picks, array $bans): array
    {
        foreach ($champions as $index => $champion) {
            foreach ($enemies as $enemy) {
                if($enemy->getId() !== $champion->getId()) {
                    continue;
                }
                unset($champions[$index]);
            }

            foreach ($picks as $pick) {
                if($pick->getId() !== $champion->getId()) {
                    continue;
                }
                unset($champions[$index]);
            }

            foreach ($bans as $ban) {
                if($ban->getId() !== $champion->getId()) {
                    continue;
                }
                unset($champions[$index]);
            }
        }

        return $champions;
    }

    /**
     * @param array $favorites
     * @param array $bestChampions
     * @param array $bestCompositions
     * @param int $limit
     * @return array
     */
    public function mergeArrayWithWinRate(array $favorites, array $bestChampions, array $bestCompositions, int $limit): array
    {
        $compositionByChampion = [];
        foreach ($bestCompositions as $composition) {

            foreach ($favorites as $index => $favorite) {
                $hash = $this->createPickRegex([$favorite]);

                if(!preg_match($hash, $composition->getHash())) {
                    continue;
                }

                /*$compositionByChampion[] = [
                    "champions" => $favorite,
                    "composition" => $composition
                ];*/

                $compositionByChampion[] = $favorite;

                unset($favorites[$index]);
            }

            foreach ($bestChampions as $index => $bestChampion) {
                $hash = $this->createPickRegex([$bestChampion]);

                if(!preg_match($hash, $composition->getHash())) {
                    continue;
                }

                /*$compositionByChampion[] = [
                    "champion" => $bestChampion,
                    "composition" => $composition
                ];*/
                $compositionByChampion[] = $bestChampion;

                unset($bestChampions[$index]);
            }
        }

        if(count($compositionByChampion) !== $limit) {
            $diff = $limit - count($compositionByChampion);
            $merged = array_merge($favorites, $bestChampions);
            usort($merged, [$this, 'sortArrayByKey']);
            $defaultChampions = array_slice($merged, 0, $diff);
            foreach ($defaultChampions as $defaultChampion) {
                $compositionByChampion[] = $defaultChampion;
            }
        }

        return $compositionByChampion;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
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