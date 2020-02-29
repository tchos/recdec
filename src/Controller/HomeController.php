<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(UserRepository $userRepo)
    {
        
        return $this->render('home.html.twig',[
            'bestUsers' => $userRepo->findBestUser(3),
            'worstUsers' => $userRepo->findWorstUser(3)
        ]);
    }
}
