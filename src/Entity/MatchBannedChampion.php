<?php

namespace App\Entity;

use App\Repository\MatchBannedChampionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchBannedChampionRepository::class)
 */
class MatchBannedChampion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PlayerMatch::class, inversedBy="matchBannedChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchId;

    /**
     * @ORM\ManyToOne(targetEntity=Champions::class, inversedBy="matchBannedChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $championId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchId(): ?PlayerMatch
    {
        return $this->matchId;
    }

    public function setMatchId(?PlayerMatch $matchId): self
    {
        $this->matchId = $matchId;

        return $this;
    }

    public function getChampionId(): ?Champions
    {
        return $this->championId;
    }

    public function setChampionId(?Champions $championId): self
    {
        $this->championId = $championId;

        return $this;
    }
}
