<?php

namespace App\Entity;

use App\Repository\EmailVorlagRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailVorlagRepository::class)]
class EmailVorlag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Inhalt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInhalt(): ?string
    {
        return $this->Inhalt;
    }

    public function setInhalt(string $Inhalt): self
    {
        $this->Inhalt = $Inhalt;

        return $this;
    }

    public function __toString(): string
    {
        return 'E-Mail-Vorlag für '.$this->getName();
    }
}
