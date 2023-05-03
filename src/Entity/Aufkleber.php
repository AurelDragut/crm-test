<?php

namespace App\Entity;

use App\Repository\AufkleberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AufkleberRepository::class)]
class Aufkleber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Artikelnummer = null;

    #[ORM\Column(length: 255)]
    private ?string $Abmessungen = null;

    #[ORM\Column(length: 255)]
    private ?string $Hersteller = null;

    #[ORM\Column(length: 255)]
    private ?string $LinkFA = null;

    #[ORM\Column(length: 255)]
    private ?string $LinkWMD = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Bestellungsdatum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BasisArtikel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtikelnummer(): ?string
    {
        return $this->Artikelnummer;
    }

    public function setArtikelnummer(string $Artikelnummer): self
    {
        $this->Artikelnummer = $Artikelnummer;

        return $this;
    }

    public function getAbmessungen(): ?string
    {
        return $this->Abmessungen;
    }

    public function setAbmessungen(string $Abmessungen): self
    {
        $this->Abmessungen = $Abmessungen;

        return $this;
    }

    public function getHersteller(): ?string
    {
        return $this->Hersteller;
    }

    public function setHersteller(string $Hersteller): self
    {
        $this->Hersteller = $Hersteller;

        return $this;
    }

    public function getLinkFA(): ?string
    {
        return $this->LinkFA;
    }

    public function setLinkFA(string $LinkFA): self
    {
        $this->LinkFA = $LinkFA;

        return $this;
    }

    public function getLinkWMD(): ?string
    {
        return $this->LinkWMD;
    }

    public function setLinkWMD(string $LinkWMD): self
    {
        $this->LinkWMD = $LinkWMD;

        return $this;
    }

    public function getBestellungsdatum(): ?\DateTimeInterface
    {
        return $this->Bestellungsdatum;
    }

    public function setBestellungsdatum(\DateTimeInterface $Bestellungsdatum): self
    {
        $this->Bestellungsdatum = $Bestellungsdatum;

        return $this;
    }

    public function getBasisArtikel(): ?string
    {
        return $this->BasisArtikel;
    }

    public function setBasisArtikel(?string $BasisArtikel): self
    {
        $this->BasisArtikel = $BasisArtikel;

        return $this;
    }
}
