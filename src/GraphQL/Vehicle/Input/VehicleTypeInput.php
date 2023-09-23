<?php

namespace App\GraphQL\Vehicle\Input;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class VehicleTypeInput
{
    #[GQL\Field()]
    public string $code;

    #[GQL\Field()]
    public string $shortName;

    #[GQL\Field()]
    public string $name;

    #[GQL\Field()]
    public ?string $iconImage = null;

    #[GQL\Field()]
    public ?string $primaryImage = null;

    #[GQL\Field()]
    public ?string $coverImage = null;


    #[GQL\Field()]
    public ?string $clientNote = null;

    #[GQL\Field()]
    public ?string $driverNote = null;

    #[GQL\Field()]
    public ?string $note = null;

    #[GQL\Field()]
    public ?string $description = null;

    #[GQL\Field()]
    public ?bool $enabled = false;
}
