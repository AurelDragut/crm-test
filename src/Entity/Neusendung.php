<?php

namespace App\Entity;

use App\Repository\NeusendungRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeusendungRepository::class)]
class Neusendung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $RechnungsNr = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Datum = null;

    #[ORM\Column(length: 255)]
    private ?string $Stueck_ArtNr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Kommentar = null;

    #[ORM\Column(length: 255)]
    private ?string $SendungsTyp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRechnungsNr(): ?string
    {
        return $this->RechnungsNr;
    }

    public function setRechnungsNr(string $RechnungsNr): self
    {
        $this->RechnungsNr = $RechnungsNr;

        return $this;
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

    public function getStueckArtNr(): ?string
    {
        return $this->Stueck_ArtNr;
    }

    public function setStueckArtNr(string $Stueck_ArtNr): self
    {
        $this->Stueck_ArtNr = $Stueck_ArtNr;

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

    public function getSendungsTyp(): ?string
    {
        return $this->SendungsTyp;
    }

    public function setSendungsTyp(string $SendungsTyp): self
    {
        $this->SendungsTyp = $SendungsTyp;

        return $this;
    }
}
