<?php

namespace App\Repository;

use App\Entity\Champion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Champion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Champion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Champion[]    findAll()
 * @method Champion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Champion::class);
    }

    public function getChampionsForIds(array $ids) {
        return $this->createQueryBuilder('c')
            ->where('c.riotId IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('c.riotId', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getChampionsForIdsByLane(array $ids, int $laneId) {
        return $this->createQueryBuilder('c')
            ->join('c.lane', 'l')
            ->where('c.riotId IN (:ids)')
            ->andWhere('l.id = :lid')
            ->setParameter('ids', $ids)
            ->setParameter('lid', $laneId)
            ->orderBy('c.winRate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Champion[] Returns an array of Champion objects
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
    public function findOneBySomeField($value): ?Champion
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
