<?php

namespace App\Entity;

use App\Repository\TournamentRegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TournamentRegistrationRepository::class)]
class TournamentRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update"])]
    private ?User $user = null;

    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?int $userId = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $userLastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $userFirstName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $userEmail = null;

    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?bool $haveToCreateUser = false;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "result:read"])]
    private ?Tournament $tournament = null;

    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?int $tournamentId = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $tournamentName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $tournamentCity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?\DateTimeInterface $tournamentStartDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?\DateTimeInterface $tournamentEndDate = null;

    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?bool $haveToCreateTournament = false;

    #[ORM\Column(length: 100)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $requestState = "pending";

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?bool $participationSingle = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?bool $participationDouble = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?bool $participationMixed = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $doublePartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $doublePartnerClub = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $mixedPartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $mixedPartnerClub = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'TournamentRegistration', cascade: ['persist', 'remove'])]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read"])]
    private ?Result $result = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->result = new Result();
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserLastName(): ?string
    {
        return $this->userLastName;
    }

    public function setUserLastName($userLastName): self
    {
        $this->userLastName = $userLastName;
        return $this;
    }

    public function getUserFirstName(): ?string
    {
        return $this->userFirstName;
    }

    public function setUserFirstName($userFirstName): self
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail): self
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    public function getHaveToCreateUser(): ?bool
    {
        return  $this->haveToCreateUser;
    }

    public function setHaveToCreateUser($haveToCreateUser): self
    {
        $this->haveToCreateUser = $haveToCreateUser;
        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getTournamentId(): ?int
    {
        return $this->tournamentId;
    }

    public function setTournamentId($tournamentId): self
    {
        $this->tournamentId = $tournamentId;
        return $this;
    }

    public function getTournamentName(): ?string
    {
        return $this->tournamentName;
    }

    public function setTournamentName($tournamentName): self
    {
        $this->tournamentName = $tournamentName;
        return $this;
    }

    public function getTournamentCity(): ?string
    {
        return $this->tournamentCity;
    }

    public function setTournamentCity($tournamentCity): self
    {
        $this->tournamentCity = $tournamentCity;
        return $this;
    }

    public function getTournamentStartDate(): ?\DateTimeInterface
    {
        return $this->tournamentStartDate;
    }

    public function setTournamentStartDate($tournamentStartDate): self
    {
        $this->tournamentStartDate = $tournamentStartDate;
        return $this;
    }
    public function getTournamentEndDate(): ?\DateTimeInterface
    {
        return $this->tournamentEndDate;
    }

    public function setTournamentEndDate($tournamentEndDate): self
    {
        $this->tournamentEndDate = $tournamentEndDate;
        return $this;
    }

    public function getHaveToCreateTournament(): ?bool
    {
        return  $this->haveToCreateTournament;
    }

    public function setHaveToCreateTournament($haveToCreateTournament): self
    {
        $this->haveToCreateTournament = $haveToCreateTournament;
        return $this;
    }

    public function getRequestState(): ?string
    {
        return $this->requestState;
    }

    public function setRequestState(string $requestState): self
    {
        $this->requestState = $requestState;

        return $this;
    }

    public function isParticipationSingle(): ?bool
    {
        return $this->participationSingle;
    }

    public function setParticipationSingle(?bool $participationSingle): self
    {
        $this->participationSingle = $participationSingle;

        return $this;
    }

    public function isParticipationDouble(): ?bool
    {
        return $this->participationDouble;
    }

    public function setParticipationDouble(?bool $participationDouble): self
    {
        $this->participationDouble = $participationDouble;

        return $this;
    }

    public function isParticipationMixed(): ?bool
    {
        return $this->participationMixed;
    }

    public function setParticipationMixed(?bool $participationMixed): self
    {
        $this->participationMixed = $participationMixed;

        return $this;
    }

    public function getDoublePartnerName(): ?string
    {
        return $this->doublePartnerName;
    }

    public function setDoublePartnerName(?string $doublePartnerName): self
    {
        $this->doublePartnerName = $doublePartnerName;

        return $this;
    }

    public function getDoublePartnerClub(): ?string
    {
        return $this->doublePartnerClub;
    }

    public function setDoublePartnerClub(?string $doublePartnerClub): self
    {
        $this->doublePartnerClub = $doublePartnerClub;

        return $this;
    }

    public function getMixedPartnerName(): ?string
    {
        return $this->mixedPartnerName;
    }

    public function setMixedPartnerName(?string $mixedPartnerName): self
    {
        $this->mixedPartnerName = $mixedPartnerName;

        return $this;
    }

    public function getMixedPartnerClub(): ?string
    {
        return $this->mixedPartnerClub;
    }

    public function setMixedPartnerClub(?string $mixedPartnerClub): self
    {
        $this->mixedPartnerClub = $mixedPartnerClub;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        // unset the owning side of the relation if necessary
        if ($result === null && $this->result !== null) {
            $this->result->setTournamentRegistration(null);
        }

        // set the owning side of the relation if necessary
        if ($result !== null && $result->getTournamentRegistration() !== $this) {
            $result->setTournamentRegistration($this);
        }

        $this->result = $result;

        return $this;
    }
}
