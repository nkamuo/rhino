<?php
namespace App\MessageHandler\Chat;
use App\Entity\Chat\ChatSubject;
use App\Message\Chat\Subject\CountSubject;
use App\Message\Chat\Subject\FindSubjectById;
use App\Message\Chat\Subject\SearchSubject;
use App\Repository\Chat\ChatSubjectRepository;
use App\Util\Doctrine\QueryBuilderUtil;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


class ChatSubjectQueryHandler{


    public function __construct(private ChatSubjectRepository $repository){

    }
    
    #[AsMessageHandler]
    public function findOrderById(FindSubjectById $query): ?ChatSubject{
        return $this->repository->find($query->getId());
    }


    #[AsMessageHandler]
    public function search(SearchSubject $search): array{

        $qb = $this->repository->createQueryBuilder('chat_subject');
     
        QueryBuilderUtil::applyCriteria($qb, $search->getFilter(), 'chat_subject');
        QueryBuilderUtil::enableQueryCache($qb);

        if($orderId = $search->getChannelId()){
            $qb->andWhere('chat_subject.channel = :channelId')
                ->setParameter('channelId',$orderId,UlidType::NAME);
        }


        return $qb
            ->setFirstResult($search->getOffset())
            ->setMaxResults($search->getLimit())
            ->getQuery()
            ->getResult();
    }


    #[AsMessageHandler]
    public function count(CountSubject $search): int{

        $qb = $this->repository->createQueryBuilder('chat_subject');

        QueryBuilderUtil::applyCriteria($qb, $search->getFilter(), 'chat_subject');
        
        if($orderId = $search->getChannelId()){
            $qb->andWhere('chat_subject.channel = :channelId')
                ->setParameter('channelId',$orderId,UlidType::NAME);
        }
        
        return (int) $qb->select('COUNT(chat_subject.id)')->getQuery()->getSingleScalarResult();
    }




   
}