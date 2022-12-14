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
    #[Groups(["registration:create", "registration:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups(["registration:create", "registration:read"])]
    private ?int $userId = null;

    private ?string $userLastName = null;

    private ?string $userFirstName = null;

    private ?string $userEmail = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournament $tournament = null;

    #[Groups(["registration:create", "registration:read"])]
    private ?int $tournamentId = null;

    private ?string $tournamentName = null;

    private ?string $tournamentCity = null;

    private ?\DateTimeInterface $tournamentStartDate = null;

    private ?\DateTimeInterface $tournamentEndDate = null;

    #[ORM\Column(length: 100)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $requestState = "pending";

    #[ORM\Column]
    #[Groups(["registration:create", "registration:read"])]
    private ?bool $hasParticipated = false;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?bool $participationSingle = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?bool $participationDouble = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?bool $participationMixed = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $singleStageReached = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $doubleStageReached = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $mixedStageReached = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $doublePartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $doublePartnerClub = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $mixedPartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $mixedPartnerClub = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(["registration:create", "registration:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["registration:create", "registration:read"])]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getRequestState(): ?string
    {
        return $this->requestState;
    }

    public function setRequestState(string $requestState): self
    {
        $this->requestState = $requestState;

        return $this;
    }

    public function isHasParticipated(): ?bool
    {
        return $this->hasParticipated;
    }

    public function setHasParticipated(bool $hasParticipated): self
    {
        $this->hasParticipated = $hasParticipated;

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

    public function getSingleStageReached(): ?string
    {
        return $this->singleStageReached;
    }

    public function setSingleStageReached(?string $singleStageReached): self
    {
        $this->singleStageReached = $singleStageReached;

        return $this;
    }

    public function getDoubleStageReached(): ?string
    {
        return $this->doubleStageReached;
    }

    public function setDoubleStageReached(?string $doubleStageReached): self
    {
        $this->doubleStageReached = $doubleStageReached;

        return $this;
    }

    public function getMixedStageReached(): ?string
    {
        return $this->mixedStageReached;
    }

    public function setMixedStageReached(?string $mixedStageReached): self
    {
        $this->mixedStageReached = $mixedStageReached;

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
}
