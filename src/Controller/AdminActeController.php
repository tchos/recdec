<?php

namespace App\Controller;

use App\Entity\ActeDeces;
use App\Form\ActeDecesType;
use App\Form\UpdateActeDecesType;
use App\Service\Paginator;
use App\Repository\ActeDecesRepository;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminActeController extends AbstractController
{
    /**
     * Permet de lister les saisies effectuées pas l'administrateur
     * @Route("/admin/actes/{page<\d+>?1}", name="admin_acte_index")
     */
    public function index($page, Paginator $paginator, ActeDecesRepository $repository)
    {
        $paginator->setEntityClass(ActeDeces::class)
                  ->setUser($this->getUser())
                  ->setPage($page);

        return $this->render('admin/acte/index.html.twig', [
            'paginator' => $paginator,
            'actes' => $repository->findBy(['agentSaisie' => $this->getUser()])
        ]);
    }

    /**
     * Permet d'enregistrer un nouvel acte de décès
     *
     * @Route("admin/acte/new", name="admin_acte_create")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request, Statistiques $statistiques)
    {
        $acteDeces = new ActeDeces();

        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(ActeDecesType::class, $acteDeces);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if($form->isSubmitted() && $form->isValid())
        {
            // L'agent de saisie sera le user connecté
            $acteDeces->setAgentSaisie($this->getUser());

            // Persistence de l'entité ActeDeces
            $manager->persist($acteDeces);
            $manager->flush();

            // Alerte succès de l'enregistrement de l'acte de décès
            $this->addFlash("success", "Acte de décès enregistré avec succès !");

            return $this->redirectToRoute('admin_acte_create');
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteur($this->getUser());

        return $this->render("admin/acte/create.html.twig", [
            'form' => $form->createView(),
            'compteur' => $compteur
        ]);
    }

    /**
     * Permet de modifier un acte de décès saisi
     *
     * @Route("admin/acte/{id}/edit", name="admin_acte_edit")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param ActeDeces $acteDeces
     * @return Response
     */
    public function edit(EntityManagerInterface $manager, Request $request,
                         Statistiques $statistiques, ActeDeces $acteDeces)
    {

        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(UpdateActeDecesType::class, $acteDeces);

        // On met à jour la date de saisie
        $acteDeces->setDateSaisie(new \DateTime());

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if ($form->isSubmitted() && $form->isValid()) {

            // Persistence de l'entité ActeDeces
            $manager->persist($acteDeces);

            // Envoyer réellement la requête
            $manager->flush();

            // Alerte succès de l'enregistrement de l'acte de décès
            $this->addFlash("success", "L'acte de décès N° <strong>{$acteDeces->getNumeroActe()}</strong> a 
                été modifié avec succès !");

            return $this->redirectToRoute('admin_acte_create');
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteur($this->getUser());

        return $this->render("admin/acte/edit.html.twig", [
            'acteDeces' => $acteDeces,
            'form' => $form->createView(),
            'compteur' => $compteur,
        ]);
    }

    /**
     * Permet de supprimer un acte de décès
     * @IsGranted("ROLE_USER")
     *
     * @Route("acte/{id}/delete", name="acte_remove")
     *
     * @param EntityManagerInterface $manager
     * @param ActeDeces $acteDeces
     * @return void
     */
    public function delete(EntityManagerInterface $manager, ActeDeces $acteDeces)
    {
        if($this->getUser() == $acteDeces->getAgentSaisie())
        {
            $manager->remove($acteDeces);
            $manager->flush();
            $this->addFlash(
                "success",
                "L'acte de décès n° <strong>{$acteDeces->getNumeroActe()}</strong> a bien été supprimé"
            );
        } else {
            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimer l'acte de décès n° <strong>{$acteDeces->getNumeroActe()}</strong> 
                car vous n'en êtes pas l'auteur !"
            );
        }

        return $this->redirectToRoute("acte_create");
    }
}
