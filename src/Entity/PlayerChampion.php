<?php

namespace App\Entity;

use App\Repository\PlayerChampionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerChampionRepository::class)
 */
class PlayerChampion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Champions::class, inversedBy="playerChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $championId;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="playerChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlayerId(): ?Player
    {
        return $this->playerId;
    }

    public function setPlayerId(?Player $playerId): self
    {
        $this->playerId = $playerId;

        return $this;
    }
}
