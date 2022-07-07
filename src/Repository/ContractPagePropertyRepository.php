<?php

namespace App\Repository;

use App\Entity\ContractPageProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContractPageProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractPageProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractPageProperty[]    findAll()
 * @method ContractPageProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractPagePropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractPageProperty::class);
    }

    // /**
    //  * @return ContractPageProperty[] Returns an array of ContractPageProperty objects
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
    public function findOneBySomeField($value): ?ContractPageProperty
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
