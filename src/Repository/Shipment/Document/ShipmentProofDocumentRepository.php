<?php

namespace App\Repository\Shipment\Document;

use App\Entity\Shipment\Document\ShipmentProofDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentProofDocument>
 *
 * @method ShipmentProofDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentProofDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentProofDocument[]    findAll()
 * @method ShipmentProofDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentProofDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentProofDocument::class);
    }

//    /**
//     * @return ShipmentProofDocument[] Returns an array of ShipmentProofDocument objects
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

//    public function findOneBySomeField($value): ?ShipmentProofDocument
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
