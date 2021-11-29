<?php

namespace App\Repository;

use App\Entity\MatchBannedChampion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchBannedChampion|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchBannedChampion|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchBannedChampion[]    findAll()
 * @method MatchBannedChampion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchBannedChampionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchBannedChampion::class);
    }

    // /**
    //  * @return MatchBannedChampion[] Returns an array of MatchBannedChampion objects
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
    public function findOneBySomeField($value): ?MatchBannedChampion
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
