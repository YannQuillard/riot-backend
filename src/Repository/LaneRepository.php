<?php

namespace App\Repository;

use App\Entity\Lane;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lane|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lane|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lane[]    findAll()
 * @method Lane[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lane::class);
    }

}
