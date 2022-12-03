<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api")]
// #[IsGranted("ROLE_MEMBER")]
class ApiTournamentController extends AbstractController
{
    #[Route('/api/tournament', name: 'app_api_tournament')]
    public function index(): Response
    {
        return $this->render('api_tournament/index.html.twig', [
            'controller_name' => 'ApiTournamentController',
        ]);
    }
}
