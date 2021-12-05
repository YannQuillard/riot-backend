<?php

namespace App\Repository;

use App\Entity\BestMatchComposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BestMatchComposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method BestMatchComposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method BestMatchComposition[]    findAll()
 * @method BestMatchComposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BestMatchCompositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BestMatchComposition::class);
    }
}
