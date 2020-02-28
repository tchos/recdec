<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActeController extends AbstractController
{
    /**
     * @Route("/actes", name="actes_index")
     */
    public function index()
    {
        return $this->render('acte/index.html.twig', [
            'controller_name' => 'ActeController',
        ]);
    }
}
