<?php

namespace App\Controller;

use App\Entity\ActeDeces;
use App\Service\Paginator;
use App\Repository\ActeDecesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminActeController extends AbstractController
{
    /**
     * Permet de lister les saisies effectuées pas l'administrateur
     * @Route("/admin/actes/{page<\d+>?1}", name="admin_acte_index")
     */
    public function index($page, Paginator $paginator)
    {
        $paginator->setEntityClass(ActeDeces::class)
                  ->setUser($this->getUser())
                  ->setPage($page);

        return $this->render('admin/acte/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }
}
