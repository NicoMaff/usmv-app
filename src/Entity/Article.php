<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_image_main = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_image_additional_1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_image_additional_2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_image_additional_3 = null;

    // #[Timestampable(on: "create")]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Timestampable(on: "update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getUrlImageMain(): ?string
    {
        return $this->url_image_main;
    }

    public function setUrlImageMain(?string $url_image_main): self
    {
        $this->url_image_main = $url_image_main;

        return $this;
    }

    public function getUrlImageAdditional1(): ?string
    {
        return $this->url_image_additional_1;
    }

    public function setUrlImageAdditional1(?string $url_image_additional_1): self
    {
        $this->url_image_additional_1 = $url_image_additional_1;

        return $this;
    }

    public function getUrlImageAdditional2(): ?string
    {
        return $this->url_image_additional_2;
    }

    public function setUrlImageAdditional2(?string $url_image_additional_2): self
    {
        $this->url_image_additional_2 = $url_image_additional_2;

        return $this;
    }

    public function getUrlImageAdditional3(): ?string
    {
        return $this->url_image_additional_3;
    }

    public function setUrlImageAdditional3(?string $url_image_additional_3): self
    {
        $this->url_image_additional_3 = $url_image_additional_3;

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
