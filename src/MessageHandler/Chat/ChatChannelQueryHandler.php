<?php
namespace App\MessageHandler\Chat;

use App\Entity\Chat\AbstractChatChannel;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatUserParticipant;
use App\Message\Chat\Channel\CountChannel;
use App\Message\Chat\Channel\FindChannelById;
use App\Message\Chat\Channel\SearchChannel;
use App\Repository\Chat\AbstractChatChannelRepository;
use App\Repository\Chat\ChatChannelRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


class ChatChannelQueryHandler
{


    public function __construct(private AbstractChatChannelRepository $repository)
    {

    }

    #[AsMessageHandler]
    public function findOrderById(FindChannelById $query): ?AbstractChatChannel
    {
        return $this->repository->find($query->getId());
    }


    #[AsMessageHandler]
    public function search(SearchChannel $search): array
    {

        $qb = $this->repository->createQueryBuilder('chat_channel');

        $this->applyFilters($qb, $search);

        return $qb
            ->setFirstResult($search->getOffset())
            ->setMaxResults($search->getLimit())
            ->getQuery()
            ->getResult();
    }


    #[AsMessageHandler]
    public function count(CountChannel $search): int
    {

        $qb = $this->repository->createQueryBuilder('chat_channel');
        $this->applyFilters($qb, $search);
        return (int) $qb->select('COUNT(chat_channel.id)')->getQuery()->getSingleScalarResult();
    }





    private function applyFilters(QueryBuilder $qb, CountChannel|SearchChannel $search): void
    {


        if ($activeUserId = $search->getActiveUserId()) {
            $qb
                // ->leftJoin('chat_channel.participants', 'participant')
                // ->join(ChatUserParticipant::class, 'user_participant', 'user_participant.id = participant.id')
                // ->andWhere('user_participant.userId = :activeUserId')
                ->andWhere(
                    $qb->expr()->exists(
                        $qb->getEntityManager()->createQueryBuilder()
                            ->select('user_participant')
                            ->from(ChatUserParticipant::class, 'user_participant')
                            ->where('user_participant.channel = chat_channel')
                            ->andWhere('user_participant.userId = :activeUserId')
                            // ->andWhere('p.publishedAt >= :oneWeekAgo')
                    )
                )
                ->setParameter('activeUserId', $activeUserId, UlidType::NAME)
            ;

        }

        QueryBuilderHelper::applyCriteria($qb, $search->getFilter(), 'chat_channel');
        QueryBuilderHelper::enableQueryCache($qb);
    }



}