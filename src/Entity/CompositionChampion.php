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
    private $compositionId;

    /**
     * @ORM\ManyToOne(targetEntity=Champions::class, inversedBy="compositionChampions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $championId;

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

    public function getChampionId(): ?Champions
    {
        return $this->championId;
    }

    public function setChampionId(?Champions $championId): self
    {
        $this->championId = $championId;

        return $this;
    }
}
