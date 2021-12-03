<?php

namespace App\Entity;

use App\Repository\ChampionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChampionRepository::class)
 */
class Champion
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
    private $riotId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Composition::class, mappedBy="champions")
     */
    private $compositions;

    /**
     * @ORM\OneToMany(targetEntity=Ban::class, mappedBy="champion", orphanRemoval=true)
     */
    private $bans;

    /**
     * @ORM\OneToMany(targetEntity=Pick::class, mappedBy="champion", orphanRemoval=true)
     */
    private $picks;

    /**
     * @ORM\ManyToMany(targetEntity=Lane::class)
     */
    private $lane;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $losses;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageLoading;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageSplash;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $winRate;

    public function __construct()
    {
        $this->compositions = new ArrayCollection();
        $this->bans = new ArrayCollection();
        $this->picks = new ArrayCollection();
        $this->lane = new ArrayCollection();
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRiotId(): ?int
    {
        return $this->riotId;
    }

    public function setRiotId(int $riotId): self
    {
        $this->riotId = $riotId;

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
     * @return Collection|Composition[]
     */
    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addComposition(Composition $composition): self
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions[] = $composition;
            $composition->addChampion($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition): self
    {
        if ($this->compositions->removeElement($composition)) {
            $composition->removeChampion($this);
        }

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
            $ban->setChampion($this);
        }

        return $this;
    }

    public function removeBan(Ban $ban): self
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getChampion() === $this) {
                $ban->setChampion(null);
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
            $pick->setChampion($this);
        }

        return $this;
    }

    public function removePick(Pick $pick): self
    {
        if ($this->picks->removeElement($pick)) {
            // set the owning side to null (unless already changed)
            if ($pick->getChampion() === $this) {
                $pick->setChampion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lane[]
     */
    public function getLane(): Collection
    {
        return $this->lane;
    }

    public function addLane(Lane $lane): self
    {
        if (!$this->lane->contains($lane)) {
            $this->lane[] = $lane;
        }

        return $this;
    }

    public function removeLane(Lane $lane): self
    {
        $this->lane->removeElement($lane);

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getImageLoading(): ?string
    {
        return $this->imageLoading;
    }

    public function setImageLoading(string $imageLoading): self
    {
        $this->imageLoading = $imageLoading;

        return $this;
    }

    public function getImageSplash(): ?string
    {
        return $this->imageSplash;
    }

    public function setImageSplash(string $imageSplash): self
    {
        $this->imageSplash = $imageSplash;

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
