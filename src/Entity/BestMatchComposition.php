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
     * @ORM\ManyToOne(targetEntity=Compositions::class, inversedBy="bestMatchCompositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compositionId;

    /**
     * @ORM\ManyToOne(targetEntity=BestMatch::class, inversedBy="bestMatchCompositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bestMatchId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $win;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompositionId(): ?Compositions
    {
        return $this->compositionId;
    }

    public function setCompositionId(?Compositions $compositionId): self
    {
        $this->compositionId = $compositionId;

        return $this;
    }

    public function getBestMatchId(): ?BestMatch
    {
        return $this->bestMatchId;
    }

    public function setBestMatchId(?BestMatch $bestMatchId): self
    {
        $this->bestMatchId = $bestMatchId;

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
