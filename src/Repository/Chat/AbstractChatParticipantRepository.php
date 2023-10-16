<?php

namespace App\Repository\Chat;

use App\Entity\Chat\AbstractChatParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbstractChatParticipant>
 *
 * @method AbstractChatParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractChatParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractChatParticipant[]    findAll()
 * @method AbstractChatParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractChatParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractChatParticipant::class);
    }

//    /**
//     * @return AbstractChatParticipant[] Returns an array of AbstractChatParticipant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AbstractChatParticipant
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function save(AbstractChatParticipant $entity, bool $flush = false): void
{
    $this->getEntityManager()->persist($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}

public function remove(AbstractChatParticipant $entity, bool $flush = false): void
{
    $this->getEntityManager()->remove($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}
}
