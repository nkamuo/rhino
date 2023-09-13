<?php
namespace App\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;


#[GQL\Type()]
class DriverQuery
{
    #[GQL\Field()]
    public function getVersion(): String
    {
        return "09.93";
    }
}
