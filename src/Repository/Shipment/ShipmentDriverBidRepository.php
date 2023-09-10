<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentDriverBid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentDriverBid>
 *
 * @method ShipmentDriverBid|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentDriverBid|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentDriverBid[]    findAll()
 * @method ShipmentDriverBid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentDriverBidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentDriverBid::class);
    }

//    /**
//     * @return ShipmentDriverBid[] Returns an array of ShipmentDriverBid objects
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

//    public function findOneBySomeField($value): ?ShipmentDriverBid
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
