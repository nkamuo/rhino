<?php
namespace App\GraphQL\Chat\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation\Relay as Relay;



#[Relay\Connection( edge: 'ChatChannelEdge')]
class ChatChannelConnection extends Connection{

}