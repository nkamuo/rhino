<?php

namespace App\GraphQL\Catalog\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ProductInput
{
    #[GQL\Field()]
    public string $title;

    #[GQL\Field()]
    public ?string $description;

    #[GQL\Field()]
    public ?ProductPriceInput $price;

    #[GQL\Field()]
    public ?ProductDimensionInput $dimension;
}
