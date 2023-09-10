<?php
namespace App\GraphQL\Catalog\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminProductInput extends ProductInput{

    #[GQL\Field(type: "Ulid")]
    public ?Ulid $userId;

}