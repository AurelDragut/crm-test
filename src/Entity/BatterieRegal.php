<?php

namespace App\Entity;

use App\Repository\BatterieRegalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BatterieRegalRepository::class)]
class BatterieRegal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Artikelnummer = null;

    #[ORM\Column(length: 255)]
    private ?string $Hersteller = null;

    #[ORM\Column(length: 255)]
    private ?string $Batterietechnologie = null;

    #[ORM\Column(length: 255)]
    private ?float $Kapazitaet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?float $Kaltstartstrom = null;

    #[ORM\Column(length: 255)]
    private ?string $Masse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Standard = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?int $Regal = null;

    #[ORM\Column(length: 255)]
    private ?string $Preis = null;

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

    public function getHersteller(): ?string
    {
        return $this->Hersteller;
    }

    public function setHersteller(string $Hersteller): self
    {
        $this->Hersteller = $Hersteller;

        return $this;
    }

    public function getBatterietechnologie(): ?string
    {
        return $this->Batterietechnologie;
    }

    public function setBatterietechnologie(string $Batterietechnologie): self
    {
        $this->Batterietechnologie = $Batterietechnologie;

        return $this;
    }

    public function getKapazitaet(): ?string
    {
        return $this->Kapazitaet;
    }

    public function setKapazitaet(string $Kapazitaet): self
    {
        $this->Kapazitaet = $Kapazitaet;

        return $this;
    }

    public function getKaltstartstrom(): ?string
    {
        return $this->Kaltstartstrom;
    }

    public function setKaltstartstrom(string $Kaltstartstrom): self
    {
        $this->Kaltstartstrom = $Kaltstartstrom;

        return $this;
    }

    public function getMasse(): ?string
    {
        return $this->Masse;
    }

    public function setMasse(string $Masse): self
    {
        $this->Masse = $Masse;

        return $this;
    }

    public function getStandard(): ?string
    {
        return $this->Standard;
    }

    public function setStandard(string $Standard): self
    {
        $this->Standard = $Standard;

        return $this;
    }

    public function getRegal(): ?int
    {
        return $this->Regal;
    }

    public function setRegal(?int $Regal): self
    {
        $this->Regal = $Regal;

        return $this;
    }

    public function getPreis(): ?string
    {
        return $this->Preis;
    }

    public function setPreis(string $Preis): self
    {
        $this->Preis = $Preis;

        return $this;
    }
}
