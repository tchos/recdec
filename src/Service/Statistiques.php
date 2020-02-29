<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Statistiques
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $users = $this->getUsersCount();
        $actes = $this->getActesCount();
        $centres = $this->getCentresCount();
        $equipes = $this->getEquipesCount();

        return compact('users', 'actes', 'centres', 'equipes');
    }

    /**
     * Retourne le nombre de users inscrits
     *
     * @return Integer
     */
    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    /**
     * Retourne le nombre d'annonces enregistrées
     *
     * @return Integer
     */
    public function getActesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\ActeDeces a')
                    ->getSingleScalarResult();
    }

    /**
     * Retourne le nombre de réservations effectuées
     *
     * @return Integer
     */
    public function getCentresCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\CentreEtatCivil c')
                    ->getSingleScalarResult();
    }

    /**
     * Retourne le nombre de commentaires effectuées
     *
     * @return Integer
     */
    public function getEquipesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(e) FROM App\Entity\Equipe e')
                    ->getSingleScalarResult();
    }
    
    /**
     * Retourne les statistiques de saisies par agents de saisie
     *
     * @return User
     */
    public function getUserStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT u.fullName as fullName, COUNT(a.numeroActe) as compteur, e.libelle as libelle
            FROM App\Entity\User u
            JOIN u.acteDeces a
            JOIN u.equipe e
            GROUP BY fullName
            ORDER BY compteur '.$direction
        )
            ->getResult();
    }
}

?>