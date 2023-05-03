<?php

namespace App\Entity;

use App\Repository\WarenEingangRepository;
use App\Service\CreateFolder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: WarenEingangRepository::class)]
class WarenEingang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $EingangDatum = null;

    #[ORM\Column(length: 255)]
    private ?string $Bestellnummer = null;

    #[ORM\Column(length: 255)]
    private ?string $Rechnungsnummer = null;

    #[ORM\Column(length: 255)]
    private ?string $LieferantName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $VollstaendingGeliefert = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $FehlendeArtikel = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $SonstigesKommentarWare = null;

    #[ORM\Column(nullable: true)]
    private ?bool $VollBezahlt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $SonstigesKommentarFinanzen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Freigabe = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ImBestandGebucht = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $VonWenGebucht = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $RechnungsDatei = null;

    /**
     * @Vich\UploadableField(mapping="rechnungen", fileNameProperty="RechnungsDatei")
     * @var File
     */
    private File $imageFile;

    #[ORM\Column(length: 255)]
    private ?string $WarenTyp = null;

    #[ORM\Column(nullable: true)]
    private ?bool $PreiseAngepasst = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEingangDatum(): ?\DateTimeInterface
    {
        return $this->EingangDatum;
    }

    public function setEingangDatum(\DateTimeInterface $EingangDatum): self
    {
        $this->EingangDatum = $EingangDatum;

        return $this;
    }

    public function getBestellnummer(): ?string
    {
        return $this->Bestellnummer;
    }

    public function setBestellnummer(string $Bestellnummer): self
    {
        $this->Bestellnummer = $Bestellnummer;

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

    public function getLieferantName(): ?string
    {
        return $this->LieferantName;
    }

    public function setLieferantName(string $LieferantName): self
    {
        $this->LieferantName = $LieferantName;

        return $this;
    }

    public function isVollstaendingGeliefert(): ?bool
    {
        return $this->VollstaendingGeliefert;
    }

    public function setVollstaendingGeliefert(?bool $VollstaendingGeliefert): self
    {
        $this->VollstaendingGeliefert = $VollstaendingGeliefert;

        return $this;
    }

    public function getFehlendeArtikel(): ?string
    {
        return $this->FehlendeArtikel;
    }

    public function setFehlendeArtikel(?string $FehlendeArtikel): self
    {
        $this->FehlendeArtikel = $FehlendeArtikel;

        return $this;
    }

    public function getSonstigesKommentarWare(): ?string
    {
        return $this->SonstigesKommentarWare;
    }

    public function setSonstigesKommentarWare(?string $SonstigesKommentarWare): self
    {
        $this->SonstigesKommentarWare = $SonstigesKommentarWare;

        return $this;
    }

    public function isVollBezahlt(): ?bool
    {
        return $this->VollBezahlt;
    }

    public function setVollBezahlt(?bool $VollBezahlt): self
    {
        $this->VollBezahlt = $VollBezahlt;

        return $this;
    }

    public function getSonstigesKommentarFinanzen(): ?string
    {
        return $this->SonstigesKommentarFinanzen;
    }

    public function setSonstigesKommentarFinanzen(?string $SonstigesKommentarFinanzen): self
    {
        $this->SonstigesKommentarFinanzen = $SonstigesKommentarFinanzen;

        return $this;
    }

    public function getFreigabe(): ?string
    {
        return $this->Freigabe;
    }

    public function setFreigabe(?string $Freigabe): self
    {
        $this->Freigabe = $Freigabe;

        return $this;
    }

    public function isImBestandGebucht(): ?bool
    {
        return $this->ImBestandGebucht;
    }

    public function setImBestandGebucht(?bool $ImBestandGebucht): self
    {
        $this->ImBestandGebucht = $ImBestandGebucht;

        return $this;
    }

    public function getVonWenGebucht(): ?string
    {
        return $this->VonWenGebucht;
    }

    public function setVonWenGebucht(?string $VonWenGebucht): self
    {
        $this->VonWenGebucht = $VonWenGebucht;

        return $this;
    }

    public function getRechnungsDatei(): ?string
    {
        return $this->RechnungsDatei;
    }

    public function setRechnungsDatei(?string $RechnungsDatei): self
    {
        $this->RechnungsDatei = $RechnungsDatei;

        return $this;
    }

    public function setImageFile(File $image = null): void
    {
        mkdir('/'.date('Y-m-d'),777,true);
        $this->imageFile = $image;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function getWarenTyp(): ?string
    {
        return $this->WarenTyp;
    }

    public function setWarenTyp(string $WarenTyp): self
    {
        $this->WarenTyp = $WarenTyp;

        return $this;
    }

    public function isPreiseAngepasst(): ?bool
    {
        return $this->PreiseAngepasst;
    }

    public function setPreiseAngepasst(?bool $PreiseAngepasst): self
    {
        $this->PreiseAngepasst = $PreiseAngepasst;

        return $this;
    }
}
