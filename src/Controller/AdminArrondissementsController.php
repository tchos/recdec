<?php

namespace App\Controller;

use App\Entity\Arrondissements;
use App\Service\Paginator;
use App\Form\ArrondissementsType;
use App\Repository\ArrondissementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminArrondissementsController extends AbstractController
{
    /**
     * Permet de voir la liste des arrondissements
     *
     * @Route("admin/arrondissement/{page<\d+>?1}", name="liste_arrondissement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ArrondissementsRepository $repo, $page, Paginator $paginator)
    {
        $paginator->setEntityClass(Arrondissements::class)
            ->setUser($this->getUser())
            ->setPage($page)
        ;

        return $this->render('admin/arrondissements/addcec.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * Permet d'ajouter de nouveaux arrondissements
     *
     * @Route("admin/arrondissements", name="add_arrondissements")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $arrondissement = new Arrondissements();

        // Constructeur de formulaire de saisie des arrondissements
        $form = $this->createForm(ArrondissementsType::class, $arrondissement);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if($form->isSubmitted() && $form->isValid())
        {
            // Persistence de l'entité Arrondissement
            $manager->persist($arrondissement);
            $manager->flush();

            // Alerte succès de l'enregistrement de l'acte de décès
            $this->addFlash("success", "Arrondissement ajouté avec succès !");

            return $this->redirectToRoute('add_arrondissements');
        }

        return $this->render('admin/arrondissements/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de modifier le libellé d'un arrondissement saisi
     *
     * @Route("/admin/arrondissement/{id}/edit", name="arrondissement_edit")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Arrondissements $arrondissement
     * @return Response
     */
    public function edit(EntityManagerInterface $manager, Request $request, $arrondissement)
    {
        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(ArrondissementsType::class, $arrondissement);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if ($form->isSubmitted() && $form->isValid()) {

            // Persistence de l'entité ActeDeces
            $manager->persist($arrondissement);

            // Envoyer réellement la requête
            $manager->flush();

            // Alerte succès de l'enregistrement de l'acte de décès
            $this->addFlash("success", "L'arrondissement N° <strong>{$arrondissement->getId()}</strong> a 
                été modifié avec succès !");

            return $this->redirectToRoute('add_arrondissements');
        }

        return $this->render("admin/arrondissements/edit.html.twig", [
            'arrondissement' => $arrondissement,
            'form' => $form->createView(),
        ]);
    }
}
