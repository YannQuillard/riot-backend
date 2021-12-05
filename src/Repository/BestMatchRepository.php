<?php

namespace App\Repository;

use App\Entity\BestMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BestMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method BestMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method BestMatch[]    findAll()
 * @method BestMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BestMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BestMatch::class);
    }
}
