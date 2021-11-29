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

    public function __construct()
    {
        $this->compositionChampions = new ArrayCollection();
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
}
