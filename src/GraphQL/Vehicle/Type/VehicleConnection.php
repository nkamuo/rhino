<?php

namespace App\GraphQL\Vehicle\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'VehicleEdge',
)]
class VehicleConnection extends Connection
{
}
