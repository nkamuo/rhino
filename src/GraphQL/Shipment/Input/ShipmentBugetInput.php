<?php

namespace App\GraphQL\Shipment\Input;

use App\Entity\Shipment\ShipmentBudget;
use App\Entity\Shipment\ShipmentFixedBudget;
use App\Entity\Shipment\ShipmentRangeBudget;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression("value.isValid()")]
#[GQL\Input()]
class ShipmentBugetInput
{
    #[Assert\Length(exactly: 3)]
    #[GQL\Field()]
    public string $currency;

    #[GQL\Field()]
    public ?int $from = null;

    #[GQL\Field()]
    public ?int $to = null;

    #[GQL\Field()]
    public ?int $amount = null;


    public function toInstance(): ShipmentBudget
    {

        if ($this->from === null && $this->to === null) {
            if ($this->amount === null) {
                throw new InvalidArgumentException("Invalid Budget Input: amount is required");
            }
            $budget =  new ShipmentFixedBudget();
            $budget
                ->setCurrency($this->currency)
                ->setPrice($this->amount);
            return $budget;
        }

        if ($this->amount === null) {
            if ($this->from === null || $this->to === null) {
                throw new InvalidArgumentException("You need to provide \"from\" and \"to\"");
            }
            if (!($this->to > $this->from)) {
                throw new InvalidArgumentException("\"to\" must be greater than \"from\"");
            }

            $budget = new ShipmentRangeBudget();
            $budget
                ->setCurrency($this->currency)
                ->setMinPrice($this->from)
                ->setMaxPrice($this->to);
            return $budget;
        }

        throw new InvalidArgumentException("Invalid Budget input provided");
    }

    public function isValid(): bool
    {
        if ($this->from === null && $this->to === null) {
            return ($this->amount != null) && ($this->amount > 0);
        }

        if ($this->amount === null) {
            if ($this->from === null || $this->to === null) {
                return false;
            }
            return $this->to > $this->from;
        }
        return false;
    }
}
