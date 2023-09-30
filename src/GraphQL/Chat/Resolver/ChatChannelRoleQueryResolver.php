<?php
namespace App\GraphQL\Chat\Resolver;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatChannelRole;
use App\GraphQL\Chat\Type\ChatChannelRoleConnection;
use App\GraphQL\Chat\Type\ChatChannelRoleEdge;
use App\Message\Chat\Role\CountRole;
use App\Message\Chat\Role\FindRoleById;
use App\Message\Chat\Role\SearchRole;
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
class ChatChannelRoleQueryResolver
{

    public function __construct(
        private QueryBusInterface $queryBus,
        private ChatContextResolverInterface $chatContextResolver,
        )
    {

    }



    #[Query(name: "get_chat_channel_role")]
    #[Arg(name: 'id',  type: 'Ulid!')]
    public function getChatChannelRole(#[Arg(type: 'Ulid!')] Ulid $id): ?ChatChannelRole
    {

        return $this->queryBus->query(new FindRoleById($id));

    }



    #[Query(name: 'get_chat_channel_role_list')]
    #[Arg(name: 'channelId',  type: 'Ulid!')]
    #[Arg(name: 'filter', type: 'String!')]
    #[Arg(name: 'first', type: 'String')]
    #[Arg(name: 'after', type: 'String')]
    public function getChatChannelRoleList(
        Ulid $channelId,
        ?string $filter = null,
        ?int $first = null,
        ?string $after = null,
    ): ChatChannelRoleConnection {

        $cb = new ConnectionBuilder(
            null,
            fn($edges, PageInfoInterface $pageInfo) => new ChatChannelRoleConnection($edges, $pageInfo), fn(string $coursor, ChatChannelRole $brand, int $index) => new ChatChannelRoleEdge($coursor, $brand)
        );
        
        $total = fn() => $this->queryBus->query((new CountRole($filter))->setChannelId($channelId));
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($filter, $channelId) {

            $query = (new SearchRole(
                filter: $filter,
                offset: $offset,
                limit: $limit,

            ))->setChannelId($channelId);

            return $this->queryBus->query($query);
        }, false, $cb);


        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}