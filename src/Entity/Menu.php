<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titel;

    #[ORM\ManyToMany(targetEntity: MenuePunkt::class, inversedBy: 'menues')]
    #[ORM\JoinTable(name: "menu_menue_punkt")]
    private $MenuePunkte;

    #[ORM\Column(type: 'integer')]
    private $position;

    public function __construct()
    {
        $this->MenuePunkte = new ArrayCollection();
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

    /**
     * @return Collection<int, MenuePunkt>
     */
    public function getMenuePunkte(): Collection
    {
        return $this->MenuePunkte;
    }

    public function addMenuePunkte(MenuePunkt $menuePunkte): self
    {
        if (!$this->MenuePunkte->contains($menuePunkte)) {
            $this->MenuePunkte[] = $menuePunkte;
        }

        return $this;
    }

    public function removeMenuePunkte(MenuePunkt $menuePunkte): self
    {
        $this->MenuePunkte->removeElement($menuePunkte);

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

    public function __toString() {
        return $this->getTitel();
    }
}
