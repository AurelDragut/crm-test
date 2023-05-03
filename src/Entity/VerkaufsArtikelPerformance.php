<?php

namespace App\Entity;

use App\Repository\VerkaufsArtikelPerformanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VerkaufsArtikelPerformanceRepository::class)]
class VerkaufsArtikelPerformance
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
    private ?string $VerkaufsArtikel = null;

    #[ORM\Column(length: 255)]
    private ?string $Plattform = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Informiert = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Auffaeligkeit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Gecheckt = null;

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

    public function getVerkaufsArtikel(): ?string
    {
        return $this->VerkaufsArtikel;
    }

    public function setVerkaufsArtikel(string $VerkaufsArtikel): self
    {
        $this->VerkaufsArtikel = $VerkaufsArtikel;

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

    public function getAuffaeligkeit(): ?string
    {
        return $this->Auffaeligkeit;
    }

    public function setAuffaeligkeit(?string $Auffaeligkeit): self
    {
        $this->Auffaeligkeit = $Auffaeligkeit;

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
}
