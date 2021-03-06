<?php

namespace App\Repository;

use App\Entity\Orderslines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Orderslines|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orderslines|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orderslines[]    findAll()
 * @method Orderslines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderslinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orderslines::class);
    }

    // /**
    //  * @return Orderslines[] Returns an array of Orderslines objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orderslines
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
