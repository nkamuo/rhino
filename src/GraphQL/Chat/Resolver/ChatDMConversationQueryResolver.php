<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\QueryBusInterface;
use App\Entity\Account\User;
use App\Entity\Chat\ChatConversation;
use App\Entity\Chat\DM\Conversation;
use App\Entity\Chat\DM\DMChatChannel;
use App\GraphQL\Chat\Type\ChatConversationConnection;
use App\GraphQL\Chat\Type\ChatConversationEdge;
use App\Message\Chat\Channel\CountChannel;
use App\Message\Chat\Channel\FindChannelById;
use App\Message\Chat\Channel\SearchChannel;
use App\Repository\Chat\DM\ConversationRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Provider;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;

#[Provider(
    targetQueryTypes: [
        'Query', 'ClientQuery', 'DriverQuery'
    ],
    targetMutationTypes: [
        'Query', 'ClientMutation', 'DriverMutation'
    ]
)]
class ChatDMConversationQueryResolver
{

    public function __construct(
        private ConversationRepository $conversationRepository,
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }



    #[Query(name: "get_chat_conversation_item")]
    #[Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getChatConversation(#[Arg(type: 'Ulid!')] Ulid $id): ?Conversation
    {
        $conversation = $this->conversationRepository->find($id);
        if(null === $conversation){
            throw new UserError("Could not find conversation with [id:{$id}]");
        }
        return $conversation;
    }



    #[Query(name: 'get_chat_conversation_list')]
    public function getChatConversationList(
        ?string $filter = null,
        ?int $first = null,
        ?string $after = null,
    ): ChatConversationConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ChatConversationConnection($edges, $pageInfo),
            fn (string $coursor, Conversation $brand, int $index) => new ChatConversationEdge($coursor, $brand)
        );

        $qb = $this->conversationRepository->createQueryBuilder('conversation');

        QueryBuilderHelper::applyCriteria($qb, $filter, 'conversation');

        $user = $this->getUser();

        $qb
            ->innerJoin('conversation.sender', 'user')
            ->andWhere("user.id = :user")
            ->setParameter("user", $user->getId(), UlidType::NAME);

        $total = fn () => (int) (clone $qb)->select('COUNT(conversation.id)')->getQuery()->getSingleScalarResult();

        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);


        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }



    #[Query]
    public function getDMChatChannel(): ?DMChatChannel
    {
        return null;
    }

    #[Query]
    public function getDMChatParticipant(): ?Conversation
    {
        return null;
    }


    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }
}
