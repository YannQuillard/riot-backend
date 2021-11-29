<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $puuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $summonerId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=PlayerChampion::class, mappedBy="playerId", orphanRemoval=true)
     */
    private $playerChampions;

    /**
     * @ORM\OneToMany(targetEntity=MatchPlayerChampion::class, mappedBy="playerId", orphanRemoval=true)
     */
    private $matchPlayerChampions;

    public function __construct()
    {
        $this->playerChampions = new ArrayCollection();
        $this->matchPlayerChampions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuuid(): ?string
    {
        return $this->puuid;
    }

    public function setPuuid(string $puuid): self
    {
        $this->puuid = $puuid;

        return $this;
    }

    public function getSummonerId(): ?string
    {
        return $this->summonerId;
    }

    public function setSummonerId(string $summonerId): self
    {
        $this->summonerId = $summonerId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|PlayerChampion[]
     */
    public function getPlayerChampions(): Collection
    {
        return $this->playerChampions;
    }

    public function addPlayerChampion(PlayerChampion $playerChampion): self
    {
        if (!$this->playerChampions->contains($playerChampion)) {
            $this->playerChampions[] = $playerChampion;
            $playerChampion->setPlayerId($this);
        }

        return $this;
    }

    public function removePlayerChampion(PlayerChampion $playerChampion): self
    {
        if ($this->playerChampions->removeElement($playerChampion)) {
            // set the owning side to null (unless already changed)
            if ($playerChampion->getPlayerId() === $this) {
                $playerChampion->setPlayerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MatchPlayerChampion[]
     */
    public function getMatchPlayerChampions(): Collection
    {
        return $this->matchPlayerChampions;
    }

    public function addMatchPlayerChampion(MatchPlayerChampion $matchPlayerChampion): self
    {
        if (!$this->matchPlayerChampions->contains($matchPlayerChampion)) {
            $this->matchPlayerChampions[] = $matchPlayerChampion;
            $matchPlayerChampion->setPlayerId($this);
        }

        return $this;
    }

    public function removeMatchPlayerChampion(MatchPlayerChampion $matchPlayerChampion): self
    {
        if ($this->matchPlayerChampions->removeElement($matchPlayerChampion)) {
            // set the owning side to null (unless already changed)
            if ($matchPlayerChampion->getPlayerId() === $this) {
                $matchPlayerChampion->setPlayerId(null);
            }
        }

        return $this;
    }
}
