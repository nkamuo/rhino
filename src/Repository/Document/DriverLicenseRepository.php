<?php

namespace App\Repository\Document;

use App\Entity\Document\DriverLicense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DriverLicense>
 *
 * @method DriverLicense|null find($id, $lockMode = null, $lockVersion = null)
 * @method DriverLicense|null findOneBy(array $criteria, array $orderBy = null)
 * @method DriverLicense[]    findAll()
 * @method DriverLicense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverLicenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverLicense::class);
    }

//    /**
//     * @return DriverLicense[] Returns an array of DriverLicense objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DriverLicense
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
