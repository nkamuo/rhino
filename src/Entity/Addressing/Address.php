<?php

namespace App\Entity\Addressing;

use App\Repository\Addressing\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\InheritanceType("JOINED")]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{

    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $firstName = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $lastName = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phoneNumber = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $emailAddress = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $company = null;

    #[GQL\Field()]
    #[ORM\Column(length: 3)]
    private ?string $countryCode = null;

    #[GQL\Field()]
    #[ORM\Column(length: 16, nullable: true)]
    private ?string $provinceCode = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $provinceName = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $city = null;

    #[GQL\Field()]
    #[ORM\Column(length: 128, nullable: true)]
    private ?string $street = null;

    #[GQL\Field()]
    #[ORM\Column(length: 8, nullable: true)]
    private ?string $postcode = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coordinate $coordinate = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $formatted = null;


    public function __construct(?Ulid $id = null)
    {
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

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

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(?string $provinceCode): static
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    public function getProvinceName(): ?string
    {
        return $this->provinceName;
    }

    public function setProvinceName(?string $provinceName): static
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): static
    {
        $this->postcode = $postcode;

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

    public function getCoordinate(): ?Coordinate
    {
        return $this->coordinate;
    }

    public function setCoordinate(Coordinate $coordinate): static
    {
        $this->coordinate = $coordinate;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFormatted(): ?string
    {
        return $this->formatted;
    }

    public function setFormatted(?string $formatted): static
    {
        $this->formatted = $formatted;

        return $this;
    }
}
