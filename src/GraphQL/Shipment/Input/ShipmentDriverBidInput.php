<?php

namespace App\GraphQL\Shipment\Input;

use App\Entity\Shipment\ShipmentDriverBidPrice;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

// #[Assert\Expression("value.isValid()")]
#[GQL\Input()]
class ShipmentDriverBidInput
{
    #[Assert\Length(exactly: 3)]
    #[GQL\Field()]
    public string $currency;

    #[GQL\Field()]
    public int $amount;


    public function toInstance(): ShipmentDriverBidPrice
    {
        $price = new ShipmentDriverBidPrice();
        $price
            ->setCurrency($this->currency)
            ->setAmount($this->amount)
            ;
        return  $price;
    }

}
