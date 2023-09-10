<?php

namespace App\GraphQL\Addressing\Input;

use App\Entity\Addressing\Address;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class AddressInput
{
    #[GQL\Field()]
    public ?string $firstName = null;

    #[GQL\Field()]
    public ?string $lastName = null;

    #[GQL\Field()]
    public ?string $phoneNumber = null;

    #[GQL\Field()]
    public ?string $emailAddress = null;

    #[GQL\Field()]
    public ?string $company = null;

    #[GQL\Field()]
    public ?string $countryCode = null;

    #[GQL\Field()]
    public ?string $provinceCode = null;

    #[GQL\Field()]
    public ?string $provinceName = null;

    #[GQL\Field()]
    public ?string $city = null;

    #[GQL\Field()]
    public ?string $street = null;

    #[GQL\Field()]
    public ?string $postcode = null;

    #[GQL\Field()]
    public AddressCoordinateInput $coordinate;



    public function build(Address $address):void{
        $address
            ->setFirstName($this->firstName)
            ->setLastName($this->lastName)
            ->setPhoneNumber($this->phoneNumber)
            ->setEmailAddress($this->emailAddress)
            ->setCompany($this->company)
            ->setStreet($this->street)
            ->setCity($this->city)
            ->setProvinceCode($this->provinceCode)
            ->setProvinceName($this->provinceName)
            ->setPostcode($this->postcode)
            ->setCountryCode($this->countryCode)
            ->setCoordinate($this->coordinate->toInstance())
            ;
    }
}