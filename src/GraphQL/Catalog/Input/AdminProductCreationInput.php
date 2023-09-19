<?php
namespace App\GraphQL\Catalog\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminProductCreationInput extends AdminProductInput{
    
    #[GQL\Field(type: "Ulid!")]
    public Ulid $ownerId;
}