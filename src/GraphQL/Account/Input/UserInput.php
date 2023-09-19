<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class UserInput
{
    #[GQL\Field()]
    public string $firstName;

    #[GQL\Field()]
    public string $lastName;

    #[GQL\Field()]
    public string $email;

    #[GQL\Field()]
    public string $phone;

    #[GQL\Field()]
    public string $password;

    #[Assert\Choice(options: ['SHIPPER', 'TRUCKER'])]
    #[GQL\Field()]
    public ?string $type;

    public function build(User $user): void
    {
        $user
            ->setFirstName($this->firstName)
            ->setLastName($this->lastName)
            ->setEmail($this->email)
            ->setPhone($this->phone)
            // ->setTitle($this->title)
            // ->setDescription($this->description)
        ;
    }
}
