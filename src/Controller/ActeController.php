<?php

namespace App\Controller;

use App\Entity\ActeDeces;
use App\Service\Paginator;
use App\Repository\ActeDecesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActeController extends AbstractController
{
    /**
     * @Route("/actes/{page<\d+>?1}", name="actes_index")
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
}
