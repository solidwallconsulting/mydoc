<?php

namespace App\Repository;

use App\Entity\PrintHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrintHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrintHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrintHistory[]    findAll()
 * @method PrintHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrintHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrintHistory::class);
    }

    // /**
    //  * @return PrintHistory[] Returns an array of PrintHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrintHistory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
