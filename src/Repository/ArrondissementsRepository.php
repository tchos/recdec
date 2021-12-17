<?php

namespace App\Repository;

use App\Entity\Arrondissements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Arrondissements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrondissements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrondissements[]    findAll()
 * @method Arrondissements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrondissementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrondissements::class);
    }

    // /**
    //  * @return Arrondissements[] Returns an array of Arrondissements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Arrondissements
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
