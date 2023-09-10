<?php

namespace App\Repository\Catalog;

use App\Entity\Catalog\ProductDimension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductDimension>
 *
 * @method ProductDimension|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDimension|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDimension[]    findAll()
 * @method ProductDimension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDimensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDimension::class);
    }

//    /**
//     * @return ProductDimension[] Returns an array of ProductDimension objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductDimension
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
