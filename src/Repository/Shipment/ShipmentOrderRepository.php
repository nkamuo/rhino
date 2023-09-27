<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentOrder>
 *
 * @method ShipmentOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentOrder[]    findAll()
 * @method ShipmentOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentOrder::class);
    }

//    /**
//     * @return ShipmentOrder[] Returns an array of ShipmentOrder objects
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

//    public function findOneBySomeField($value): ?ShipmentOrder
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
