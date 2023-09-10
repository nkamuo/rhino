<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentNode>
 *
 * @method ShipmentNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentNode[]    findAll()
 * @method ShipmentNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentNode::class);
    }

//    /**
//     * @return ShipmentNode[] Returns an array of ShipmentNode objects
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

//    public function findOneBySomeField($value): ?ShipmentNode
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
