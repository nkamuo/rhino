<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentDriverBidPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentDriverBidPrice>
 *
 * @method ShipmentDriverBidPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentDriverBidPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentDriverBidPrice[]    findAll()
 * @method ShipmentDriverBidPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentDriverBidPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentDriverBidPrice::class);
    }

//    /**
//     * @return ShipmentDriverBidPrice[] Returns an array of ShipmentDriverBidPrice objects
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

//    public function findOneBySomeField($value): ?ShipmentDriverBidPrice
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
