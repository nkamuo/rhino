<?php

namespace App\Repository\Shipment\Assessment;

use App\Entity\Shipment\Assessment\UnitReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnitReview>
 *
 * @method UnitReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitReview[]    findAll()
 * @method UnitReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitReview::class);
    }

//    /**
//     * @return UnitReview[] Returns an array of UnitReview objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UnitReview
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
