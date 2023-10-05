<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\Driver;
use App\Entity\Account\DriverAddress;
use App\Entity\Account\Gender;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class DriverInput
{
    #[GQL\Field(type: 'Date')]
    public \DateTimeInterface $dob;

    #[GQL\Field()]
    public ?Gender $gender;

    #[GQL\Field()]
    public ?DriverAddressInput $address;

    public function build(Driver $driver): void
    {
        if ($this->dob)
            $driver->setDob($this->dob);
        if ($this->gender)
            $driver->setGender($this->gender);
        if ($this->address) {
            $address = $driver->getAddress() ?? new DriverAddress();
            $this->address->build($address);
            $driver->setAddress($address);
        }
    }
}
