<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Permet de voir les meilleurs agents de saisie
     *
     * @param Integer $limit
     * @return void
     */
    public function findBestUser($limit)
    {
        return $this->createQueryBuilder('u')
            ->select('u.fullName as fullName, COUNT(a.numeroActe) as compteur, e.libelle as libelle')
            ->join('u.acteDeces', 'a')
            ->join('u.equipe', 'e')
            ->groupBy('u')
            ->orderBy('compteur', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findWorstUser($limit)
    {
        return $this->createQueryBuilder('u')
            ->select('u.fullName as fullName, COUNT(a.numeroActe) as compteur, e.libelle as libelle')
            ->join('u.acteDeces', 'a')
            ->join('u.equipe', 'e')
            ->groupBy('u')
            ->orderBy('compteur', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
