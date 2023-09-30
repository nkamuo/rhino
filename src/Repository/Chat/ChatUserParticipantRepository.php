<?php

namespace App\Repository\Chat;

use App\Entity\Chat\ChatUserParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChatUserParticipant>
 *
 * @method ChatUserParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatUserParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatUserParticipant[]    findAll()
 * @method ChatUserParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatUserParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatUserParticipant::class);
    }

    public function save(ChatUserParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChatUserParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ChatUserParticipant[] Returns an array of ChatUserParticipant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChatUserParticipant
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
