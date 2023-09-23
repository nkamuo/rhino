<?php

namespace App\GraphQL\Vehicle\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'Vehicle',
)]
class VehicleEdge extends Edge
{
}
