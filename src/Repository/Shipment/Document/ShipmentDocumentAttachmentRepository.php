<?php

namespace App\Repository\Shipment\Document;

use App\Entity\Shipment\Document\ShipmentDocumentAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipmentDocumentAttachment>
 *
 * @method ShipmentDocumentAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipmentDocumentAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipmentDocumentAttachment[]    findAll()
 * @method ShipmentDocumentAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipmentDocumentAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipmentDocumentAttachment::class);
    }

//    /**
//     * @return ShipmentDocumentAttachment[] Returns an array of ShipmentDocumentAttachment objects
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

//    public function findOneBySomeField($value): ?ShipmentDocumentAttachment
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
