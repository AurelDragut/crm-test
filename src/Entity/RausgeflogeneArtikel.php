<?php

namespace App\Entity;

use App\Repository\RausgeflogeneArtikelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RausgeflogeneArtikelRepository::class)]
class RausgeflogeneArtikel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Datum = null;

    #[ORM\Column(length: 255)]
    private ?string $Mitarbeiter = null;

    #[ORM\Column(length: 255)]
    private ?string $Plattform = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Informiert = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Gecheckt = null;

    #[ORM\Column(length: 255)]
    private ?string $Kundennummer = null;

    #[ORM\Column(length: 255)]
    private ?string $Bestellnummer = null;

    #[ORM\Column(length: 255)]
    private ?string $BetragsGebuehr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->Datum;
    }

    public function setDatum(\DateTimeInterface $Datum): self
    {
        $this->Datum = $Datum;

        return $this;
    }

    public function getMitarbeiter(): ?string
    {
        return $this->Mitarbeiter;
    }

    public function setMitarbeiter(string $Mitarbeiter): self
    {
        $this->Mitarbeiter = $Mitarbeiter;

        return $this;
    }

    public function getPlattform(): ?string
    {
        return $this->Plattform;
    }

    public function setPlattform(string $Plattform): self
    {
        $this->Plattform = $Plattform;

        return $this;
    }

    public function getInformiert(): ?string
    {
        return $this->Informiert;
    }

    public function setInformiert(?string $Informiert): self
    {
        $this->Informiert = $Informiert;

        return $this;
    }

    public function isGecheckt(): ?bool
    {
        return $this->Gecheckt;
    }

    public function setGecheckt(?bool $Gecheckt): self
    {
        $this->Gecheckt = $Gecheckt;

        return $this;
    }

    public function getKundennummer(): ?string
    {
        return $this->Kundennummer;
    }

    public function setKundennummer(string $Kundennummer): self
    {
        $this->Kundennummer = $Kundennummer;

        return $this;
    }

    public function getBestellnummer(): ?string
    {
        return $this->Bestellnummer;
    }

    public function setBestellnummer(string $Bestellnummer): self
    {
        $this->Bestellnummer = $Bestellnummer;

        return $this;
    }

    public function getBetragsGebuehr(): ?string
    {
        return $this->BetragsGebuehr;
    }

    public function setBetragsGebuehr(string $BetragsGebuehr): self
    {
        $this->BetragsGebuehr = $BetragsGebuehr;

        return $this;
    }
}
