<?php

namespace App\Repository;

use App\Entity\Champion;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function getFavorite(Champion $champion) {
        $query = $this->createQueryBuilder('p')
                      ->select('p')
                      ->leftJoin('p.favorite', 'c')
                      ->addSelect('c.id');
 
        $query = $query
                    ->where($query->expr()->in('c', ':c'))
                    ->setParameter('c', $champion->getId())
                    ->getQuery()
                    ->getResult();

        return $query;
    }

}
