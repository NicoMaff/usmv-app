<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("article:read", "article:write")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups("article:read", "article:write", "article:update")]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        // max: 100,
        minMessage: "Le titre doit comporter plus de 5 caractères",
        maxMessage: "Le titre ne doit pas comporter plus de 5 caractères"
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("article:read", "article:write", "article:update")]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $mainImageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $mainImageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $firstAdditionalImageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $firstAdditionalImageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $secondAdditionalImageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $secondAdditionalImageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $thirdAdditionalImageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?string $thirdAdditionalImageUrl = null;

    #[ORM\Column(nullable: true)]
    #[Groups("article:read", "article:write", "article:update")]
    private ?bool $visible = null;

    #[ORM\Column]
    #[Groups("article:read", "article:write")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups("article:read", "article:write")]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMainImageName(): ?string
    {
        return $this->mainImageName;
    }

    public function setMainImageName(?string $mainImageName): self
    {
        $this->mainImageName = $mainImageName;

        return $this;
    }

    public function getMainImageUrl(): ?string
    {
        return $this->mainImageUrl;
    }

    public function setMainImageUrl(?string $mainImageUrl): self
    {
        $this->mainImageUrl = $mainImageUrl;

        return $this;
    }

    public function getFirstAdditionalImageName(): ?string
    {
        return $this->firstAdditionalImageName;
    }

    public function setFirstAdditionalImageName(?string $firstAdditionalImageName): self
    {
        $this->firstAdditionalImageName = $firstAdditionalImageName;

        return $this;
    }

    public function getFirstAdditionalImageUrl(): ?string
    {
        return $this->firstAdditionalImageUrl;
    }

    public function setFirstAdditionalImageUrl(?string $firstAdditionalImageUrl): self
    {
        $this->firstAdditionalImageUrl = $firstAdditionalImageUrl;

        return $this;
    }

    public function getSecondAdditionalImageName(): ?string
    {
        return $this->secondAdditionalImageName;
    }

    public function setSecondAdditionalImageName(?string $secondAdditionalImageName): self
    {
        $this->secondAdditionalImageName = $secondAdditionalImageName;

        return $this;
    }

    public function getSecondAdditionalImageUrl(): ?string
    {
        return $this->secondAdditionalImageUrl;
    }

    public function setSecondAdditionalImageUrl(?string $secondAdditionalImageUrl): self
    {
        $this->secondAdditionalImageUrl = $secondAdditionalImageUrl;

        return $this;
    }

    public function getThirdAdditionalImageName(): ?string
    {
        return $this->thirdAdditionalImageName;
    }

    public function setThirdAdditionalImageName(?string $thirdAdditionalImageName): self
    {
        $this->thirdAdditionalImageName = $thirdAdditionalImageName;

        return $this;
    }

    public function getThirdAdditionalImageUrl(): ?string
    {
        return $this->thirdAdditionalImageUrl;
    }

    public function setThirdAdditionalImageUrl(?string $thirdAdditionalImageUrl): self
    {
        $this->thirdAdditionalImageUrl = $thirdAdditionalImageUrl;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible($visible): self
    {
        $this->visible = $visible;
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
