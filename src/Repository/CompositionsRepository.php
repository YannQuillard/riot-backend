<?php

namespace App\Repository;

use App\Entity\Compositions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compositions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compositions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compositions[]    findAll()
 * @method Compositions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompositionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compositions::class);
    }

    // /**
    //  * @return Compositions[] Returns an array of Compositions objects
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
    public function findOneBySomeField($value): ?Compositions
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
