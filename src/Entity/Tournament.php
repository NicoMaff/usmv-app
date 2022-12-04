<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[Vich\Uploadable]
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
    #[Assert\LessThan(propertyPath: "endDate")]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min: 9, max: 9)]
    private ?string $season = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $standardPrice1 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $standardPrice2 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $standardPrice3 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $elitePrice1 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $elitePrice2 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $elitePrice3 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $priceSingle = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $priceDouble = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $priceMixed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual(propertyPath: "randomDraw")]
    private ?\DateTimeInterface $registrationClosingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThan(propertyPath: "startDate")]
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

    #[Vich\UploadableField(mapping: 'tournaments', fileNameProperty: 'regulationUrl')]
    private ?File $regulationFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regulationUrl = null;

    public function __construct()
    {
        return $this->createdAt = new \DateTimeImmutable();
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

    public function getRegulationFile(): ?File
    {
        return $this->regulationFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setRegulationFile(?File $regulationFile = null): void
    {
        $this->regulationFile = $regulationFile;

        if (null !== $regulationFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getRegulationUrl(): ?string
    {
        return $this->regulationUrl;
    }

    public function setRegulationUrl(?string $regulationUrl): self
    {
        $this->regulationUrl = $regulationUrl;

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
