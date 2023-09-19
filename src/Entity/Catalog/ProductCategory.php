<?php

namespace App\Entity\Catalog;

use App\Repository\Catalog\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $code = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[GQL\Field()]
    #[ORM\Column(length: 16, nullable: true)]
    private ?string $shortName = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iconImage = null;

    #[GQL\Field(type:'[Product!]!')]
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getIconImage(): ?string
    {
        return $this->iconImage;
    }

    public function setIconImage(?string $iconImage): static
    {
        $this->iconImage = $iconImage;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
