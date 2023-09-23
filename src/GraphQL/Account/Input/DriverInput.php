<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\Driver;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class DriverInput
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

    public function build(Driver $driver): void
    {
        $driver
            // ->setFirstName($this->firstName)
            // ->setLastName($this->lastName)
            // ->setEmail($this->email)
            // ->setPhone($this->phone)
            // ->setTitle($this->title)
            // ->setDescription($this->description)
        ;
    }
}
