<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatParticipant;
use App\GraphQL\Chat\Type\ChatParticipantConnection;
use App\GraphQL\Chat\Type\ChatParticipantEdge;
use App\Message\Chat\Participant\CountParticipant;
use App\Message\Chat\Participant\FindParticipantById;
use App\Message\Chat\Participant\SearchParticipant;
use App\Service\Chat\ChatContextResolverInterface;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Provider;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Component\Uid\Ulid;

#[Provider(targetQueryTypes: ['Query'])]
class ChatParticipantQueryResolver
{

    public function __construct(
        private QueryBusInterface $queryBus,
        private ChatContextResolverInterface $chatContextResolver,
    ) {
    }



    #[Query(name: "get_chat_participant")]
    #[Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getChatParticipant(#[Arg(type: 'Ulid!')] Ulid $id): ?ChatParticipant
    {

        return $this->queryBus->query(new FindParticipantById($id));
    }



    #[Query(name: 'get_chat_participant_list')]
    #[Arg(
        name: 'channelId',
        type: 'Ulid!'
    )]
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
    public function getChatParticipantList(
        Ulid $channelId,
        ?string $filter = null,
        ?int $first = null,
        ?string $after = null,
    ): ChatParticipantConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ChatParticipantConnection($edges, $pageInfo),
            fn (string $coursor, ChatParticipant $brand, int $index) => new ChatParticipantEdge($coursor, $brand)
        );

        $total = fn () => $this->queryBus->query((new CountParticipant($filter))->setChannelId($channelId));
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($filter, $channelId) {

            $query = (new SearchParticipant(
                filter: $filter,
                offset: $offset,
                limit: $limit,

            ))->setChannelId($channelId);

            return $this->queryBus->query($query);
        }, false, $cb);


        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}
