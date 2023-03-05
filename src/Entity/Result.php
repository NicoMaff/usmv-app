<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'result', cascade: ['persist', 'remove'])]
    // #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?TournamentRegistration $TournamentRegistration = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?string $singleStageReached = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?string $doubleStageReached = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?string $mixedStageReached = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?bool $areResultsValidated = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["registration:create", "registration:read", "registration:update", "user:create", "user:read", "user:update", "tournament:read", "result:read", "result:update"])]
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

    public function getTournamentRegistration(): ?TournamentRegistration
    {
        return $this->TournamentRegistration;
    }

    public function setTournamentRegistration(?TournamentRegistration $TournamentRegistration): self
    {
        $this->TournamentRegistration = $TournamentRegistration;

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

    public function getAreResultsValidated(): ?bool
    {
        return $this->areResultsValidated;
    }

    public function setAreResultsValidated(?bool $areResultsValidated): self
    {
        $this->areResultsValidated = $areResultsValidated;
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
