<?php

namespace App\DataFixtures;

use App\Entity\ActeDeces;
use App\Entity\CentreEtatCivil;
use App\Entity\Equipe;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /** Variable créée pour encoder le mot de passe du user */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("Fr-fr");

        // création des équipes
        $equipes = [];
        for($i = 1; $i <= 11; $i++)
        {
            $equipe = new Equipe();
            $equipe->setLibelle('Equipe '.$i)
                   ->setResponsable($faker->name)
            ;
            $manager->persist($equipe);
            $equipes[] = $equipe; 
        }

        // création du rôle d'admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // création d'un user qui aura, en plus du rôle par défaut ('ROLE_USER'), le role d'administrateur
        $adminUser = new User();
        $adminUser->setFullName('Kwette Noumsi')
                  ->setEmail('kwette@minfi.cm')
                  ->setHash($this->encoder->encodePassword($adminUser, 'minfi'))
                  ->setEquipe($equipes[10])
                  ->addUserRole($adminRole)
        ;
        $manager->persist($adminUser);

        // création des utilisateurs
        $users = [];
        for($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->setFullName($faker->name)
                 ->setEmail($faker->email)
                 ->setHash($this->encoder->encodePassword($user, 'minfi'))
                 ->setEquipe($equipes[mt_rand(1, count($equipes) -1 )])
            ;
            $manager->persist($user);
            $users[] = $user;
        }

        // création des centres d'état civils
        for($i=0; $i < 50; $i++)
        {
            $centre = new CentreEtatCivil();
            $user = $users[mt_rand(0, count($users) -1 )];
    
            $centre->setLibelle($faker->city)
                   ->setCreatedAt($faker->dateTime('now'))
                   ->setCreatedBy($user->getFullName())
            ;
            $manager->persist($centre);

            // création des actes de décès
            for($j = 0; $j < mt_rand(20, 50); $j++)
            {
                $acte = new ActeDeces();
                $user = $users[mt_rand(0, count($users) - 1)];
                $dateDeces = $faker->dateTimeBetween('-6 years');

                $acte->setCentreEtatCivil($centre)
                     ->setNumeroActe($faker->regexify('[A-Z] + \d+{4} + / + [A-Z]{4,}'))
                     ->setFullName($faker->name)
                     ->setDateDeces($dateDeces)
                     ->setLieuDeces($faker->city)
                     ->setDateNaissance($faker->dateTimeBetween('-70 years', '-18 years'))
                     ->setLieuNaissance($faker->city)
                     ->setProfession($faker->jobTitle)
                     ->setDomicile($faker->city)
                     ->setDeclarant($faker->name)
                     ->setDateActe($faker->dateTimeBetween($dateDeces, 'now'))
                     ->setAgentSaisie($user)
                ;
                $manager->persist($acte);
            }
        }

        $manager->flush();
    }
}
