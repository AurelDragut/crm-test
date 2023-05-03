<?php

namespace App\Entity;

use App\Repository\FrageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FrageRepository::class)]
class Frage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Frage;

    #[ORM\Column(type: 'text')]
    private $Antwort;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrage(): ?string
    {
        return $this->Frage;
    }

    public function setFrage(string $Frage): self
    {
        $this->Frage = $Frage;

        return $this;
    }

    public function getAntwort(): ?string
    {
        return $this->Antwort;
    }

    public function setAntwort(string $Antwort): self
    {
        $this->Antwort = $Antwort;

        return $this;
    }
}
