<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
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
    #[Assert\NotBlank()]
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
    private ?string $last_name = null;

    #[ORM\Column(length: 100)]
    #[Groups(["user:read", "user:create", "user:update"])]
    #[Assert\NotBlank(message: "Ce champs ne peut être vide !")]
    private ?string $first_name = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups("user:read", "user:createByA", "user:update")]
    private ?string $gender = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $birth_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $avatar_url = null;

    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?bool $validated_account = false;

    #[ORM\Column(length: 50)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?string $state = "inactive";

    #[Timestampable(on: "create")]
    #[ORM\Column]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeImmutable $created_at = null;

    #[Timestampable(on: "update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["user:read", "user:create", "user:update"])]
    private ?\DateTimeInterface $updated_at = null;


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
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

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
        return $this->birth_date;
    }

    public function setBirthDate(?string $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function setAvatarUrl(?string $avatar_url): self
    {
        $this->avatar_url = $avatar_url;

        return $this;
    }

    public function isValidatedAccount(): ?bool
    {
        return $this->validated_account;
    }

    public function setValidatedAccount(bool $validated_account): self
    {
        $this->validated_account = $validated_account;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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
}
