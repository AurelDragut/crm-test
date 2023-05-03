<?php

namespace App\Entity;

use App\Repository\BenutzerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BenutzerRepository::class)]
#[ORM\Table(name: 'benutzer')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Benutzer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'string')]
    private ?string $name;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(nullable: true)]
    private ?string $avatar;

    #[ORM\OneToMany(mappedBy: 'Mitarbeiter', targetEntity: Auftrag::class)]
    private $auftrags;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $KdNr;

    #[ORM\OneToMany(mappedBy: 'Verfasser', targetEntity: Kommentar::class)]
    private $kommentars;

    #[ORM\OneToMany(mappedBy: 'Mitarbeiter', targetEntity: AuftragStatus::class)]
    private $auftragStatuses;

    #[ORM\OneToMany(mappedBy: 'BearbeitetVon', targetEntity: AnfrageAngebote::class)]
    private Collection $AnfrageAngeboten;

    public function __construct()
    {
        $this->auftrags = new ArrayCollection();
        $this->kommentars = new ArrayCollection();
        $this->auftragStatuses = new ArrayCollection();
        $this->AnfrageAngeboten = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $auftrag->setMitarbeiter($this);
        }

        return $this;
    }

    public function removeAuftrag(Auftrag $auftrag): self
    {
        if ($this->auftrags->removeElement($auftrag)) {
            // set the owning side to null (unless already changed)
            if ($auftrag->getMitarbeiter() === $this) {
                $auftrag->setMitarbeiter(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->getName();
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ?? 'no-avatar-350x350-300x300.jpg';
    }
    public function getAvatarUrl(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        if (str_contains($this->avatar, '/')) {
            return $this->avatar;
        }
        return sprintf('/uploads/avatars/%s', $this->avatar);
    }
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
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
            $kommentar->setVerfasser($this);
        }

        return $this;
    }

    public function removeKommentar(Kommentar $kommentar): self
    {
        if ($this->kommentars->removeElement($kommentar)) {
            // set the owning side to null (unless already changed)
            if ($kommentar->getVerfasser() === $this) {
                $kommentar->setVerfasser(null);
            }
        }

        return $this;
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
            $auftragStatus->setMitarbeiter($this);
        }

        return $this;
    }

    public function removeAuftragStatus(AuftragStatus $auftragStatus): self
    {
        if ($this->auftragStatuses->removeElement($auftragStatus)) {
            // set the owning side to null (unless already changed)
            if ($auftragStatus->getMitarbeiter() === $this) {
                $auftragStatus->setMitarbeiter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnfrageAngebote>
     */
    public function getAnfrageAngeboten(): Collection
    {
        return $this->AnfrageAngeboten;
    }

    public function addAnfrageAngeboten(AnfrageAngebote $anfrageAngeboten): self
    {
        if (!$this->AnfrageAngeboten->contains($anfrageAngeboten)) {
            $this->AnfrageAngeboten->add($anfrageAngeboten);
            $anfrageAngeboten->setBearbeitetVon($this);
        }

        return $this;
    }

    public function removeAnfrageAngeboten(AnfrageAngebote $anfrageAngeboten): self
    {
        if ($this->AnfrageAngeboten->removeElement($anfrageAngeboten)) {
            // set the owning side to null (unless already changed)
            if ($anfrageAngeboten->getBearbeitetVon() === $this) {
                $anfrageAngeboten->setBearbeitetVon(null);
            }
        }

        return $this;
    }
}
