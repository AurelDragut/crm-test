<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $Bezeichnung;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $Farbe;

    #[ORM\OneToMany(mappedBy: 'Status', targetEntity: AuftragStatus::class)]
    private $auftragStatuses;

    #[ORM\OneToMany(mappedBy: 'Status', targetEntity: Auftrag::class)]
    private $auftrags;

    public function __construct()
    {
        $this->auftragStatuses = new ArrayCollection();
        $this->auftrags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBezeichnung(): ?string
    {
        return $this->Bezeichnung;
    }

    public function setBezeichnung(string $Bezeichnung): self
    {
        $this->Bezeichnung = $Bezeichnung;

        return $this;
    }

    public function getFarbe(): ?string
    {
        return $this->Farbe;
    }

    public function setFarbe(string $Farbe): self
    {
        $this->Farbe = $Farbe;

        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return $this->getBezeichnung();
    }

    /**
     * @return Collection<int, AuftragStatus>
     */
    public function getAuftragStatuses(): Collection
    {
        return $this->auftragStatuses;
    }

    public function addAuftragStatus(AuftragStatus $auftragStatus): self
    {
        if (!$this->auftragStatuses->contains($auftragStatus)) {
            $this->auftragStatuses[] = $auftragStatus;
            $auftragStatus->setStatus($this);
        }

        return $this;
    }

    public function removeAuftragStatus(AuftragStatus $auftragStatus): self
    {
        if ($this->auftragStatuses->removeElement($auftragStatus)) {
            // set the owning side to null (unless already changed)
            if ($auftragStatus->getStatus() === $this) {
                $auftragStatus->setStatus(null);
            }
        }

        return $this;
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
            $auftrag->setStatus($this);
        }

        return $this;
    }

    public function removeAuftrag(Auftrag $auftrag): self
    {
        if ($this->auftrags->removeElement($auftrag)) {
            // set the owning side to null (unless already changed)
            if ($auftrag->getStatus() === $this) {
                $auftrag->setStatus(null);
            }
        }

        return $this;
    }
}
