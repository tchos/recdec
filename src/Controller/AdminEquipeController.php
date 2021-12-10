<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminEquipeController extends AbstractController
{
    /**
     * @Route("/admin/equipe", name="admin_equipe")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $equipe = new Equipe();

        // Constructeur du formulaire de saisie des équipes
        $form = $this->createForm(EquipeType::class, $equipe);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         */
        if($form->isSubmitted() && $form->isValid())
        {
            // Persistence de l'entité ActeDeces
            $manager->persist($equipe);
            $manager->flush();

            // Alerte succès de l'enregistrement de l'acte de décès
            $this->addFlash("success", "Equipe ajoutée avec succès !");

            return $this->redirectToRoute('admin_equipe');
        }

        return $this->render('admin/equipe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
