<?php

namespace App\Controller;

use App\Entity\CentreEtatCivil;
use App\Form\CentreEtatCivilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCECController extends AbstractController
{
    /**
     * @Route("/admin/cec", name="add_cec")
     */
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $cec = new CentreEtatCivil();

        // Constructeur de formulaire de saisie des centres d'état civils
        $form = $this->createForm(CentreEtatCivilType::class, $cec);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if ($form->isSubmitted() && $form->isValid())
        {
            // Persistence de l'entité Arrondissement
            $manager->persist($cec);
            $manager->flush();

            // Alerte de succès pour l'ajout d'un nouveau centre d'état civil
            $this->addFlash("success", "Centre d'état civil créé avec succès !");

            // Redirection vers la page d'ajout d'un nouveau cec
            return $this->redirectToRoute('add_cec');
        }

        return $this->render('admin/cec/addcec.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
