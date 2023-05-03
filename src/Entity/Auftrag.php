<?php

namespace App\Entity;

use App\Repository\AuftragRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: AuftragRepository::class)]
class Auftrag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Name;

    #[ORM\ManyToOne(targetEntity: Grund::class, inversedBy: 'auftrags')]
    #[Assert\NotBlank]
    private ?Grund $Grund;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $Beschreibung;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive]
    private ?int $Menge;

    #[ORM\ManyToOne(targetEntity: Benutzer::class, inversedBy: 'auftrags')]
    #[Assert\NotBlank]
    private ?Benutzer $Mitarbeiter;

    #[ORM\ManyToOne(targetEntity: Artikel::class, inversedBy: 'Auftrag')]
    #[Assert\NotBlank]
    private ?Artikel $artikel;

    #[ORM\ManyToOne(targetEntity: Rechnung::class, inversedBy: 'auftrags')]
    #[ORM\JoinColumn(name: 'rechnung_id', referencedColumnName: 'rechnungsnummer')]
    #[Assert\NotBlank]
    private ?Rechnung $Rechnung;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $Datum;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $TerminFuerReparatur;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'auftrags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $Status;
    private ?Status $AktuellerStatus;

    #[ORM\Column(type: 'boolean')]
    private bool $sendEmail;

    #[ORM\OneToMany(mappedBy: 'Auftrag', targetEntity: Kommentar::class, cascade: ["persist", "remove"])]
    private $kommentars;
    private array $kommentareListe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(maxSize: '2048k', mimeTypes: ['image/jpg', 'image/jpeg'],)]
    private ?string $Bild_1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(maxSize: '2048k', mimeTypes: ['image/jpg', 'image/jpeg'],)]
    private ?string $Bild_2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(maxSize: '2048k', mimeTypes: ['image/jpg', 'image/jpeg'],)]
    private ?string $Bild_3;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(maxSize: '2048k', mimeTypes: ['image/jpg', 'image/jpeg'],)]
    private ?string $Bild_4;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(maxSize: '2048k', mimeTypes: ['image/jpg', 'image/jpeg'],)]
    private ?string $Bild_5;

    #[ORM\OneToMany(mappedBy: 'auftrag', targetEntity: AuftragStatus::class, cascade: ["persist", "remove"])]
    private $AuftragStatuses;

    #[ORM\Column(nullable: true)]
    private ?bool $isArchiviert = null;

    public function __construct()
    {
        $this->kommentars = new ArrayCollection();
        $this->AuftragStatuses = new ArrayCollection();
    }

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

    public function getGrund(): ?Grund
    {
        return $this->Grund;
    }

    public function setGrund(?Grund $Grund): self
    {
        $this->Grund = $Grund;

        return $this;
    }

    public function getBeschreibung(): ?string
    {
        return $this->Beschreibung;
    }

    public function setBeschreibung(string $Beschreibung): self
    {
        $this->Beschreibung = $Beschreibung;

        return $this;
    }

    public function getMenge(): ?int
    {
        return $this->Menge;
    }

    public function setMenge(int $Menge): self
    {
        $this->Menge = $Menge;

        return $this;
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

    public function getArtikel(): ?Artikel
    {
        return $this->artikel;
    }

    public function setArtikel(?Artikel $artikel): self
    {
        $this->artikel = $artikel;

        return $this;
    }

    public function getRechnung(): ?Rechnung
    {
        return $this->Rechnung;
    }

    public function setRechnung(?Rechnung $Rechnung): self
    {
        $this->Rechnung = $Rechnung;

        return $this;
    }

    public function getDatum(): ?DateTimeInterface
    {
        return $this->Datum;
    }

    public function setDatum(DateTimeInterface $Datum): self
    {
        $this->Datum = $Datum;

        return $this;
    }

    public function getTerminFuerReparatur(): ?DateTimeInterface
    {
        return $this->TerminFuerReparatur;
    }

    public function setTerminFuerReparatur(?DateTimeInterface $Datum): self
    {
        $this->TerminFuerReparatur = $Datum;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getStatus(): ?Status
    {
        return $this->Status;
    }

    public function getAktuellerStatus(): ?Status
    {
        return $this->Status;
    }

    public function setStatus(?Status $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    /**
     * @return Collection<int, Kommentar>
     */
    public function getKommentars(): Collection
    {
        return $this->kommentars;
    }

    public function addKommentar(Kommentar $kommentar): self
    {
        if (!$this->kommentars->contains($kommentar)) {
            $this->kommentars[] = $kommentar;
            $kommentar->setAuftrag($this);
        }

        return $this;
    }

    public function removeKommentar(Kommentar $kommentar): self
    {
        if ($this->kommentars->removeElement($kommentar)) {
            // set the owning side to null (unless already changed)
            if ($kommentar->getAuftrag() === $this) {
                $kommentar->setAuftrag(null);
            }
        }

        return $this;
    }

    public function getkommentareListe(): Collection
    {
        return $this->getKommentars();
    }

    public function getBild1(): ?string
    {
        $filename = explode('/',$this->Bild_1);
        return end($filename);
    }

    public function setBild1(File $Bild_1 = null): self
    {
        $this->Bild_1 = $Bild_1;

        return $this;
    }

    public function getBild2(): ?string
    {
        $filename = explode('/',$this->Bild_2);
        return end($filename);
    }

    public function setBild2(File $Bild_2 = null): self
    {
        $this->Bild_2 = $Bild_2;

        return $this;
    }

    public function getBild3(): ?string
    {
        $filename = explode('/',$this->Bild_3);
        return end($filename);
    }

    public function setBild3(File $Bild_3 = null): self
    {
        $this->Bild_3 = $Bild_3;

        return $this;
    }

    public function getBild4(): ?string
    {
        $filename = explode('/',$this->Bild_4);
        return end($filename);
    }

    public function setBild4(File $Bild_4 = null): self
    {
        $this->Bild_4 = $Bild_4;

        return $this;
    }

    public function getBild5(): ?string
    {
        $filename = explode('/',$this->Bild_5);
        return end($filename);
    }

    public function setBild5(File $Bild_5 = null): self
    {
        $this->Bild_5 = $Bild_5;

        return $this;
    }

    /**
     * @return Collection<int, AuftragStatus>
     */
    public function getAuftragStatuses(): Collection
    {
        return $this->AuftragStatuses;
    }

    public function addAuftragStatus(AuftragStatus $auftragStatus): self
    {
        if (!$this->AuftragStatuses->contains($auftragStatus)) {
            $this->AuftragStatuses[] = $auftragStatus;
            $auftragStatus->setAuftrag($this);
        }

        return $this;
    }

    public function removeAuftragStatus(AuftragStatus $auftragStatus): self
    {
        if ($this->AuftragStatuses->removeElement($auftragStatus)) {
            // set the owning side to null (unless already changed)
            if ($auftragStatus->getAuftrag() === $this) {
                $auftragStatus->setAuftrag(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSendEmail(): bool
    {
        return $this->sendEmail;
    }

    /**
     * @param bool $sendEmail
     */
    public function setSendEmail(bool $sendEmail): void
    {
        $this->sendEmail = $sendEmail;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        for ($i=1;$i<6;$i++) {
            $metadata->addPropertyConstraint('Bild_'.$i, new Assert\File([
                'maxSize' => '1024k'
            ]));
        }
    }

    public function isIsArchiviert(): ?bool
    {
        return $this->isArchiviert;
    }

    public function setIsArchiviert(?bool $isArchiviert): self
    {
        $this->isArchiviert = $isArchiviert;

        return $this;
    }
}