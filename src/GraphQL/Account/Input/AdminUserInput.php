<?php
namespace App\GraphQL\Account\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminUserInput extends UserInput{

    #[GQL\Field(type: "Ulid")]
    public ?Ulid $userId;

}
