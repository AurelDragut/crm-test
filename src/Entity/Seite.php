<?php

namespace App\Entity;

use App\Repository\SeiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeiteRepository::class)]
class Seite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titel;

    #[ORM\Column(type: 'text', nullable: true)]
    private $MetaBeschreibung;

    #[ORM\Column(type: 'text')]
    private $Inhalt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Schnecke;

    #[ORM\OneToMany(mappedBy: 'Seite', targetEntity: MenuePunkt::class)]
    private $menuePunkts;

    public function __construct()
    {
        $this->menuePunkts = new ArrayCollection();
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

    public function getMetaBeschreibung(): ?string
    {
        return $this->MetaBeschreibung;
    }

    public function setMetaBeschreibung(?string $MetaBeschreibung): self
    {
        $this->MetaBeschreibung = $MetaBeschreibung;

        return $this;
    }

    public function getInhalt(): ?string
    {
        return $this->Inhalt;
    }

    public function setInhalt(string $Inhalt): self
    {
        $this->Inhalt = $Inhalt;

        return $this;
    }

    public function getSchnecke(): ?string
    {
        return $this->Schnecke;
    }

    public function setSchnecke(?string $Schnecke): self
    {
        $this->Schnecke = $Schnecke;

        return $this;
    }

    /**
     * @return Collection<int, MenuePunkt>
     */
    public function getMenuePunkts(): Collection
    {
        return $this->menuePunkts;
    }

    public function addMenuePunkt(MenuePunkt $menuePunkt): self
    {
        if (!$this->menuePunkts->contains($menuePunkt)) {
            $this->menuePunkts[] = $menuePunkt;
            $menuePunkt->setSeite($this);
        }

        return $this;
    }

    public function removeMenuePunkt(MenuePunkt $menuePunkt): self
    {
        if ($this->menuePunkts->removeElement($menuePunkt)) {
            // set the owning side to null (unless already changed)
            if ($menuePunkt->getSeite() === $this) {
                $menuePunkt->setSeite(null);
            }
        }

        return $this;
    }

    #[Pure] public function __toString() {
        return $this->getTitel();
    }
}
