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

    public function getStatsFosa()
    {
        $users = $this->getUsersCount();
        $actes = $this->getDecedeCount();
        $centres = $this->getFosaCount();
        $equipes = $this->getEquipesCountFosa();

        return compact('users', 'decedes', 'morgues', 'equipes');
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
     * Retourne le nombre de décédés enregistrés
     *
     * @return Integer
     */
    public function getDecedeCount()
    {
        return $this->manager->createQuery('SELECT COUNT(d) FROM App\Entity\Decede d')
                    ->getSingleScalarResult();
    }

    /**
     * Retourne le nombre de formations sanitaires
     *
     * @return Integer
     */
    public function getFosaCount()
    {
        return $this->manager->createQuery('SELECT COUNT(m) FROM App\Entity\Morgue m')
                    ->getSingleScalarResult();
    }

    /**
     * Retourne le nombre d'équipes enregistrées en BD
     *
     * @return Integer
     */
    public function getEquipesCountFosa()
    {
        return $this->manager->createQuery('SELECT COUNT(e) FROM App\Entity\Equipe e')
                    ->getSingleScalarResult();
    }

    /**
     * Retourne les statistiques de saisies par agents de saisie
     *
     * @return User
     */
    public function getUserStatsFosa($direction)
    {
        return $this->manager->createQuery(
            'SELECT u.fullName as fullName, COUNT(d.nom) as compteur
            FROM App\Entity\User u
            JOIN u.decedes d
            GROUP BY fullName
            ORDER BY compteur '.$direction
        )
            ->getResult();
    }

    /**
     * Retourne les statistiques de saisies par agents de saisie
     */
    public function getDataCollect($direction)
    {
        return $this->manager->createQuery(
            'SELECT e.libelle as equipe, COUNT(d.nom) as compteur
            FROM App\Entity\Decede d
            JOIN d.equipe e
            GROUP BY equipe
            ORDER BY compteur '.$direction
        )
            ->getResult();
    }

    /**
     * Retourne les statistiques de saisies de l'agent de saisie
     *
     * @return Integer
     */
    public function getCompteurFosa($user)
    {
        return $this->manager->createQuery(
            'SELECT COUNT(d.nom) as compteur
            FROM App\Entity\Decede d
            JOIN d.agentSaisie u
            WHERE u = :user'
        )
            ->setParameter('user', $user)
            ->getSingleScalarResult();
    }

    /**************************************************************************************************/

    public function getStats()
    {
        $users = $this->getUsersCount();
        $actes = $this->getActesCount();
        $centres = $this->getCentresCount();
        $equipes = $this->getEquipesCount();

        return compact('users', 'actes', 'centres', 'equipes');
    }

    /**
     * Retourne le nombre d'acte de décès enregistrés
     *
     * @return Integer
     */
    public function getActesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\ActeDeces a')
            ->getSingleScalarResult();
    }

    /**
     * Retourne le nombre de formations sanitaires
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

    /**
     * Retourne les statistiques de saisies de l'agent de saisie
     *
     * @return Integer
     */
    public function getCompteur($user)
    {
        return $this->manager->createQuery(
            'SELECT COUNT(a.numeroActe) as compteur
            FROM App\Entity\ActeDeces a
            JOIN a.agentSaisie u
            WHERE u = :user'
        )
            ->setParameter('user', $user)
            ->getSingleScalarResult();
    }
}

?>