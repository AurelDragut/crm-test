<?php

namespace App\Entity;

use App\Repository\AuftragStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: AuftragStatusRepository::class)]
class AuftragStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Auftrag::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auftrag $Auftrag;

    #[ORM\ManyToOne(targetEntity: Status::class, cascade: ['persist'], inversedBy: 'auftragStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $Status;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $Datum;

    #[ORM\ManyToOne(targetEntity: Benutzer::class, inversedBy: 'auftragStatuses')]
    private ?Benutzer $Mitarbeiter;

    #[ORM\ManyToOne(targetEntity: Auftrag::class, inversedBy: 'AuftragStatuses')]
    #[JoinColumn(name: "auftrag_id", referencedColumnName: "id")]
    private ?Auftrag $auftrag;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?Status
    {
        return $this->Status;
    }

    public function setStatus(?Status $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->Datum;
    }

    public function setDatum(\DateTime $Datum): self
    {
        $this->Datum = new \DateTimeImmutable();

        return $this;
    }

    public function __toString(){
        return '"'.$this->getStatus()->getBezeichnung() . '" - ' . $this->getMitarbeiter()->getName() . ' - ' . $this->getDatum()->format('Y-m-d') . ' - ' . $this->getDatum()->format('H:m:s');
    }

    public function getMitarbeiter(): ?Benutzer
    {
        return $this->Mitarbeiter;
    }

    public function setMitarbeiter(?Benutzer $Mitarbeiter): self
    {
        $this->Mitarbeiter = $Mitarbeiter;

        return $this;
    }
}
