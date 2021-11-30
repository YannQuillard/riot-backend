<?php

namespace App\Entity;

use App\Repository\BestMatchCompositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BestMatchCompositionRepository::class)
 */
class BestMatchComposition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Composition::class, inversedBy="bestMatchCompositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $composition;

    /**
     * @ORM\ManyToOne(targetEntity=BestMatch::class, inversedBy="bestMatchCompositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bestMatch;

    /**
     * @ORM\Column(type="boolean")
     */
    private $win;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComposition(): ?Composition
    {
        return $this->composition;
    }

    public function setComposition(?Composition $composition): self
    {
        $this->composition = $composition;

        return $this;
    }

    public function getBestMatch(): ?BestMatch
    {
        return $this->bestMatch;
    }

    public function setBestMatch(?BestMatch $bestMatch): self
    {
        $this->bestMatch = $bestMatch;

        return $this;
    }

    public function getWin(): ?bool
    {
        return $this->win;
    }

    public function setWin(bool $win): self
    {
        $this->win = $win;

        return $this;
    }
}
