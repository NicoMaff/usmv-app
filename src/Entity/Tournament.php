<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 100)]
    private ?string $season = null;

    #[ORM\Column(nullable: true)]
    private ?int $standardPrice1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $standardPrice2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $standardPrice3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $elitePrice1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $elitePrice2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $elitePrice3 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationClosingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $randomDraw = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailContact = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $telContact = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $registrationMethod = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getStandardPrice1(): ?int
    {
        return $this->standardPrice1;
    }

    public function setStandardPrice1(?int $standardPrice1): self
    {
        $this->standardPrice1 = $standardPrice1;

        return $this;
    }

    public function getStandardPrice2(): ?int
    {
        return $this->standardPrice2;
    }

    public function setStandardPrice2(?int $standardPrice2): self
    {
        $this->standardPrice2 = $standardPrice2;

        return $this;
    }

    public function getStandardPrice3(): ?int
    {
        return $this->standardPrice3;
    }

    public function setStandardPrice3(?int $standardPrice3): self
    {
        $this->standardPrice3 = $standardPrice3;

        return $this;
    }

    public function getElitePrice1(): ?int
    {
        return $this->elitePrice1;
    }

    public function setElitePrice1(?int $elitePrice1): self
    {
        $this->elitePrice1 = $elitePrice1;

        return $this;
    }

    public function getElitePrice2(): ?int
    {
        return $this->elitePrice2;
    }

    public function setElitePrice2(?int $elitePrice2): self
    {
        $this->elitePrice2 = $elitePrice2;

        return $this;
    }

    public function getElitePrice3(): ?int
    {
        return $this->elitePrice3;
    }

    public function setElitePrice3(?int $elitePrice3): self
    {
        $this->elitePrice3 = $elitePrice3;

        return $this;
    }

    public function getRegistrationClosingDate(): ?\DateTimeInterface
    {
        return $this->registrationClosingDate;
    }

    public function setRegistrationClosingDate(?\DateTimeInterface $registrationClosingDate): self
    {
        $this->registrationClosingDate = $registrationClosingDate;

        return $this;
    }

    public function getRandomDraw(): ?\DateTimeInterface
    {
        return $this->randomDraw;
    }

    public function setRandomDraw(?\DateTimeInterface $randomDraw): self
    {
        $this->randomDraw = $randomDraw;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->emailContact;
    }

    public function setEmailContact(?string $emailContact): self
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    public function getTelContact(): ?string
    {
        return $this->telContact;
    }

    public function setTelContact(?string $telContact): self
    {
        $this->telContact = $telContact;

        return $this;
    }

    public function getRegistrationMethod(): ?string
    {
        return $this->registrationMethod;
    }

    public function setRegistrationMethod(?string $registrationMethod): self
    {
        $this->registrationMethod = $registrationMethod;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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
