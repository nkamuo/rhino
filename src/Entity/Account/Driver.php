<?php

namespace App\Entity\Account;

use App\Entity\Document\DriverLicense;
use App\Repository\Account\DriverRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\OneToOne(inversedBy: 'driver', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userAccount = null;

    #[GQL\Field()]
    #[ORM\Column(
        options: [
            // 'default' => true
        ]
    )]
    private ?bool $verified = false;

    #[GQL\Field()]
    #[ORM\Column(length: 32, enumType: DriverStatus::class)]
    private ?DriverStatus $status = DriverStatus::PENDING;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?DriverAddress $address = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, nullable: true, enumType:Gender::class)]
    private ?Gender $gender = null;

    #[GQL\Field(type:'Date')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[GQL\Field()]
    #[ORM\OneToOne(inversedBy: 'driver', cascade: ['persist', 'remove'])]
    private ?DriverLicense $drivingLicense = null;

    #[GQL\Field()]
    #[ORM\Embedded(columnPrefix: 'review_')]
    private ?ReviewSummary $review = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    #[GQL\Field(name: 'user')]
    public function getUserAccount(): ?User
    {
        return $this->userAccount;
    }

    public function setUserAccount(User $userAccount): static
    {
        $this->userAccount = $userAccount;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function getStatus(): ?DriverStatus
    {
        return $this->status;
    }

    public function setStatus(DriverStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getAddress(): ?DriverAddress
    {
        return $this->address;
    }

    public function setAddress(?DriverAddress $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getDrivingLicense(): ?DriverLicense
    {
        return $this->drivingLicense;
    }

    public function setDrivingLicense(?DriverLicense $drivingLicense): static
    {
        $this->drivingLicense = $drivingLicense;

        return $this;
    }

    
    public function getReview(): ?ReviewSummary{
        return $this->review;
    }

    public function setReview(ReviewSummary $review): static{
        $this->review = $review;
        return $this;
    }
}
