<?php

namespace App\Repository;

use App\Entity\ContactPages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactPages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactPages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactPages[]    findAll()
 * @method ContactPages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactPagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactPages::class);
    }

    // /**
    //  * @return ContactPages[] Returns an array of ContactPages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactPages
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
