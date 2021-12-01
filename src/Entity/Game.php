<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $gameId;

    /**
     * @ORM\OneToMany(targetEntity=Ban::class, mappedBy="game", orphanRemoval=true)
     */
    private $bans;

    /**
     * @ORM\OneToMany(targetEntity=Pick::class, mappedBy="game", orphanRemoval=true)
     */
    private $picks;

    public function __construct()
    {
        $this->bans = new ArrayCollection();
        $this->picks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    public function setGameId(string $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
    }

    /**
     * @return Collection|Ban[]
     */
    public function getBans(): Collection
    {
        return $this->bans;
    }

    public function addBan(Ban $ban): self
    {
        if (!$this->bans->contains($ban)) {
            $this->bans[] = $ban;
            $ban->setGame($this);
        }

        return $this;
    }

    public function removeBan(Ban $ban): self
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getGame() === $this) {
                $ban->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pick[]
     */
    public function getPicks(): Collection
    {
        return $this->picks;
    }

    public function addPick(Pick $pick): self
    {
        if (!$this->picks->contains($pick)) {
            $this->picks[] = $pick;
            $pick->setGame($this);
        }

        return $this;
    }

    public function removePick(Pick $pick): self
    {
        if ($this->picks->removeElement($pick)) {
            // set the owning side to null (unless already changed)
            if ($pick->getGame() === $this) {
                $pick->setGame(null);
            }
        }

        return $this;
    }
}
