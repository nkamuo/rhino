<?php

namespace App\Repository\Addressing\Routing;

use App\Entity\Addressing\Routing\RouteSegment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RouteSegment>
 *
 * @method RouteSegment|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteSegment|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteSegment[]    findAll()
 * @method RouteSegment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteSegmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteSegment::class);
    }

//    /**
//     * @return RouteSegment[] Returns an array of RouteSegment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RouteSegment
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
