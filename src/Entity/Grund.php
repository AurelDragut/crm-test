<?php

namespace App\Entity;

use App\Repository\GrundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: GrundRepository::class)]
class Grund
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Inhalt;

    #[ORM\Column(type: 'string', length: 255)]
    private $ZielEmail;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titel;

    #[ORM\OneToMany(mappedBy: 'Grund', targetEntity: Auftrag::class)]
    private $auftrags;

    public function __construct()
    {
        $this->auftrags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getZielEmail(): ?string
    {
        return $this->ZielEmail;
    }

    public function setZielEmail(string $ZielEmail): self
    {
        $this->ZielEmail = $ZielEmail;

        return $this;
    }

    public function getTitel(): ?string
    {
        return $this->Titel;
    }

    public function setTitel(string $Titel): self
    {
        $this->Titel = $Titel;

        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return $this->getTitel();
    }

    /**
     * @return Collection<int, Auftrag>
     */
    public function getAuftrags(): Collection
    {
        return $this->auftrags;
    }

    public function auftragsCount(): int
    {
        return $this->auftrags->count();
    }

    public function addAuftrag(Auftrag $auftrag): self
    {
        if (!$this->auftrags->contains($auftrag)) {
            $this->auftrags[] = $auftrag;
            $auftrag->setGrund($this);
        }

        return $this;
    }

    public function removeAuftrag(Auftrag $auftrag): self
    {
        if ($this->auftrags->removeElement($auftrag)) {
            // set the owning side to null (unless already changed)
            if ($auftrag->getGrund() === $this) {
                $auftrag->setGrund(null);
            }
        }

        return $this;
    }
}
