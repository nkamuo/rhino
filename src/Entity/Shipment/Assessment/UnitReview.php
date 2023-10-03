<?php

namespace App\Entity\Shipment\Assessment;

use App\Repository\Shipment\Assessment\UnitReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: UnitReviewRepository::class)]
class UnitReview
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rating = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $type = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'unitReviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Review $review = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssessmentParameter $paramter = null;

    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getReview(): ?Review
    {
        return $this->review;
    }

    public function setReview(?Review $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getParamter(): ?AssessmentParameter
    {
        return $this->paramter;
    }

    public function setParamter(?AssessmentParameter $paramter): static
    {
        $this->paramter = $paramter;

        return $this;
    }
}
