<?php

namespace App\Controller;

use App\Entity\ActeDeces;
use App\Service\Paginator;
use App\Form\ActeDecesType;
use App\Service\Statistiques;
use App\Form\UpdateActeDecesType;
use App\Repository\ActeDecesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActeController extends AbstractController
{
    /**
     * Permet de voir les actes saisies par le user connecté
     * 
     * @Route("/acte/{page<\d+>?1}", name="actes_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(ActeDecesRepository $repo, $page, Paginator $paginator)
    {
        $paginator->setEntityClass(ActeDeces::class)
                  ->setUser($this->getUser())
                  ->setPage($page)
        ;
        return $this->render('acte/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * Permet d'enregistrer un nouvel acte de décès
     * 
     * @Route("acte/new", name="acte_create")
     * @IsGranted("ROLE_USER")
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
         */
        if($form->isSubmitted() && $form->isValid())
        {
            // L'agent de saisie sera le user connecté
            $acteDeces->setAgentSaisie($this->getUser());

            // Le décédé doit avoir au mois 18 ans
            $age = $acteDeces->getDateNaissance()->diff($acteDeces->getDateDeces())->format('%y');

            if ( $age < 18 )
            {
                // $form->get('dateNaissance') me donne accès au champ dateNaissance du formulaire
                $form->get('dateNaissance')->addError(new FormError("Le décédé doit avoir au moins 18 ans pour 
                    que l'acte soit enregistré !"));
            }else {

                // Persistence de l'entité ActeDeces
                $manager->persist($acteDeces);
                $manager->flush();

                // Alerte succès de l'enregistrement de l'acte de décès
                $this->addFlash("success", "Acte de décès enregistré avec succès !");

                return $this->redirectToRoute('acte_create');
            }
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteur($this->getUser());

        return $this->render("acte/create.html.twig", [
            'form' => $form->createView(),
            'compteur' => $compteur
        ]);
    }

    /**
     * Permet de modifier un acte de décès saisi
     *
     * @Route("acte/{id}/edit", name="acte_edit")
     * @IsGranted("ROLE_USER")
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

        // Le décédé doit avoir au mois 18 ans
        $age = $acteDeces->getDateNaissance()->diff($acteDeces->getDateDeces())->format('%y');

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         *
         */
        if ($form->isSubmitted() && $form->isValid())
        {
            // Le décédé doit avoir au mois 18 ans
            $age = $acteDeces->getDateNaissance()->diff($acteDeces->getDateDeces())->format('%y');

            if ( $age < 18 )
            {
                // $form->get('dateNaissance') me donne accès au champ dateNaissance du formulaire
                $form->get('dateNaissance')->addError(new FormError("Le décédé doit être agé d'au moins 18 ans pour 
                    que son acte soit enregistré !"));
            }else {
                // Persistence de l'entité ActeDeces
                $manager->persist($acteDeces);

                // Envoyer réellement la requête
                $manager->flush();

                // Alerte succès de l'enregistrement de l'acte de décès
                $this->addFlash("success", "L'acte de décès N° <strong>{$acteDeces->getNumeroActe()}</strong> a 
                été modifié avec succès !");

                return $this->redirectToRoute('acte_create');
            }
        }

        // Compteur de l'agent de saisie
        $compteur = $statistiques->getCompteur($this->getUser());

        return $this->render("acte/edit.html.twig", [
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
