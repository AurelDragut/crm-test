<?php

namespace App\Entity;

use App\Repository\BWareRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BWareRepository::class)]
class BWare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $EingangsDatum = null;

    #[ORM\Column(length: 255)]
    private ?string $ArtikelNummer = null;

    #[ORM\Column(name: 'stück')]
    private ?int $Stueck = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Status = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEingangsDatum(): ?\DateTimeInterface
    {
        return $this->EingangsDatum;
    }

    public function setEingangsDatum(\DateTimeInterface $EingangsDatum): self
    {
        $this->EingangsDatum = $EingangsDatum;

        return $this;
    }

    public function getArtikelNummer(): ?string
    {
        return $this->ArtikelNummer;
    }

    public function setArtikelNummer(string $ArtikelNummer): self
    {
        $this->ArtikelNummer = $ArtikelNummer;

        return $this;
    }

    public function getStueck(): ?int
    {
        return $this->Stueck;
    }

    public function setStueck(int $Stueck): self
    {
        $this->Stueck = $Stueck;

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
