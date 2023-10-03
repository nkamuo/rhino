<?php

namespace App\GraphQL\Shipment\Input\Assessment\Parameter;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input]
abstract class ShipmentAssessmentParameterInput
{

    #[GQL\Field()]
    public ?string $code = null;


    #[GQL\Field()]
    public string $title;


    #[GQL\Field()]
    public string $subtitle;

    #[GQL\Field()]
    public ?string $description = null;


    #[GQL\Field()]
    public ?string $icon = null;
}
