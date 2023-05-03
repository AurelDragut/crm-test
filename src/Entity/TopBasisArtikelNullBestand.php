<?php

namespace App\Entity;

use App\Repository\TopBasisArtikelNullBestandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopBasisArtikelNullBestandRepository::class)]
class TopBasisArtikelNullBestand
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
    private ?string $BasisArtikel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Auffaeligkeit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Informiert = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Gecheckt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Aktion = null;

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

    public function getBasisArtikel(): ?string
    {
        return $this->BasisArtikel;
    }

    public function setBasisArtikel(string $BasisArtikel): self
    {
        $this->BasisArtikel = $BasisArtikel;

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

    public function getAktion(): ?string
    {
        return $this->Aktion;
    }

    public function setAktion(?string $Aktion): self
    {
        $this->Aktion = $Aktion;

        return $this;
    }
}
