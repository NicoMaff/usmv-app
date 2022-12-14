<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["tournament:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["tournament:read"])]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThan(propertyPath: "endDate")]
    #[Groups(["tournament:read"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min: 9, max: 9)]
    #[Groups(["tournament:read"])]
    private ?string $season = null;

    #[ORM\Column]
    #[Groups(["tournament:read"])]
    private ?bool $isTeamCompetition = false;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $standardPrice1 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $standardPrice2 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $standardPrice3 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $elitePrice1 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $elitePrice2 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $elitePrice3 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $priceSingle = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $priceDouble = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["tournament:read"])]
    private ?int $priceMixed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual(propertyPath: "randomDraw")]
    #[Groups(["tournament:read"])]
    private ?\DateTimeInterface $registrationClosingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThan(propertyPath: "startDate")]
    #[Groups(["tournament:read"])]
    private ?\DateTimeInterface $randomDraw = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $emailContact = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $telContact = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $registrationMethod = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(["tournament:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $regulationFileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["tournament:read"])]
    private ?string $regulationFileUrl = null;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentRegistration::class)]
    private Collection $tournamentRegistrations;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tournamentRegistrations = new ArrayCollection();
        return $this;
    }

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

    public function isTeamCompetition(): ?bool
    {
        return $this->isTeamCompetition;
    }

    public function setIsTeamCompetition(bool $isTeamCompetition): self
    {
        $this->isTeamCompetition = $isTeamCompetition;

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

    public function getPriceSingle(): ?int
    {
        return $this->priceSingle;
    }

    public function setPriceSingle(?int $priceSingle): self
    {
        $this->priceSingle = $priceSingle;

        return $this;
    }

    public function getPriceDouble(): ?int
    {
        return $this->priceDouble;
    }

    public function setPriceDouble(?int $priceDouble): self
    {
        $this->priceDouble = $priceDouble;

        return $this;
    }

    public function getPriceMixed(): ?int
    {
        return $this->priceMixed;
    }

    public function setPriceMixed(?int $priceMixed): self
    {
        $this->priceMixed = $priceMixed;

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

    public function getRegulationFileName(): ?string
    {
        return $this->regulationFileName;
    }

    public function setRegulationFileName(?string $regulationFileName): self
    {
        $this->regulationFileName = $regulationFileName;
        return $this;
    }

    public function getRegulationFileUrl(): ?string
    {
        return $this->regulationFileUrl;
    }

    public function setRegulationFileUrl(?string $regulationFileUrl): self
    {
        $this->regulationFileUrl = $regulationFileUrl;

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
            $tournamentRegistration->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentRegistration(TournamentRegistration $tournamentRegistration): self
    {
        if ($this->tournamentRegistrations->removeElement($tournamentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRegistration->getTournament() === $this) {
                $tournamentRegistration->setTournament(null);
            }
        }

        return $this;
    }
}
