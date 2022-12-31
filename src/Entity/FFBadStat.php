<?php

namespace App\Entity;

use App\Repository\FFBadStatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FFBadStatRepository::class)]
class FFBadStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'FFBadStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $extractionDate = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $rankingsDate = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $weekNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $season = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $apiId = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $license = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $birthDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $nationality = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $countryISO = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $categoryGlobal = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $categoryShort = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $categoryLong = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $clubReference = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $clubAcronym = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clubName = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $clubDepartment = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPlayerTransferred = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPlayerActive = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $feather = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $singleCPPH = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $singleRankNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $singleFrenchRankNumber = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $singleRankName = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $doubleCPPH = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $doubleRankNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $doubleFrenchRankNumber = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $doubleRankName = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $mixedCPPH = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $mixedRankNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $mixedFrenchRankNumber = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $mixedRankName = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $CPPHSum = null;

    public function __construct()
    {
        $this->extractionDate = new \DateTimeImmutable();
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

    public function getExtractionDate(): ?\DateTimeImmutable
    {
        return $this->extractionDate;
    }

    public function setExtractionDate(\DateTimeImmutable $extractionDate): self
    {
        $this->extractionDate = $extractionDate;

        return $this;
    }

    public function getRankingsDate(): ?string
    {
        return $this->rankingsDate;
    }

    public function setRankingsDate(?string $rankingsDate): self
    {
        $this->rankingsDate = $rankingsDate;

        return $this;
    }

    public function getWeekNumber(): ?string
    {
        return $this->weekNumber;
    }

    public function setWeekNumber(?string $weekNumber): self
    {
        $this->weekNumber = $weekNumber;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(?string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(?string $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryISO(): ?string
    {
        return $this->countryISO;
    }

    public function setCountryISO(?string $countryISO): self
    {
        $this->countryISO = $countryISO;

        return $this;
    }

    public function getCategoryGlobal(): ?string
    {
        return $this->categoryGlobal;
    }

    public function setCategoryGlobal(?string $categoryGlobal): self
    {
        $this->categoryGlobal = $categoryGlobal;

        return $this;
    }

    public function getCategoryShort(): ?string
    {
        return $this->categoryShort;
    }

    public function setCategoryShort(?string $categoryShort): self
    {
        $this->categoryShort = $categoryShort;

        return $this;
    }

    public function getCategoryLong(): ?string
    {
        return $this->categoryLong;
    }

    public function setCategoryLong(?string $categoryLong): self
    {
        $this->categoryLong = $categoryLong;

        return $this;
    }

    public function getClubReference(): ?string
    {
        return $this->clubReference;
    }

    public function setClubReference(?string $clubReference): self
    {
        $this->clubReference = $clubReference;

        return $this;
    }

    public function getClubAcronym(): ?string
    {
        return $this->clubAcronym;
    }

    public function setClubAcronym(?string $clubAcronym): self
    {
        $this->clubAcronym = $clubAcronym;

        return $this;
    }

    public function getClubName(): ?string
    {
        return $this->clubName;
    }

    public function setClubName(?string $clubName): self
    {
        $this->clubName = $clubName;

        return $this;
    }

    public function getClubDepartment(): ?string
    {
        return $this->clubDepartment;
    }

    public function setClubDepartment(?string $clubDepartment): self
    {
        $this->clubDepartment = $clubDepartment;

        return $this;
    }

    public function isIsPlayerTransferred(): ?bool
    {
        return $this->isPlayerTransferred;
    }

    public function setIsPlayerTransferred(?bool $isPlayerTransferred): self
    {
        $this->isPlayerTransferred = $isPlayerTransferred;

        return $this;
    }

    public function isIsPlayerActive(): ?bool
    {
        return $this->isPlayerActive;
    }

    public function setIsPlayerActive(?bool $isPlayerActive): self
    {
        $this->isPlayerActive = $isPlayerActive;

        return $this;
    }

    public function getFeather(): ?string
    {
        return $this->feather;
    }

    public function setFeather(?string $feather): self
    {
        $this->feather = $feather;

        return $this;
    }

    public function getSingleCPPH(): ?string
    {
        return $this->singleCPPH;
    }

    public function setSingleCPPH(?string $singleCPPH): self
    {
        $this->singleCPPH = $singleCPPH;

        return $this;
    }

    public function getSingleRankNumber(): ?string
    {
        return $this->singleRankNumber;
    }

    public function setSingleRankNumber(?string $singleRankNumber): self
    {
        $this->singleRankNumber = $singleRankNumber;

        return $this;
    }

    public function getSingleFrenchRankNumber(): ?string
    {
        return $this->singleFrenchRankNumber;
    }

    public function setSingleFrenchRankNumber(?string $singleFrenchRankNumber): self
    {
        $this->singleFrenchRankNumber = $singleFrenchRankNumber;

        return $this;
    }

    public function getSingleRankName(): ?string
    {
        return $this->singleRankName;
    }

    public function setSingleRankName(?string $singleRankName): self
    {
        $this->singleRankName = $singleRankName;

        return $this;
    }

    public function getDoubleCPPH(): ?string
    {
        return $this->doubleCPPH;
    }

    public function setDoubleCPPH(?string $doubleCPPH): self
    {
        $this->doubleCPPH = $doubleCPPH;

        return $this;
    }

    public function getDoubleRankNumber(): ?string
    {
        return $this->doubleRankNumber;
    }

    public function setDoubleRankNumber(?string $doubleRankNumber): self
    {
        $this->doubleRankNumber = $doubleRankNumber;

        return $this;
    }

    public function getDoubleFrenchRankNumber(): ?string
    {
        return $this->doubleFrenchRankNumber;
    }

    public function setDoubleFrenchRankNumber(?string $doubleFrenchRankNumber): self
    {
        $this->doubleFrenchRankNumber = $doubleFrenchRankNumber;

        return $this;
    }

    public function getDoubleRankName(): ?string
    {
        return $this->doubleRankName;
    }

    public function setDoubleRankName(?string $doubleRankName): self
    {
        $this->doubleRankName = $doubleRankName;

        return $this;
    }

    public function getMixedCPPH(): ?string
    {
        return $this->mixedCPPH;
    }

    public function setMixedCPPH(?string $mixedCPPH): self
    {
        $this->mixedCPPH = $mixedCPPH;

        return $this;
    }

    public function getMixedRankNumber(): ?string
    {
        return $this->mixedRankNumber;
    }

    public function setMixedRankNumber(?string $mixedRankNumber): self
    {
        $this->mixedRankNumber = $mixedRankNumber;

        return $this;
    }

    public function getMixedFrenchRankNumber(): ?string
    {
        return $this->mixedFrenchRankNumber;
    }

    public function setMixedFrenchRankNumber(?string $mixedFrenchRankNumber): self
    {
        $this->mixedFrenchRankNumber = $mixedFrenchRankNumber;

        return $this;
    }

    public function getMixedRankName(): ?string
    {
        return $this->mixedRankName;
    }

    public function setMixedRankName(string $mixedRankName): self
    {
        $this->mixedRankName = $mixedRankName;

        return $this;
    }

    public function getCPPHSum(): ?string
    {
        return $this->CPPHSum;
    }

    public function setCPPHSum(?string $CPPHSum): self
    {
        $this->CPPHSum = $CPPHSum;

        return $this;
    }
}
