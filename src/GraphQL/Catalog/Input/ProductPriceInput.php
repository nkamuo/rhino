<?php
namespace App\GraphQL\Catalog\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class ProductPriceInput{
    
    #[GQL\Field()]
    #[Assert\Length(exactly:3)]
    public string $curreny;

    #[Assert\GreaterThanOrEqual(0)]
    #[GQL\Field()]
    public int $amount;
}