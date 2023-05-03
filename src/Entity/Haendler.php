<?php

namespace App\Entity;

use App\Repository\HaendlerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HaendlerRepository::class)]
class Haendler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $URL;

    #[ORM\Column(type: 'string', length: 255)]
    private $Bild;

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

    public function getURL(): ?string
    {
        return $this->URL;
    }

    public function setURL(?string $URL): self
    {
        $this->URL = $URL;

        return $this;
    }

    public function getBild(): ?string
    {
        return $this->Bild;
    }

    public function setBild(string $Bild): self
    {
        $this->Bild = $Bild;

        return $this;
    }
}
