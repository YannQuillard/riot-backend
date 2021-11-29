<?php

namespace App\Entity;

use App\Repository\CompositionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompositionsRepository::class)
 */
class Compositions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $losses;

    /**
     * @ORM\OneToMany(targetEntity=BestMatchComposition::class, mappedBy="CompositionId", orphanRemoval=true)
     */
    private $bestMatchCompositions;

    /**
     * @ORM\OneToMany(targetEntity=CompositionChampion::class, mappedBy="CompositionId", orphanRemoval=true)
     */
    private $compositionChampions;

    public function __construct()
    {
        $this->bestMatchCompositions = new ArrayCollection();
        $this->compositionChampions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(?int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLosses(): ?int
    {
        return $this->losses;
    }

    public function setLosses(?int $losses): self
    {
        $this->losses = $losses;

        return $this;
    }

    /**
     * @return Collection|BestMatchComposition[]
     */
    public function getBestMatchCompositions(): Collection
    {
        return $this->bestMatchCompositions;
    }

    public function addBestMatchComposition(BestMatchComposition $bestMatchComposition): self
    {
        if (!$this->bestMatchCompositions->contains($bestMatchComposition)) {
            $this->bestMatchCompositions[] = $bestMatchComposition;
            $bestMatchComposition->setCompositionId($this);
        }

        return $this;
    }

    public function removeBestMatchComposition(BestMatchComposition $bestMatchComposition): self
    {
        if ($this->bestMatchCompositions->removeElement($bestMatchComposition)) {
            // set the owning side to null (unless already changed)
            if ($bestMatchComposition->getCompositionId() === $this) {
                $bestMatchComposition->setCompositionId(null);
            }
        }

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
            $compositionChampion->setCompositionId($this);
        }

        return $this;
    }

    public function removeCompositionChampion(CompositionChampion $compositionChampion): self
    {
        if ($this->compositionChampions->removeElement($compositionChampion)) {
            // set the owning side to null (unless already changed)
            if ($compositionChampion->getCompositionId() === $this) {
                $compositionChampion->setCompositionId(null);
            }
        }

        return $this;
    }
}
