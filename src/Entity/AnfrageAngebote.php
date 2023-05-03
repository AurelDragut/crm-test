<?php

namespace App\Entity;

use App\Repository\AnfrageAngeboteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnfrageAngeboteRepository::class)]
class AnfrageAngebote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $AnfrageDatum = null;

    #[ORM\Column(length: 255)]
    private ?string $KundenName = null;

    #[ORM\ManyToOne(inversedBy: 'AnfrageAngeboten')]
    private ?Benutzer $BearbeitetVon = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Kommentar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ergebnis = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Abgeschlossen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Status = null;

    #[ORM\Column(length: 255)]
    private ?string $KanalDerAnfrage = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnfrageDatum(): ?\DateTimeInterface
    {
        return $this->AnfrageDatum;
    }

    public function setAnfrageDatum(\DateTimeInterface $AnfrageDatum): self
    {
        $this->AnfrageDatum = $AnfrageDatum;

        return $this;
    }

    public function getKundenName(): ?string
    {
        return $this->KundenName;
    }

    public function setKundenName(string $KundenName): self
    {
        $this->KundenName = $KundenName;

        return $this;
    }

    public function getBearbeitetVon(): ?Benutzer
    {
        return $this->BearbeitetVon;
    }

    public function setBearbeitetVon(?Benutzer $BearbeitetVon): self
    {
        $this->BearbeitetVon = $BearbeitetVon;

        return $this;
    }

    public function getKommentar(): ?string
    {
        return $this->Kommentar;
    }

    public function setKommentar(?string $Kommentar): self
    {
        $this->Kommentar = $Kommentar;

        return $this;
    }

    public function getErgebnis(): ?string
    {
        return $this->Ergebnis;
    }

    public function setErgebnis(?string $Ergebnis): self
    {
        $this->Ergebnis = $Ergebnis;

        return $this;
    }

    public function isAbgeschlossen(): ?bool
    {
        return $this->Abgeschlossen;
    }

    public function setAbgeschlossen(?bool $Abgeschlossen): self
    {
        $this->Abgeschlossen = $Abgeschlossen;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getKanalDerAnfrage(): ?string
    {
        return $this->KanalDerAnfrage;
    }

    public function setKanalDerAnfrage(string $KanalDerAnfrage): self
    {
        $this->KanalDerAnfrage = $KanalDerAnfrage;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }
}
