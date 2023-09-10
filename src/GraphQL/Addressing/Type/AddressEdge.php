<?php

namespace App\GraphQL\Addressing\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'Address',
)]
class AddressEdge extends Edge
{
}
