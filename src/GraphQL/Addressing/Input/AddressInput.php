<?php

namespace App\GraphQL\Addressing\Input;

use App\Entity\Addressing\Address;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
abstract class AddressInput
{

    #[GQL\Field()]
    public ?string $googleId = null;

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

    #[Assert\Length(exactly: 2)]
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



    public function build(Address $address): void
    {
        if ($this->googleId)
            $address->setGoogleId($this->googleId);
        if ($this->firstName)
            $address->setFirstName($this->firstName);
        if ($this->lastName)
            $address->setLastName($this->lastName);
        if ($this->phoneNumber)
            $address->setPhoneNumber($this->phoneNumber);
        if ($this->emailAddress)
            $address->setEmailAddress($this->emailAddress);
        //
        if ($this->company)
            $address->setCompany($this->company);
        if ($this->street)
            $address->setStreet($this->street);
        if ($this->city)
            $address->setCity($this->city);
        if ($this->provinceCode)
            $address->setProvinceCode($this->provinceCode);
        if ($this->provinceName)
            $address->setProvinceName($this->provinceName);
        if ($this->postcode)
            $address->setPostcode($this->postcode);
        if ($this->countryCode)
            $address->setCountryCode($this->countryCode);
        if ($this->coordinate)
            $address->setCoordinate($this->coordinate->toInstance());
    }
}
