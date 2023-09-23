<?php
namespace App\GraphQL\Account\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminDriverInput extends DriverInput{

    #[GQL\Field(type: "Ulid")]
    public ?Ulid $userId;

}
