<?php
namespace App\GraphQL\Account\Type;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'UserEdge',
)]
class UserConnection extends Connection{

}