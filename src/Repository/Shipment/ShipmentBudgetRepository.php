<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentBudget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentBudget>
 *
 * @method ShipmentBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentBudget[]    findAll()
 * @method ShipmentBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentBudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentBudget::class);
    }

//    /**
//     * @return ShipmentBudget[] Returns an array of ShipmentBudget objects
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

//    public function findOneBySomeField($value): ?ShipmentBudget
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
