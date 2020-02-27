<?php

namespace App\Repository;

use App\Entity\ActeDeces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActeDeces|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActeDeces|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActeDeces[]    findAll()
 * @method ActeDeces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeDecesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActeDeces::class);
    }

    // /**
    //  * @return ActeDeces[] Returns an array of ActeDeces objects
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
    public function findOneBySomeField($value): ?ActeDeces
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
