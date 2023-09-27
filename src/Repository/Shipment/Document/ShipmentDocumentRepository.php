<?php

namespace App\Repository\Shipment\Document;

use App\Entity\Shipment\Document\ShipmentDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentDocument>
 *
 * @method ShipmentDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentDocument[]    findAll()
 * @method ShipmentDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentDocument::class);
    }

//    /**
//     * @return ShipmentDocument[] Returns an array of ShipmentDocument objects
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

//    public function findOneBySomeField($value): ?ShipmentDocument
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
