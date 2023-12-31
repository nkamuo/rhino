<?php

namespace App\GraphQL\Catalog\Input;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class ProductCategoryInput
{
    #[GQL\Field()]
    public string $code;

    #[GQL\Field()]
    public string $shortName;

    #[GQL\Field()]
    public string $name;

    #[GQL\Field()]
    public ?string $iconImage;

    #[GQL\Field()]
    public ?string $primaryImage;

    #[GQL\Field()]
    public ?string $coverImage;

    #[GQL\Field()]
    public ?string $clientNote;

    #[GQL\Field()]
    public ?string $driverNote;

    #[GQL\Field()]
    public ?string $note;

    #[GQL\Field()]
    public ?string $description;

    #[GQL\Field()]
    public ?bool $enabled = false;
}
