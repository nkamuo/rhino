<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatChannel;
use App\GraphQL\Chat\Type\ChatChannelConnection;
use App\GraphQL\Chat\Type\ChatChannelEdge;
use App\Message\Chat\Channel\CountChannel;
use App\Message\Chat\Channel\FindChannelById;
use App\Message\Chat\Channel\SearchChannel;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Provider;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Component\Uid\Ulid;

#[Provider(targetQueryTypes: 'Query')]
class ChatChannelQueryResolver
{

    public function __construct(private QueryBusInterface $queryBus)
    {
    }




    #[Query(name: "get_chat_channel")]
    #[Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getChatChannel(#[Arg(type: 'Ulid!')] Ulid $id): ?ChatChannel
    {
        return $this->queryBus->query(new FindChannelById($id));
    }



    #[Query(name: 'get_chat_channel_list')]
    public function getChatChannelList(
        ?string $filter = null,
        ?int $first = null,
        ?string $after = null,
    ): ChatChannelConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ChatChannelConnection($edges, $pageInfo),
            fn (string $coursor, ChatChannel $brand, int $index) => new ChatChannelEdge($coursor, $brand)
        );
        $total = fn () => $this->queryBus->query(new CountChannel($filter));
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($filter) {

            $query = new SearchChannel(
                filter: $filter,
                offset: $offset,
                limit: $limit,

            );

            return $this->queryBus->query($query);
        }, false, $cb);


        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}
