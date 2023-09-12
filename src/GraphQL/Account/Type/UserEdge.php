<?php

namespace App\GraphQL\Account\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'User',
)]
class UserEdge extends Edge
{
}
