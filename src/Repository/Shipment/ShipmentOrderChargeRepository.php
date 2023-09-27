<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentOrderCharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentOrderCharge>
 *
 * @method ShipmentOrderCharge|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentOrderCharge|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentOrderCharge[]    findAll()
 * @method ShipmentOrderCharge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentOrderChargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentOrderCharge::class);
    }

//    /**
//     * @return ShipmentOrderCharge[] Returns an array of ShipmentOrderCharge objects
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

//    public function findOneBySomeField($value): ?ShipmentOrderCharge
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
