<?php

namespace App\Repository\Account;

use App\Entity\Account\DriverSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DriverSubscription>
 *
 * @method DriverSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method DriverSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method DriverSubscription[]    findAll()
 * @method DriverSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverSubscription::class);
    }

//    /**
//     * @return DriverSubscription[] Returns an array of DriverSubscription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DriverSubscription
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
