<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $badge = null;


    #[ORM\Column(nullable: true)]
    private ?int $reviewsCount = null;


    #[ORM\Column(nullable: true)]
    private ?int $photosCount = null;


    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;


    #[ORM\Column]
    private ?int $rating = 5;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $visitDate = null;


    #[ORM\Column(length: 10, nullable: true)]
    private ?string $avatar = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product = null;


    #[ORM\Column]
    private bool $isVisible = true;


    #[ORM\Column]
    private int $position = 0;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }


    public function getBadge(): ?string
    {
        return $this->badge;
    }


    public function setBadge(?string $badge): static
    {
        $this->badge = $badge;
        return $this;
    }


    public function getReviewsCount(): ?int
    {
        return $this->reviewsCount;
    }


    public function setReviewsCount(?int $reviewsCount): static
    {
        $this->reviewsCount = $reviewsCount;
        return $this;
    }


    public function getPhotosCount(): ?int
    {
        return $this->photosCount;
    }


    public function setPhotosCount(?int $photosCount): static
    {
        $this->photosCount = $photosCount;
        return $this;
    }


    public function getText(): ?string
    {
        return $this->text;
    }


    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }


    public function getRating(): ?int
    {
        return $this->rating;
    }


    public function setRating(int $rating): static
    {
        $this->rating = $rating;
        return $this;
    }


    public function getDate(): ?string
    {
        return $this->date;
    }


    public function setDate(?string $date): static
    {
        $this->date = $date;
        return $this;
    }


    public function getVisitDate(): ?string
    {
        return $this->visitDate;
    }


    public function setVisitDate(?string $visitDate): static
    {
        $this->visitDate = $visitDate;
        return $this;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }


    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }


    public function getProduct(): ?string
    {
        return $this->product;
    }


    public function setProduct(?string $product): static
    {
        $this->product = $product;
        return $this;
    }


    public function isVisible(): bool
    {
        return $this->isVisible;
    }


    public function setIsVisible(bool $isVisible): static
    {
        $this->isVisible = $isVisible;
        return $this;
    }


    public function getPosition(): int
    {
        return $this->position;
    }


    public function setPosition(int $position): static
    {
        $this->position = $position;
        return $this;
    }
}