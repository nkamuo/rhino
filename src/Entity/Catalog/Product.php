<?php

namespace App\Entity\Catalog;

use App\Repository\Catalog\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use GraphQL\Type\Definition\Type;

#[GQL\TypeInterface(resolveType:'value.resolveGQLType(typeResolver)')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\InheritanceType("JOINED")]
abstract class Product
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ProductPrice $price = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ProductDimension $dimension = null;

    #[GQL\Field()]
    #[ORM\Column(options:['default' => 0])]
    private ?int $weight = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductCategory $category = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getPrice(): ?ProductPrice
    {
        return $this->price;
    }

    public function setPrice(?ProductPrice $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDimension(): ?ProductDimension
    {
        return $this->dimension;
    }

    public function setDimension(?ProductDimension $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    
    abstract function resolveGQLType( TypeResolver $typeResolver): Type;

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
