<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user:read", "user:create", "user:update")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    #[Assert\NotBlank()]
    #[Assert\Email(message: "L'email utilisé n'est pas valide")]
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
    public ?string $confirmPassword = null;

    #[Assert\Length(
        min: 6,
        minMessage: "Votre mot de passe doit comporter 6 caractères minimum",
    )]
    public ?string $previousPassword = null;

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

    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'avatarUrl')]
    private ?File $avatarFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $avatarUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $avatarName = null;

    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?bool $validatedAccount = false;

    #[ORM\Column(length: 50)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $state = "inactive";

    #[Timestampable(on: "create")]
    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Timestampable(on: "update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: TournamentRegistration::class, orphanRemoval: true)]
    private Collection $tournamentRegistrations;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tournamentRegistrations = new ArrayCollection();
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
        return (string) $this->email;
    }

    // /**
    //  * Méthode getUsername qui permet de retourner le champ qui est utilisé pour l'authentification.
    //  *
    //  * @return string
    //  */
    // public function getUsername(): string
    // {
    //     return $this->getUserIdentifier();
    // }

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

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $avatarFile
     */
    public function setImageFile(?File $avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): void
    {
        $this->avatarName = $avatarName;
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
            $tournamentRegistration->setUserId($this);
        }

        return $this;
    }

    public function removeTournamentRegistration(TournamentRegistration $tournamentRegistration): self
    {
        if ($this->tournamentRegistrations->removeElement($tournamentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRegistration->getUserId() === $this) {
                $tournamentRegistration->setUserId(null);
            }
        }

        return $this;
    }
}
