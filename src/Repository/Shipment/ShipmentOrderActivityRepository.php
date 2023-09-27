<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentOrderActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentOrderActivity>
 *
 * @method ShipmentOrderActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentOrderActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentOrderActivity[]    findAll()
 * @method ShipmentOrderActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentOrderActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentOrderActivity::class);
    }

//    /**
//     * @return ShipmentOrderActivity[] Returns an array of ShipmentOrderActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShipmentOrderActivity
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
