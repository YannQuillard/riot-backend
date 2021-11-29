<?php

namespace App\Entity;

use App\Repository\CompositionChampionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompositionChampionRepository::class)
 */
class CompositionChampion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Compositions::class, inversedBy="compositionChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CompositionId;

    /**
     * @ORM\ManyToOne(targetEntity=Champions::class, inversedBy="compositionChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ChampionId;

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

    public function getChampionId(): ?Champions
    {
        return $this->ChampionId;
    }

    public function setChampionId(?Champions $ChampionId): self
    {
        $this->ChampionId = $ChampionId;

        return $this;
    }
}
