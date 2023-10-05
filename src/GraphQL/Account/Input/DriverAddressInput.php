<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\DriverAddress;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class DriverAddressInput
{

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

    public function build(DriverAddress $address): void
    {

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
    }
}
