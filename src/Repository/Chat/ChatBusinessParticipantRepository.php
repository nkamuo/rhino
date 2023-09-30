<?php

namespace App\Repository\Chat;

use App\Entity\Chat\ChatBusinessParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChatBusinessParticipant>
 *
 * @method ChatBusinessParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatBusinessParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatBusinessParticipant[]    findAll()
 * @method ChatBusinessParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatBusinessParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatBusinessParticipant::class);
    }

    public function save(ChatBusinessParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChatBusinessParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ChatBusinessParticipant[] Returns an array of ChatBusinessParticipant objects
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

//    public function findOneBySomeField($value): ?ChatBusinessParticipant
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
