<?php

namespace App\Entity;

use App\Repository\RechnungRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Schema\Index;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: RechnungRepository::class)]
class Rechnung
{
    #[ORM\Column(type: 'integer')]
    public ?int $id;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    public ?string $Rechnungsnummer;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $BestellNummer;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $Anrede;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $Vorname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Nachname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Strasse;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $PLZ;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Ort;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Land;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Tel;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $Email;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $KdNr;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $Webshop;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $Plattform;

    #[ORM\OneToMany(mappedBy: 'Rechnung', targetEntity: Artikel::class, cascade: ["persist", "remove", "merge"], orphanRemoval: true, indexBy: 'Rechnung')]
    public $artikels;

    #[ORM\OneToMany(mappedBy: 'Rechnung', targetEntity: Auftrag::class, cascade: ["persist", "remove", "merge"], orphanRemoval: true, indexBy: 'Rechnung')]
    private $auftrags;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->artikels = new ArrayCollection();
        $this->auftrags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRechnungsnummer(): ?string
    {
        return $this->Rechnungsnummer;
    }

    public function setRechnungsnummer(string $Rechnungsnummer): self
    {
        $this->Rechnungsnummer = $Rechnungsnummer;

        return $this;
    }

    public function getBestellNummer(): ?string
    {
        return $this->BestellNummer;
    }

    public function setBestellNummer(string $BestellNummer): self
    {
        $this->BestellNummer = $BestellNummer;

        return $this;
    }

    public function getAnrede(): ?string
    {
        return $this->Anrede;
    }

    public function setAnrede(?string $Anrede): self
    {
        $this->Anrede = $Anrede;

        return $this;
    }

    public function getVorname(): ?string
    {
        return $this->Vorname;
    }

    public function setVorname(?string $Vorname): self
    {
        $this->Vorname = $Vorname;

        return $this;
    }

    public function getNachname(): ?string
    {
        return $this->Nachname;
    }

    public function setNachname(?string $Nachname): self
    {
        $this->Nachname = $Nachname;

        return $this;
    }

    public function getStrasse(): ?string
    {
        return $this->Strasse;
    }

    public function setStrasse(?string $Strasse): self
    {
        $this->Strasse = $Strasse;

        return $this;
    }

    public function getPLZ(): ?string
    {
        return $this->PLZ;
    }

    public function setPLZ(?string $PLZ): self
    {
        $this->PLZ = $PLZ;

        return $this;
    }

    public function getOrt(): ?string
    {
        return $this->Ort;
    }

    public function setOrt(?string $Ort): self
    {
        $this->Ort = $Ort;

        return $this;
    }

    public function getLand(): ?string
    {
        return $this->Land;
    }

    public function setLand(string $Land): self
    {
        $this->Land = $Land;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(?string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getKdNr(): ?string
    {
        return $this->KdNr;
    }

    public function setKdNr(?string $KdNr): self
    {
        $this->KdNr = $KdNr;

        return $this;
    }

    public function getWebshop(): ?string
    {
        return $this->Webshop;
    }

    public function setWebshop(?string $Webshop): self
    {
        $this->Webshop = $Webshop;

        return $this;
    }

    public function getPlattform(): ?string
    {
        return $this->Plattform;
    }

    public function setPlattform(string $Plattform): self
    {
        $this->Plattform = $Plattform;

        return $this;
    }

    /**
     * @return Collection<int, Artikel>
     */
    public function getArtikels(): Collection
    {
        return $this->artikels;
    }

    public function addArtikel(Artikel $artikel): self
    {
        if (!$this->artikels->contains($artikel)) {
            $this->artikels[] = $artikel;
            $artikel->setRechnung($this);
        }

        return $this;
    }

    public function removeArtikel(Artikel $artikel): self
    {
        if ($this->artikels->removeElement($artikel)) {
            // set the owning side to null (unless already changed)
            if ($artikel->getRechnung() === $this) {
                $artikel->setRechnung(null);
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

    public function addAuftrag(Auftrag $auftrag): self
    {
        if (!$this->auftrags->contains($auftrag)) {
            $this->auftrags[] = $auftrag;
            $auftrag->setRechnung($this);
        }

        return $this;
    }

    public function removeAuftrag(Auftrag $auftrag): self
    {
        if ($this->auftrags->removeElement($auftrag)) {
            // set the owning side to null (unless already changed)
            if ($auftrag->getRechnung() === $this) {
                $auftrag->setRechnung(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getRechnungsnummer();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
