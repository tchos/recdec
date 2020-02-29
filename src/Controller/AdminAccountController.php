<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
            return $this->redirectToRoute('account_login');
        }
        return $this->render('admin/account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
