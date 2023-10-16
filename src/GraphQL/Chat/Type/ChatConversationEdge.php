<?php
namespace App\GraphQL\Chat\Type;
use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation\Relay as Relay;

#[Relay\Edge(node: 'ChatDMConversation')]
class ChatConversationEdge extends Edge{

}