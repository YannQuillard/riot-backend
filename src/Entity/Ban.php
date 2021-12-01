<?php

namespace App\Entity;

use App\Repository\BanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BanRepository::class)
 */
class Ban
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Champion::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $champion;

    /**
     * @ORM\Column(type="integer")
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getChampion(): ?Champion
    {
        return $this->champion;
    }

    public function setChampion(?Champion $champion): self
    {
        $this->champion = $champion;

        return $this;
    }

    public function getTeam(): ?int
    {
        return $this->team;
    }

    public function setTeam(int $team): self
    {
        $this->team = $team;

        return $this;
    }
}
