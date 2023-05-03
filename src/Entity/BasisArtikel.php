<?php

namespace App\Entity;

use App\Repository\BasisArtikelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasisArtikelRepository::class)]
class BasisArtikel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private string $ArtikelNummer;

    #[ORM\Column]
    private ?int $Menge = null;

    #[ORM\Column]
    private ?int $Auftraege = null;

    #[ORM\OneToMany(mappedBy: 'basisArtikel', targetEntity: Artikel::class, cascade:["remove"])]
    private Collection $Artikel;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtikelNummer(): string
    {
        return $this->ArtikelNummer;
    }

    public function setArtikelNummer($ArtikelNummer): void
    {
        $this->ArtikelNummer = $ArtikelNummer;
    }

    public function getMenge(): ?int
    {
        return $this->Menge;
    }

    public function setMenge(int $Menge): self
    {
        $this->Menge = $Menge;

        return $this;
    }

    public function getAuftraege(): ?int
    {
        return $this->Auftraege;
    }

    public function setAuftraege(int $Auftraege): self
    {
        $this->Auftraege = $Auftraege;

        return $this;
    }

    /**
     * @return Collection<int, Artikel>
     */
    public function getArtikel(): Collection
    {
        return $this->Artikel;
    }

    public function addArtikel(Artikel $Artikel): self
    {
        if (!$this->Artikel->contains($Artikel)) {
            $this->Artikel[] = $Artikel;
            $Artikel->setBasisArtikel($this);
        }

        return $this;
    }

    public function removeArtikel(Artikel $Artikel): self
    {
        if ($this->Artikel->removeElement($Artikel)) {
            // set the owning side to null (unless already changed)
            if ($Artikel->getBasisArtikel() === $this) {
                $Artikel->setBasisArtikel(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->getArtikelNummer();
    }
}
