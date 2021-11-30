<?php

namespace App\Entity;

use App\Repository\BestMatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BestMatchRepository::class)
 */
class BestMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateMatch;

    /**
     * @ORM\OneToMany(targetEntity=BestMatchComposition::class, mappedBy="bestMatch", orphanRemoval=true)
     */
    private $bestMatchCompositions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $riotId;

    public function __construct()
    {
        $this->bestMatchCompositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(\DateTimeInterface $dateMatch): self
    {
        $this->dateMatch = $dateMatch;

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
            $bestMatchComposition->setBestMatch($this);
        }

        return $this;
    }

    public function removeBestMatchComposition(BestMatchComposition $bestMatchComposition): self
    {
        if ($this->bestMatchCompositions->removeElement($bestMatchComposition)) {
            // set the owning side to null (unless already changed)
            if ($bestMatchComposition->getBestMatch() === $this) {
                $bestMatchComposition->setBestMatch(null);
            }
        }

        return $this;
    }

    public function getRiotId(): ?string
    {
        return $this->riotId;
    }

    public function setRiotId(string $riotId): self
    {
        $this->riotId = $riotId;

        return $this;
    }
}
