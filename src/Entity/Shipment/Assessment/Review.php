<?php

namespace App\Entity\Shipment\Assessment;

use App\Entity\Account\User;
use App\Entity\Shipment\ShipmentOrder;
use App\Repository\Shipment\Assessment\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type(name:'ShipmentAssessment')]
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
    #[ORM\OneToMany(mappedBy: 'review', targetEntity: UnitReview::class, cascade:['persist','remove'], orphanRemoval: true)]
    private Collection $unitReviews;

    #[GQL\Field(type:'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reviewer = null;
    
    #[GQL\Field()]
    #[ORM\OneToOne(mappedBy: 'review')]
    private ?ShipmentOrder $shipmentOrder = null;

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
        $total = 0;
        $count = 0;
        foreach ($this->getUnitReviews() as $unitReview) {
            $total += $unitReview->getRating() ?? 0;
            $count++;
        }
        if($count === 0){
            $rating = 0;
        }
        else{
            $rating = ($total/$count);
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

    public function getReviewer(): ?User
    {
        return $this->reviewer;
    }

    public function setReviewer(?User $reviewer): static
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    
    public function getShipmentOrder(): ?ShipmentOrder
    {
        return $this->shipmentOrder;
    }

    public function setShipmentOrder(ShipmentOrder $shipmentOrder): static
    {
        // set the owning side of the relation if necessary
        if ($shipmentOrder->getReview() !== $this) {
            $shipmentOrder->setReview($this);
        }

        $this->shipmentOrder = $shipmentOrder;

        return $this;
    }
}
