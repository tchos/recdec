<?php

namespace App\Controller;

use App\Service\Statistiques;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_index")
     */
    public function index(Statistiques $statistiques)
    {
        $stats = $statistiques->getStats();
        $userStats = $statistiques->getUserStats('DESC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'userStats' => $userStats
        ]);
    }
}
