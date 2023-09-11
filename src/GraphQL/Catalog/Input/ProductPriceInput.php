<?php

namespace App\GraphQL\Catalog\Input;

use App\Entity\Catalog\ProductPrice;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class ProductPriceInput
{

    #[GQL\Field()]
    #[Assert\Length(exactly: 3)]
    public string $currency;

    #[Assert\GreaterThanOrEqual(0)]
    #[GQL\Field()]
    public int $amount;


    public function toInstance(): ProductPrice
    {
        $price = new ProductPrice();
        $price
            ->setAmount($this->amount)
            ->setCurrency($this->currency);
        return $price;
    }
}
