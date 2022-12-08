<?php

namespace App\Entity;

use App\Repository\TournamentRegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRegistrationRepository::class)]
class TournamentRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournament $tournamentId = null;

    #[ORM\Column(length: 100)]
    private ?string $requestState = null;

    #[ORM\Column]
    private ?bool $hasParticipated = null;

    #[ORM\Column(nullable: true)]
    private ?bool $participationSingle = null;

    #[ORM\Column(nullable: true)]
    private ?bool $participationDouble = null;

    #[ORM\Column(nullable: true)]
    private ?bool $participationMixed = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $singleStageReached = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $doubleStageReached = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $mixedStageReached = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $doublePartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $doublePartnerClub = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mixedPartnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mixedPartnerClub = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTournamentId(): ?Tournament
    {
        return $this->tournamentId;
    }

    public function setTournamentId(?Tournament $tournamentId): self
    {
        $this->tournamentId = $tournamentId;

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
}
