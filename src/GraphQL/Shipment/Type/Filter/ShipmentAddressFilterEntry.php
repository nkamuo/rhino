<?php

namespace App\GraphQL\Shipment\Type\Filter;

use App\Entity\Addressing\Address;
use Overblog\GraphQLBundle\Annotation as GQL;


#[GQL\Type()]
class ShipmentAddressFilterEntry
{

    #[GQL\Field()]
    private ?string $city = null;

    #[GQL\Field()]
    private ?string $province = null;

    #[GQL\Field()]
    private ?string $country = null;


    #[GQL\Field()]
    private ?int $count = null;


    private function __construct(array $data)
    {

        $this->city = $data['city'];
        $this->province = $data['provinceCode'];
        $this->country = $data['countryCode'];
        $this->count = $data['count'];
    }


    public static function create(array $data): static
    {
        return new static($data);
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function getCount(): ?int
    {
        return $this->count;
    }
}
