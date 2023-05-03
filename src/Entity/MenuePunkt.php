<?php

namespace App\Entity;

use App\Repository\MenuePunktRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: MenuePunktRepository::class)]
class MenuePunkt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titel;

    #[ORM\ManyToOne(targetEntity: Seite::class, inversedBy: 'menuePunkts')]
    private $Seite;

    #[ORM\Column(type: 'integer')]
    private $position;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'MenuePunkte')]
    #[ORM\JoinTable(name: "menu_menue_punkt")]
    private $menues;

    public function __construct()
    {
        $this->menues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitel(): ?string
    {
        return $this->Titel;
    }

    public function setTitel(string $Titel): self
    {
        $this->Titel = $Titel;

        return $this;
    }

    public function getSeite(): ?Seite
    {
        return $this->Seite;
    }

    public function setSeite(?Seite $Seite): self
    {
        $this->Seite = $Seite;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitel();
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenues(): Collection
    {
        return $this->menues;
    }

    public function addMenue(Menu $menue): self
    {
        if (!$this->menues->contains($menue)) {
            $this->menues[] = $menue;
            $menue->addMenuePunkte($this);
        }

        return $this;
    }

    public function removeMenue(Menu $menue): self
    {
        if ($this->menues->removeElement($menue)) {
            $menue->removeMenuePunkte($this);
        }

        return $this;
    }
}
