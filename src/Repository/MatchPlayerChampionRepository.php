<?php

namespace App\Repository;

use App\Entity\MatchPlayerChampion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchPlayerChampion|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchPlayerChampion|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchPlayerChampion[]    findAll()
 * @method MatchPlayerChampion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchPlayerChampionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchPlayerChampion::class);
    }

    // /**
    //  * @return MatchPlayerChampion[] Returns an array of MatchPlayerChampion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchPlayerChampion
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
