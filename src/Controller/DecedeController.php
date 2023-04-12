<?php

namespace App\Controller;

use App\Entity\Decede;
use App\Form\DecedeType;
use App\Repository\DecedeRepository;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_USER for all the actions of this controller
 * @IsGranted("ROLE_USER")
 */
class DecedeController extends AbstractController
{
    /**
     * @param DecedeRepository $repository
     * @return Response
     */
    #[Route('/decede/list', name: 'decede_list')]
    public function index(DecedeRepository $repository): Response
    {
        $listeDecedeByUser = $repository->findBy(['agentSaisie' => $this->getUser()]);
        return $this->render('decede/decede_list.html.twig', [
            'listeDecedeByUser' => $listeDecedeByUser
        ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Statistiques $statistiques
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route('/decede/new', name: 'decede_new')]
    public function addDecede(EntityManagerInterface $manager, Request $request, Statistiques $statistiques)
    {
        $decede = new Decede();

        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(DecedeType::class, $decede);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         */
        if($form->isSubmitted() && $form->isValid())
        {
            // L'agent de saisie sera le user connecté
            $decede->setAgentSaisie($this->getUser());

            // Le décédé doit avoir au mois 18 ans
            $age = $decede->getNaissance()->diff($decede->getDateDeces())->format('%y');

            if ( $age < 18 )
            {
                // $form->get('dateNaissance') me donne accès au champ dateNaissance du formulaire
                $form->get('naissance')->addError(new FormError("Le décédé doit avoir au moins 18 ans pour 
                    être enregistré !"));
            }else {

                // Persistence de l'entité ActeDeces
                $manager->persist($decede);
                $manager->flush();

                // Alerte succès de l'enregistrement de l'acte de décès
                $this->addFlash("success", "Décédé enregistré avec succès !");

                return $this->redirectToRoute('decede_new');
            }
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteurFosa($this->getUser());

        return $this->render("decede/decede_create.html.twig", [
            'form' => $form->createView(),
            'compteur' => $compteur
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Statistiques $statistiques
     * @param Decede $decede
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route('/decede/{id}/edit', name: 'decede_edit')]
    public function editDecede (EntityManagerInterface $manager, Request $request,
                                Statistiques $statistiques, Decede $decede)
    {
        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(DecedeType::class, $decede);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         */
        if($form->isSubmitted() && $form->isValid()) {
            // L'agent de saisie sera le user connecté
            $decede->setAgentSaisie($this->getUser());

            // Le décédé doit avoir au mois 18 ans
            $age = $decede->getNaissance()->diff($decede->getDateDeces())->format('%y');

            if ($age < 18) {
                // $form->get('dateNaissance') me donne accès au champ dateNaissance du formulaire
                $form->get('naissance')->addError(new FormError("Le décédé doit avoir au moins 18 ans pour 
                    être enregistré !"));
            } else {

                // Persistence de l'entité ActeDeces
                $manager->persist($decede);
                $manager->flush();

                // Alerte succès de l'enregistrement de l'acte de décès
                $this->addFlash("success", "Les modifications sur le Décédé ont été enregistrés avec succès !");

                return $this->redirectToRoute('decede_list');
            }
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteurFosa($this->getUser());

        return $this->render("decede/decede_edit.html.twig", [
            'decedd' => $decede,
            'form' => $form->createView(),
            'compteur' => $compteur,
        ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Decede $decede
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route('/decede/{id}/delete', name: 'decede_delete')]
    public function deleteDecede (EntityManagerInterface $manager, Request $request, Decede $decede)
    {
        if($this->getUser() == $decede->getAgentSaisie())
        {
            $manager->remove($decede);
            $manager->flush();
            $this->addFlash(
                "success",
                "Les informations sur le décédé de <strong>{$decede->getNom()}</strong> ont bien été supprimées"
            );
        } else {
            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimer l'acte de décès n° <strong>{$acteDeces->getNumeroActe()}</strong> 
                car vous n'en êtes pas l'auteur !"
            );
        }
        return $this->redirectToRoute("decede_list");
    }

    #[Route('/decede/datacollect', name:'collecte_equipe')]
    public function resultByGroup(Statistiques $statistiques)
    {
        return $this->render("decede/datacollect.html.twig", [
            'collecte' => $statistiques->getDataCollect('DESC')
        ]);
    }
}
