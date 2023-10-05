<?php
namespace App\MessageHandler\Chat;
use App\Entity\Chat\ChatParticipant;
use App\Message\Chat\Participant\CountParticipant;
use App\Message\Chat\Participant\FindParticipantById;
use App\Message\Chat\Participant\SearchParticipant;
use App\Repository\Chat\ChatParticipantRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


class ChatParticipantQueryHandler{


    public function __construct(private ChatParticipantRepository $repository){

    }
    
    #[AsMessageHandler]
    public function findOrderById(FindParticipantById $query): ?ChatParticipant{
        return $this->repository->find($query->getId());
    }


    #[AsMessageHandler]
    public function search(SearchParticipant $search): array{

        $qb = $this->repository->createQueryBuilder('chat_participant');
     
        QueryBuilderHelper::applyCriteria($qb, $search->getFilter(), 'chat_participant');
        QueryBuilderHelper::enableQueryCache($qb);

        if($orderId = $search->getChannelId()){
            $qb->andWhere('chat_participant.channel = :channelId')
                ->setParameter('channelId',$orderId,UlidType::NAME);
        }


        return $qb
            ->setFirstResult($search->getOffset())
            ->setMaxResults($search->getLimit())
            ->getQuery()
            ->getResult();
    }


    #[AsMessageHandler]
    public function count(CountParticipant $search): int{

        $qb = $this->repository->createQueryBuilder('chat_participant');

        QueryBuilderHelper::applyCriteria($qb, $search->getFilter(), 'chat_participant');
        
        if($orderId = $search->getChannelId()){
            $qb->andWhere('chat_participant.channel = :channelId')
                ->setParameter('channelId',$orderId,UlidType::NAME);
        }
        
        return (int) $qb->select('COUNT(chat_participant.id)')->getQuery()->getSingleScalarResult();
    }




   
}