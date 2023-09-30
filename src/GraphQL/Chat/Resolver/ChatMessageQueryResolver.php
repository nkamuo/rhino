<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatMessage;
use App\Entity\Chat\ChatParticipant;
use App\GraphQL\Chat\Type\ChatMessageConnection;
use App\GraphQL\Chat\Type\ChatMessageEdge;
use App\Message\Chat\Message\CountChatMessage;
use App\Message\Chat\Message\FindChatMessageById;
use App\Message\Chat\Message\SearchChatMessage;
use App\Service\Chat\ChatContextResolverInterface;
use App\Service\Transport\Shipment\Chat\ShipmentChatContextInterface;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Provider;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Component\Uid\Ulid;

#[Provider(targetQueryTypes: 'Query')]
class ChatMessageQueryResolver
{

    public function __construct(
        private QueryBusInterface $queryBus,
        private ChatContextResolverInterface $chatContextResolver,
    ) {
    }



    #[Query(name: "resolve_current_user_chat_channel_participant")]
    #[Arg(name: 'channelId', type: 'Ulid!')]
    public function getParticipantByChannelId(Ulid $channelId): ChatParticipant
    {
        return $this->chatContextResolver->resolveCurrentChatParticipantForChannelId($channelId);
    }





    #[Query(name: "get_chat_message")]
    #[Arg(name: 'id', type: 'Ulid!')]
    public function getChatMessage(Ulid $id): ?ChatMessage
    {

        return $this->queryBus->query(new FindChatMessageById($id));
    }



    #[Query(name: 'get_chat_message_list')]
    #[Arg(
        name: 'channelId',
        type: 'Ulid!'
    )]
    #[
        Arg(
            name: 'subjectId',
            type: 'Ulid'
        )
    ]
    #[Arg(
        name: 'filter',
        type: 'String'
    )]
    #[Arg(
        name: 'first',
        type: 'Int'
    )]
    #[Arg(
        name: 'after',
        type: 'String'
    )]
    public function getChatMessageList(
        Ulid $channelId,
        ?Ulid $subjectId = null,
        ?string $filter = null,
        ?int $first = null,
        ?string $after = null,
    ): ChatMessageConnection {


        $participant = $this->getoptionalParticipantByChannelId($channelId);
        $participantId = $participant?->getId();

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ChatMessageConnection($edges, $pageInfo),
            fn (string $coursor, ChatMessage $brand, int $index) => new ChatMessageEdge($coursor, $brand)
        );

        $total = fn () => $this->queryBus->query(
            (new CountChatMessage($filter))
                ->setChannelId($channelId)
                ->setSubjectId($subjectId)
                ->setActiveParticipantId($participantId)
        );

        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($filter, $channelId, $subjectId, $participantId) {

            $query = (new SearchChatMessage(
                    filter: $filter,
                    offset: $offset,
                    limit: $limit,

                )
            )
                ->setChannelId($channelId)
                ->setSubjectId($subjectId)
                ->setActiveParticipantId($participantId);

            return $this->queryBus->query($query);
        }, false, $cb);


        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }



    private function getoptionalParticipantByChannelId(Ulid $channelId): ?ChatParticipant
    {
        try {
            return $this->chatContextResolver->resolveCurrentChatParticipantForChannelId($channelId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
