<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentExecution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentExecution>
 *
 * @method ShipmentExecution|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentExecution|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentExecution[]    findAll()
 * @method ShipmentExecution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentExecutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentExecution::class);
    }

//    /**
//     * @return ShipmentExecution[] Returns an array of ShipmentExecution objects
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

//    public function findOneBySomeField($value): ?ShipmentExecution
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
