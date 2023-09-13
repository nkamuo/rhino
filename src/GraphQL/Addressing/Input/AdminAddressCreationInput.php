<?php
namespace App\GraphQL\Addressing\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminAddressCreationInput extends AddressInput{
    
    #[GQL\Field(type: "Ulid!")]
    public Ulid $userId;
}