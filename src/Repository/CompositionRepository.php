<?php

namespace App\Repository;

use App\Entity\Composition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Composition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Composition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Composition[]    findAll()
 * @method Composition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Composition::class);
    }


    public function getBestCompositionChampions(string $hash, array $pickArray, array $banHash, int $max) {
            $compo =  $this->createQueryBuilder('c')
                ->where('c.hash LIKE :hash')
                ->setParameter('hash', $hash)
            ;

            foreach ($pickArray as $index => $pick) {
                $compo
                    ->andWhere("c.hash LIKE :hash$index")
                    ->setParameter("hash$index", $pick)
                ;
            }

            foreach ($banHash as $index => $ban) {
                $compo
                    ->andWhere("c.hash NOT LIKE :banHash$index")
                    ->setParameter("banHash$index", $ban)
                ;
            }

            if($max !== 0) {
                $compo->setMaxResults($max);
            }

            return $compo
                ->orderBy('c.winRate', 'DESC')
                ->getQuery()
                ->getResult()
            ;
    }

    // /**
    //  * @return Composition[] Returns an array of Composition objects
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
    public function findOneBySomeField($value): ?Composition
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
