<?php

namespace App\Entity;

use App\Repository\ArtikelRepository;
use App\Repository\AuftragRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtikelRepository::class)]
class Artikel
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $Artikelnummer;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $Bezeichnung;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive]
    private int $Menge;

    #[ORM\OneToMany(mappedBy: 'artikel', targetEntity: Auftrag::class)]
    private $Auftrag;

    #[ORM\ManyToOne(targetEntity: Rechnung::class, inversedBy: 'artikels')]
    #[ORM\JoinColumn(name: 'rechnung_id', referencedColumnName: 'rechnungsnummer')]
    private Rechnung $Rechnung;

    #[ORM\ManyToOne(inversedBy: 'Artikel')]
    #[ORM\JoinColumn(nullable: true)]
    private ?BasisArtikel $basisArtikel = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $zeitstempel;

    public function __construct()
    {
        $this->Auftrag = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getArtikelnummer(): string
    {
        return $this->Artikelnummer;
    }

    public function setArtikelnummer(string $Artikelnummer): self
    {
        $this->Artikelnummer = $Artikelnummer;

        return $this;
    }

    public function getBezeichnung(): string
    {
        return $this->Bezeichnung;
    }

    public function setBezeichnung(string $Bezeichnung): self
    {
        $this->Bezeichnung = $Bezeichnung;

        return $this;
    }

    public function getMenge(): int
    {
        return $this->Menge;
    }

    public function setMenge(?int $Menge): self
    {
        $this->Menge = $Menge;

        return $this;
    }

    /**
     * @return Collection<int, Auftrag>
     */
    public function getAuftrag(): Collection
    {
        return $this->Auftrag;
    }

    public function addAuftrag(Auftrag $auftrag): self
    {
        if (!$this->Auftrag->contains($auftrag)) {
            $this->Auftrag[] = $auftrag;
            $auftrag->setArtikel($this);
        }

        return $this;
    }

    public function removeAuftrag(Auftrag $auftrag): self
    {
        if ($this->Auftrag->removeElement($auftrag)) {
            // set the owning side to null (unless already changed)
            if ($auftrag->getArtikel() === $this) {
                $auftrag->setArtikel(null);
            }
        }

        return $this;
    }

    public function getRechnung(): ?Rechnung
    {
        return $this->Rechnung;
    }

    public function setRechnung(?Rechnung $Rechnung): self
    {
        $this->Rechnung = $Rechnung;

        return $this;
    }

    public function getAuftraege(): int
    {
        return ($this->getAuftrag()->count() > 0);
    }

    public function __toString(): string
    {
        return $this->getBezeichnung();
    }

    public function getBasisArtikel(): ?BasisArtikel
    {
        return $this->basisArtikel;
    }

    public function setBasisArtikel(?BasisArtikel $basisArtikel): self
    {
        $this->basisArtikel = $basisArtikel;

        return $this;
    }

    public function getzeitstempel(): \DateTime
    {
        return $this->zeitstempel;
    }

    public function setzeitstempel(string $zeitstempel): self
    {
        $this->zeitstempel = new DateTime($zeitstempel);

        return $this;
    }
}
