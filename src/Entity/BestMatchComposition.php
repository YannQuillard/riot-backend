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
    private $CompositionId;

    /**
     * @ORM\ManyToOne(targetEntity=BestMatch::class, inversedBy="bestMatchCompositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $BestMatchId;

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
        return $this->CompositionId;
    }

    public function setCompositionId(?Compositions $CompositionId): self
    {
        $this->CompositionId = $CompositionId;

        return $this;
    }

    public function getBestMatchId(): ?BestMatch
    {
        return $this->BestMatchId;
    }

    public function setBestMatchId(?BestMatch $BestMatchId): self
    {
        $this->BestMatchId = $BestMatchId;

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
