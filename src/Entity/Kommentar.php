<?php

namespace App\Entity;

use App\Repository\KommentarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KommentarRepository::class)]
class Kommentar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Benutzer::class, inversedBy: 'kommentars')]
    private $Verfasser;

    #[ORM\Column(type: 'text')]
    private $Inhalt;

    #[ORM\Column(type: 'datetime')]
    private $ErstelltAm;

    #[ORM\ManyToOne(targetEntity: Auftrag::class, inversedBy: 'kommentars')]
    private $Auftrag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerfasser(): ?Benutzer
    {
        return $this->Verfasser;
    }

    public function setVerfasser(?Benutzer $Verfasser): self
    {
        $this->Verfasser = $Verfasser;

        return $this;
    }

    public function getInhalt(): ?string
    {
        return $this->Inhalt;
    }

    public function setInhalt(string $Inhalt): self
    {
        $this->Inhalt = $Inhalt;

        return $this;
    }

    public function getErstelltAm(): ?\DateTimeInterface
    {
        return $this->ErstelltAm;
    }

    public function setErstelltAm(\DateTimeInterface $ErstelltAm): self
    {
        $this->ErstelltAm = $ErstelltAm;

        return $this;
    }

    public function getAuftrag(): ?Auftrag
    {
        return $this->Auftrag;
    }

    public function setAuftrag(?Auftrag $Auftrag): self
    {
        $this->Auftrag = $Auftrag;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getVerfasser().' - '.$this->getInhalt().' - '.$this->getErstelltAm()->format('H:m:s D d M Y');
    }
}
