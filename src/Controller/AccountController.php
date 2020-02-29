<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet de se connecter à l'application
     * 
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter de l'application et se rediriger vers la page d'accueil
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){}

    /**
     * Permet de modifier le profile de l'utilisateur
     * 
     * @Route("account/profile", name="account_profile")
     * @Security("is_granted('ROLE_USER')")
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
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render("account/profile.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder)
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

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié !');

                // redirection vers la page d'accueil
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
