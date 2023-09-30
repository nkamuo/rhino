<?php
namespace App\MessageHandler\Chat;
use App\Entity\Chat\ChatMessage;
use App\Message\Chat\Message\CountChatMessage;
use App\Message\Chat\Message\FindChatMessageById;
use App\Message\Chat\Message\SearchChatMessage;
use App\Repository\Chat\ChatMessageRepository;
use App\Util\Doctrine\QueryBuilderUtil;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


class ChatMessageQueryHandler{


    public function __construct(private ChatMessageRepository $repository){

    }
    
    #[AsMessageHandler]
    public function findOrderById(FindChatMessageById $query): ?ChatMessage{
        return $this->repository->find($query->getId());
    }


    #[AsMessageHandler]
    public function search(SearchChatMessage $search): array{

        $qb = $this->repository->createQueryBuilder('chat_message');
     
        QueryBuilderUtil::applyCriteria($qb, $search->getFilter(), 'chat_message');
        QueryBuilderUtil::enableQueryCache($qb);

        if($channelId = $search->getChannelId()){
            $qb->andWhere('chat_message.channel = :channelId')
                ->setParameter('channelId',$channelId,UlidType::NAME);
        }

        if($subjectId = $search->getSubjectId()){
            $qb->andWhere('chat_message.subject = :subjectId')
                ->setParameter('subjectId',$subjectId,UlidType::NAME);
        }

        if($activeParticipantId = $search->getActiveParticipantId()){
           //TODO: ENSURE THAT ONLY MESSAGES READABLE BY THIS PARTICIPANT IS ALLOWED
        }
        


        return $qb
            ->setFirstResult($search->getOffset())
            ->setMaxResults($search->getLimit())
            ->getQuery()
            ->getResult();
    }


    #[AsMessageHandler]
    public function count(CountChatMessage $search): int{

        $qb = $this->repository->createQueryBuilder('chat_message');

        if($channelId = $search->getChannelId()){
            $qb->andWhere('chat_message.channel = :channelId')
                ->setParameter('channelId',$channelId,UlidType::NAME);
        }

        
        if($subjectId = $search->getSubjectId()){
            $qb->andWhere('chat_message.subject = :subjectId')
                ->setParameter('subjectId',$subjectId,UlidType::NAME);
        }

        if($activeParticipantId = $search->getActiveParticipantId()){
           //TODO: ENSURE THAT ONLY MESSAGES READABLE BY THIS PARTICIPANT IS ALLOWED
        }
        
        QueryBuilderUtil::applyCriteria($qb, $search->getFilter(), 'chat_message');
        
        return (int) $qb->select('COUNT(chat_message.id)')->getQuery()->getSingleScalarResult();
    }




   
}