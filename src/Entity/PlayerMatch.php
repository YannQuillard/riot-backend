<?php

namespace App\Entity;

use App\Repository\PlayerMatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerMatchRepository::class)
 */
class PlayerMatch
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
    private $matchId;

    /**
     * @ORM\OneToMany(targetEntity=MatchPlayerChampion::class, mappedBy="matchId", orphanRemoval=true)
     */
    private $matchPlayerChampions;

    /**
     * @ORM\OneToMany(targetEntity=MatchBannedChampion::class, mappedBy="matchId", orphanRemoval=true)
     */
    private $matchBannedChampions;

    public function __construct()
    {
        $this->matchPlayerChampions = new ArrayCollection();
        $this->matchBannedChampions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchId(): ?string
    {
        return $this->matchId;
    }

    public function setMatchId(string $matchId): self
    {
        $this->matchId = $matchId;

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
            $matchPlayerChampion->setMatchId($this);
        }

        return $this;
    }

    public function removeMatchPlayerChampion(MatchPlayerChampion $matchPlayerChampion): self
    {
        if ($this->matchPlayerChampions->removeElement($matchPlayerChampion)) {
            // set the owning side to null (unless already changed)
            if ($matchPlayerChampion->getMatchId() === $this) {
                $matchPlayerChampion->setMatchId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MatchBannedChampion[]
     */
    public function getMatchBannedChampions(): Collection
    {
        return $this->matchBannedChampions;
    }

    public function addMatchBannedChampion(MatchBannedChampion $matchBannedChampion): self
    {
        if (!$this->matchBannedChampions->contains($matchBannedChampion)) {
            $this->matchBannedChampions[] = $matchBannedChampion;
            $matchBannedChampion->setMatchId($this);
        }

        return $this;
    }

    public function removeMatchBannedChampion(MatchBannedChampion $matchBannedChampion): self
    {
        if ($this->matchBannedChampions->removeElement($matchBannedChampion)) {
            // set the owning side to null (unless already changed)
            if ($matchBannedChampion->getMatchId() === $this) {
                $matchBannedChampion->setMatchId(null);
            }
        }

        return $this;
    }
}
