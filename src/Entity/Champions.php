<?php

namespace App\Entity;

use App\Repository\ChampionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChampionsRepository::class)
 */
class Champions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $championId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CompositionChampion::class, mappedBy="ChampionId", orphanRemoval=true)
     */
    private $compositionChampions;

    /**
     * @ORM\OneToMany(targetEntity=PlayerChampion::class, mappedBy="championId", orphanRemoval=true)
     */
    private $playerChampions;

    /**
     * @ORM\OneToMany(targetEntity=MatchPlayerChampion::class, mappedBy="championId", orphanRemoval=true)
     */
    private $matchPlayerChampions;

    /**
     * @ORM\OneToMany(targetEntity=MatchBannedChampion::class, mappedBy="championId", orphanRemoval=true)
     */
    private $matchBannedChampions;

    public function __construct()
    {
        $this->compositionChampions = new ArrayCollection();
        $this->playerChampions = new ArrayCollection();
        $this->matchPlayerChampions = new ArrayCollection();
        $this->matchBannedChampions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChampionId(): ?int
    {
        return $this->championId;
    }

    public function setChampionId(int $championId): self
    {
        $this->championId = $championId;

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
     * @return Collection|CompositionChampion[]
     */
    public function getCompositionChampions(): Collection
    {
        return $this->compositionChampions;
    }

    public function addCompositionChampion(CompositionChampion $compositionChampion): self
    {
        if (!$this->compositionChampions->contains($compositionChampion)) {
            $this->compositionChampions[] = $compositionChampion;
            $compositionChampion->setChampionId($this);
        }

        return $this;
    }

    public function removeCompositionChampion(CompositionChampion $compositionChampion): self
    {
        if ($this->compositionChampions->removeElement($compositionChampion)) {
            // set the owning side to null (unless already changed)
            if ($compositionChampion->getChampionId() === $this) {
                $compositionChampion->setChampionId(null);
            }
        }

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
            $playerChampion->setChampionId($this);
        }

        return $this;
    }

    public function removePlayerChampion(PlayerChampion $playerChampion): self
    {
        if ($this->playerChampions->removeElement($playerChampion)) {
            // set the owning side to null (unless already changed)
            if ($playerChampion->getChampionId() === $this) {
                $playerChampion->setChampionId(null);
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
            $matchPlayerChampion->setChampionId($this);
        }

        return $this;
    }

    public function removeMatchPlayerChampion(MatchPlayerChampion $matchPlayerChampion): self
    {
        if ($this->matchPlayerChampions->removeElement($matchPlayerChampion)) {
            // set the owning side to null (unless already changed)
            if ($matchPlayerChampion->getChampionId() === $this) {
                $matchPlayerChampion->setChampionId(null);
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
            $matchBannedChampion->setChampionId($this);
        }

        return $this;
    }

    public function removeMatchBannedChampion(MatchBannedChampion $matchBannedChampion): self
    {
        if ($this->matchBannedChampions->removeElement($matchBannedChampion)) {
            // set the owning side to null (unless already changed)
            if ($matchBannedChampion->getChampionId() === $this) {
                $matchBannedChampion->setChampionId(null);
            }
        }

        return $this;
    }
}
