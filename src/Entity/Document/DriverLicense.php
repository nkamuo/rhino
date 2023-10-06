<?php

namespace App\Entity\Document;

use App\Entity\Account\Driver;
use App\Repository\Document\DriverLicenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: DriverLicenseRepository::class)]
class DriverLicense
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $licenseNumber = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, nullable: true)]
    private ?string $issuanceState = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, nullable: true)]
    private ?string $class = null;

    #[GQL\Field(type: 'Date')]
    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\Column(length: 128)]
    private ?string $fullName = null;

    #[GQL\Field(type: 'Date')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $issueDate = null;

    #[GQL\Field(type: 'Date')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[GQL\Field()]
    #[ORM\OneToOne(mappedBy: 'drivingLicense', cascade: ['persist', 'remove'])]
    private ?Driver $driver = null;

    #[GQL\Field()]
    #[ORM\Column(length: 2)]
    private ?string $countryCode = null;


    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): static
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getIssuanceState(): ?string
    {
        return $this->issuanceState;
    }

    public function setIssuanceState(?string $issuanceState): static
    {
        $this->issuanceState = $issuanceState;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

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

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(?\DateTimeInterface $issueDate): static
    {
        $this->issueDate = $issueDate;

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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        // unset the owning side of the relation if necessary
        if ($driver === null && $this->driver !== null) {
            $this->driver->setDrivingLicense(null);
        }

        // set the owning side of the relation if necessary
        if ($driver !== null && $driver->getDrivingLicense() !== $this) {
            $driver->setDrivingLicense($this);
        }

        $this->driver = $driver;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
