<?php

namespace App\Repository;

use App\Entity\CentreEtatCivil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CentreEtatCivil|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentreEtatCivil|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentreEtatCivil[]    findAll()
 * @method CentreEtatCivil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreEtatCivilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentreEtatCivil::class);
    }

    // /**
    //  * @return CentreEtatCivil[] Returns an array of CentreEtatCivil objects
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
    public function findOneBySomeField($value): ?CentreEtatCivil
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
