<?php

namespace App\Repository;

use App\Entity\CompositionChampion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompositionChampion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompositionChampion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompositionChampion[]    findAll()
 * @method CompositionChampion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompositionChampionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompositionChampion::class);
    }

    // /**
    //  * @return CompositionChampion[] Returns an array of CompositionChampion objects
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
    public function findOneBySomeField($value): ?CompositionChampion
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
