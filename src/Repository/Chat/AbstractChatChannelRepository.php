<?php

namespace App\Repository\Chat;

use App\Entity\Chat\AbstractChatChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbstractChatChannel>
 *
 * @method AbstractChatChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractChatChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractChatChannel[]    findAll()
 * @method AbstractChatChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractChatChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractChatChannel::class);
    }

    //    /**
    //     * @return AbstractChatChannel[] Returns an array of AbstractChatChannel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AbstractChatChannel
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function save(AbstractChatChannel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AbstractChatChannel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
