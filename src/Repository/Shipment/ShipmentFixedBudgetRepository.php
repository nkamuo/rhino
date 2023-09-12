<?php

namespace App\Repository\Shipment;

use App\Entity\Shipment\ShipmentFixedBudget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentFixedBudget>
 *
 * @method ShipmentFixedBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentFixedBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentFixedBudget[]    findAll()
 * @method ShipmentFixedBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentFixedBudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentFixedBudget::class);
    }

//    /**
//     * @return ShipmentFixedBudget[] Returns an array of ShipmentFixedBudget objects
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

//    public function findOneBySomeField($value): ?ShipmentFixedBudget
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
