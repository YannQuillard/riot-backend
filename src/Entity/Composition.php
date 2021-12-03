<?php

namespace App\Entity;

use App\Repository\CompositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompositionRepository::class)
 */
class Composition
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
    private $hash;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $losses;

    /**
     * @ORM\ManyToMany(targetEntity=Champion::class, inversedBy="compositions")
     */
    private $champions;

    /**
     * @ORM\OneToMany(targetEntity=BestMatchComposition::class, mappedBy="composition", orphanRemoval=true)
     */
    private $bestMatchCompositions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $winRate;

    public function __construct()
    {
        $this->champions = new ArrayCollection();
        $this->bestMatchCompositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
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
     * @return Collection|Champion[]
     */
    public function getChampions(): Collection
    {
        return $this->champions;
    }

    public function addChampion(Champion $champion): self
    {
        if (!$this->champions->contains($champion)) {
            $this->champions[] = $champion;
        }

        return $this;
    }

    public function removeChampion(Champion $champion): self
    {
        $this->champions->removeElement($champion);

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
            $bestMatchComposition->setComposition($this);
        }

        return $this;
    }

    public function removeBestMatchComposition(BestMatchComposition $bestMatchComposition): self
    {
        if ($this->bestMatchCompositions->removeElement($bestMatchComposition)) {
            // set the owning side to null (unless already changed)
            if ($bestMatchComposition->getComposition() === $this) {
                $bestMatchComposition->setComposition(null);
            }
        }

        return $this;
    }

    public function getWinRate(): ?int
    {
        return $this->winRate;
    }

    public function setWinRate(?int $winRate): self
    {
        $this->winRate = $winRate;

        return $this;
    }
}
