<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class UserInput
{
    #[GQL\Field()]
    public string $title;

    #[GQL\Field()]
    public ?string $description;

    public function build(User $user): void
    {
        $user
            // ->setTitle($this->title)
            // ->setDescription($this->description)
            ;
    }
}
