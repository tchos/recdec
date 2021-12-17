<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Service\Paginator;
use App\Form\AdminUserType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use App\Form\AdminPasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        //dump($error);
        // ici on capte le dernier user qui s'est connecté
        $username = $utils->getLastUsername();
        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet à l'administrateur de se déconnecter
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout(){}

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/admin/register", name="admin_account_register")
     * 
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, 
        EntityManagerInterface $manager) 
    {
        $user = new User();

        //$manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On encode le mot de passe avant de l'enregistrer dans la base de données
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            // pour la fenêtre d'alerte success confirmant la réussite de l'inscription
            $this->addFlash(
                'success',
                'Votre compte a bien été créé, vous pouvez maintenant vous connectez !'
            );

            // Redirection vers la page de connexion
            return $this->redirectToRoute('admin_account_register');
        }

        return $this->render('admin/account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de voir la liste des users inscrits
     * @Route("admin/users/{page<\d+>?1}", name="admin_user_index")
     * 
     * @return void
     */
    public function listUsers(UserRepository $repo, $page, Paginator $paginator)
    {
        // Récupération de la liste des users avec le service "Paginator"
        $paginator->setEntityClass(User::class)
                  ->setLimit(20)
                  ->setPage($page)
        ;

        return $this->render('admin/account/list.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de modifier un utilisateur
     * @Route("/admin/user/{slug}/edit", name="admin_user_edit")
     *
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 
                "Le compte de l'utilisateur <strong>{$user->getFullName()}</strong> a bien été modifié !");
            
            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('admin/account/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un user
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     *
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function remove (EntityManagerInterface $manager, User $user)
    {
        if(count($user->getActeDeces()) > 0)
        {
            // Message de suppression
            $this->addFlash(
                "warning",
                "Le compte de <strong>{$user->getFullName()}</strong> ne peut être   supprimé car
                il a déjà effectué des saisies d'actes de décès."
            );
        } else {
            // Persistence et exécution de la requête de suppression
            $manager->remove($user);
            $manager->flush();

            // Message de suppression
            $this->addFlash(
                "success",
                "Le compte de <strong>{$user->getFullName()}</strong> a bien été supprimé."
            );
        }
        
        // redirection vers la page d'affichage des users
        return $this->redirectToRoute("admin_user_index");
    }

    /**
     * Permet de modifier le mot de passe de l'administrateur
     * 
     * @Route("/admin/account/update-password", name="admin_password_update")
     * @return Response
     */
    public function updatePassword(EntityManagerInterface $manager, Request $request, 
        UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();

        // On recupère le user connecté
        $user = $this->getUser();

        // Création du formulaire de modification
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie que le oldPassword du formulaire est le même que celui du user
            // alternative à la fonction password_verify = isValidPassword du UserPasswordEncoder
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // Gestion de l'erreur
                // $form->get('oldPassword') me donne accès au champ oldPassword du formulaire
                $form->get('oldPassword')->addError(new FormError("Le mot que vous avez tapé n'est pas
                    votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié !');

                // redirection vers la page d'accueil
                return $this->redirectToRoute('admin_user_index');
            }
        }

        return $this->render('admin/account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le profile de l'administrateur
     * 
     * @Route("admin/account/profile", name="admin_account_profile")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        // On recupère les informations sur le user connecté
        $user = $this->getUser();

        // On crée le formulaire de modification des info du user
        $form = $this->createForm(AccountType::class, $user);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        // Soumission et validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render("admin/account/profile.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
