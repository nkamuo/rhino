<?php

namespace App\Repository\Vehicle;

use App\Entity\Vehicle\VehicleDimension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleDimension>
 *
 * @method VehicleDimension|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleDimension|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleDimension[]    findAll()
 * @method VehicleDimension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleDimensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleDimension::class);
    }

//    /**
//     * @return VehicleDimension[] Returns an array of VehicleDimension objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VehicleDimension
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
