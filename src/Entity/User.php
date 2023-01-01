<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user:read", "user:create", "user:update")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    #[Assert\NotBlank(message: "This email is already used")]
    #[Assert\Email(message: "This is not a valid email")]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private array $roles = ["ROLE_MEMBER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 6,
        minMessage: "Votre mot de passe doit comporter 6 caractères minimum",
    )]
    #[Assert\NotBlank(message: "Ce champs ne peut être vide !")]
    private ?string $password = null;

    #[Assert\EqualTo(
        propertyPath: "password",
        message: "Les deux mots de passe saisis ne sont pas identiques !"
    )]
    #[Assert\NotBlank(message: "Ce champs ne peut être vide !")]
    private ?string $confirmPassword = null;

    #[Assert\Length(
        min: 6,
        minMessage: "Votre mot de passe doit comporter 6 caractères minimum",
    )]
    private ?string $previousPassword = null;

    private ?bool $passwordAutoGenerated = false;

    #[ORM\Column(length: 100)]
    #[Groups(["user:read", "user:create", "user:update"])]
    #[Assert\NotBlank(message: "Ce champs ne peut être vide !")]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    #[Groups(["user:read", "user:create", "user:update"])]
    #[Assert\NotBlank(message: "Ce champs ne peut être vide !")]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups("user:read", "user:createByA", "user:update")]
    private ?string $gender = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $birthDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $avatarFileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $avatarFileUrl = null;

    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?bool $validatedAccount = false;

    #[ORM\Column(length: 50)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $state = "inactive";

    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TournamentRegistration::class, orphanRemoval: true)]
    private Collection $tournamentRegistrations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FFBadStat::class)]
    private Collection $FFBadStats;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tournamentRegistrations = new ArrayCollection();
        $this->FFBadStats = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        // return (string) $this->email;

        /**
         * Allow to store the user's id instead email in database for refresh_token instances
         */
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_MEMBER
        $roles[] = 'ROLE_MEMBER';

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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword($confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }
    public function getPreviousPassword(): ?string
    {
        return $this->previousPassword;
    }

    public function setPreviousPassword($previousPassword): self
    {
        $this->previousPassword = $previousPassword;
        return $this;
    }

    public function isPasswordAutoGenerated(): ?bool
    {
        return $this->passwordAutoGenerated;
    }

    public function setPasswordAutoGenerated($passwordAutoGenerated): self
    {
        $this->passwordAutoGenerated = $passwordAutoGenerated;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAvatarFileUrl(): ?string
    {
        return $this->avatarFileUrl;
    }

    public function setAvatarFileUrl(?string $avatarFileUrl): self
    {
        $this->avatarFileUrl = $avatarFileUrl;

        return $this;
    }

    public function getAvatarFileName(): ?string
    {
        return $this->avatarFileName;
    }

    public function setAvatarFileName(?string $avatarFileName): void
    {
        $this->avatarFileName = $avatarFileName;
    }

    public function isValidatedAccount(): ?bool
    {
        return $this->validatedAccount;
    }

    public function setValidatedAccount(bool $validatedAccount): self
    {
        $this->validatedAccount = $validatedAccount;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, TournamentRegistration>
     */
    public function getTournamentRegistrations(): Collection
    {
        return $this->tournamentRegistrations;
    }

    public function addTournamentRegistration(TournamentRegistration $tournamentRegistration): self
    {
        if (!$this->tournamentRegistrations->contains($tournamentRegistration)) {
            $this->tournamentRegistrations->add($tournamentRegistration);
            $tournamentRegistration->setUser($this);
        }

        return $this;
    }

    public function removeTournamentRegistration(TournamentRegistration $tournamentRegistration): self
    {
        if ($this->tournamentRegistrations->removeElement($tournamentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRegistration->getUser() === $this) {
                $tournamentRegistration->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FfbadStat>
     */
    public function getFFBadStats(): Collection
    {
        return $this->FFBadStats;
    }

    public function addFFBadStat(FfbadStat $fFBadStat): self
    {
        if (!$this->FFBadStats->contains($fFBadStat)) {
            $this->FFBadStats->add($fFBadStat);
            $fFBadStat->setUser($this);
        }

        return $this;
    }

    public function removeFFBadStat(FfbadStat $fFBadStat): self
    {
        if ($this->FFBadStats->removeElement($fFBadStat)) {
            // set the owning side to null (unless already changed)
            if ($fFBadStat->getUser() === $this) {
                $fFBadStat->setUser(null);
            }
        }

        return $this;
    }
}
