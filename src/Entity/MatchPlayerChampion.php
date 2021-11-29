<?php

namespace App\Entity;

use App\Repository\MatchPlayerChampionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchPlayerChampionRepository::class)
 */
class MatchPlayerChampion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PlayerMatch::class, inversedBy="matchPlayerChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchId;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="matchPlayerChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerId;

    /**
     * @ORM\ManyToOne(targetEntity=Champions::class, inversedBy="matchPlayerChampions")
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

    public function getPlayerId(): ?Player
    {
        return $this->playerId;
    }

    public function setPlayerId(?Player $playerId): self
    {
        $this->playerId = $playerId;

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
