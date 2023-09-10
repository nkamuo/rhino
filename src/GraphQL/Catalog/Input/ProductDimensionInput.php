<?php

namespace App\GraphQL\Catalog\Input;

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
}
