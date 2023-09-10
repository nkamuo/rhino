<?php

namespace App\Repository\Catalog;

use App\Entity\Catalog\UserProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProduct>
 *
 * @method UserProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProduct[]    findAll()
 * @method UserProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProduct::class);
    }

//    /**
//     * @return UserProduct[] Returns an array of UserProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserProduct
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
