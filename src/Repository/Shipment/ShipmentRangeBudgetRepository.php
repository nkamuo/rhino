<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentRangeBudget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentRangeBudget>
 *
 * @method ShipmentRangeBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentRangeBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentRangeBudget[]    findAll()
 * @method ShipmentRangeBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentRangeBudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentRangeBudget::class);
    }

//    /**
//     * @return ShipmentRangeBudget[] Returns an array of ShipmentRangeBudget objects
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

//    public function findOneBySomeField($value): ?ShipmentRangeBudget
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
