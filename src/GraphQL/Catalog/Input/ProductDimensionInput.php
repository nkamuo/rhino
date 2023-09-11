<?php

namespace App\GraphQL\Catalog\Input;

use App\Entity\Catalog\ProductDimension;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ProductDimensionInput
{
    #[GQL\Field()]
    public int $length;

    #[GQL\Field()]
    public int $width;

    #[GQL\Field()]
    public int $height;

    #[GQL\Field()]
    public string $unit;


    public function toInstance(): ProductDimension
    {
        $dimension = new ProductDimension();
        $dimension
            ->setLength($this->length)
            ->setWidth($this->width)
            ->setHeight($this->height)
            ->setUnit($this->unit);
        return $dimension;
    }
}
