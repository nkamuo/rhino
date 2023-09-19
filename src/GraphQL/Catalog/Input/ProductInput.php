<?php

namespace App\GraphQL\Catalog\Input;

use App\Entity\Catalog\Product;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class ProductInput
{
    #[GQL\Field()]
    public string $title;

    #[GQL\Field()]
    public ?string $description;

    
    #[GQL\Field()]
    public int $weight;

    #[GQL\Field()]
    public ?ProductPriceInput $price = null;

    #[GQL\Field()]
    public ProductDimensionInput $dimension;
    
    #[GQL\Field(type: "Ulid!")]
    public Ulid $categoryId;

    public function build(Product $product): void
    {
        $product
            ->setTitle($this->title)
            ->setDescription($this->description)
            ->setWeight($this->weight)
            ->setPrice($this->price?->toInstance())
            ->setDimension($this->dimension?->toInstance())
            ;
    }
}
