<?php

namespace App\Repository\Chat\DM;

use App\Entity\Chat\DM\DMChatChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DMChatChannel>
 *
 * @method DMChatChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method DMChatChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method DMChatChannel[]    findAll()
 * @method DMChatChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DMChatChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DMChatChannel::class);
    }

//    /**
//     * @return DMChatChannel[] Returns an array of DMChatChannel objects
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

//    public function findOneBySomeField($value): ?DMChatChannel
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
