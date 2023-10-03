<?php

namespace App\Entity\Shipment\Assessment;

use App\Repository\Shipment\Assessment\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
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

    #[GQL\Field(type:'[UnitReview!]!')]
    #[ORM\OneToMany(mappedBy: 'review', targetEntity: UnitReview::class, orphanRemoval: true)]
    private Collection $unitReviews;

    #[GQL\Field(type:'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->unitReviews = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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


    public function calculateRating()
    {
        $rating = 0;
        foreach ($this->getUnitReviews() as $unitReview) {
            $rating += $unitReview->getRating() ?? 0;
        }
        $this->rating = $rating;
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

    /**
     * @return Collection<int, UnitReview>
     */
    public function getUnitReviews(): Collection
    {
        return $this->unitReviews;
    }

    public function addUnitReview(UnitReview $unitReview): static
    {
        if (!$this->unitReviews->contains($unitReview)) {
            $this->unitReviews->add($unitReview);
            $unitReview->setReview($this);
        }
        $this->calculateRating();

        return $this;
    }

    public function removeUnitReview(UnitReview $unitReview): static
    {
        if ($this->unitReviews->removeElement($unitReview)) {
            // set the owning side to null (unless already changed)
            if ($unitReview->getReview() === $this) {
                $unitReview->setReview(null);
            }
        }
        $this->calculateRating();

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
