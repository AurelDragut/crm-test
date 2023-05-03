<?php

namespace App\Entity;

use App\Repository\SlideRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlideRepository::class)]
class Slide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Bild;

    #[ORM\Column(type: 'text', nullable: true)]
    private $Text;

    #[ORM\Column(type: 'integer')]
    private $Reihenfolge;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(?string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getReihenfolge(): ?int
    {
        return $this->Reihenfolge;
    }

    public function setReihenfolge(int $Reihenfolge): self
    {
        $this->Reihenfolge = $Reihenfolge;

        return $this;
    }
}
