<?php
namespace App\GraphQL\Addressing\Type;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'AddressEdge',
)]
class AddressConnection extends Connection{

}