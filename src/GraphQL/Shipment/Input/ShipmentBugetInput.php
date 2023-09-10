<?php
namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression("value.isValid()")]
#[GQL\Input()]
class ShipmentBugetInput {
    #[GQL\Field()]
    public string $currency;

    #[GQL\Field()]
    public ?int $from;

    #[GQL\Field()]
    public ?int $to;

    #[GQL\Field()]
    public ?int $amount;

    public function isValid(): bool{
        if($this->from === null && $this->to === null){
            return ($this->amount != null) && ($this->amount > 0);
        }

        if($this->amount === null){
            if($this->from === null || $this->to === null){
                return false;
            }
            return $this->to > $this-> from;
        }
        return false;
    }
}