<?php

namespace App\Repository\Account;

use App\Entity\Account\DriverAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DriverAddress>
 *
 * @method DriverAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method DriverAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method DriverAddress[]    findAll()
 * @method DriverAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverAddress::class);
    }

//    /**
//     * @return DriverAddress[] Returns an array of DriverAddress objects
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

//    public function findOneBySomeField($value): ?DriverAddress
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
