<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competitions')]
class CompetitionsController extends AbstractController
{
    #[Route("/infos", "app_competitions_infos")]
    public function infos(): Response
    {
        return $this->render('public/competitions/infos.html.twig');
    }

    #[Route("/entrainements", "app_competitions_trainings")]
    public function trainings(): Response
    {
        return $this->render('public/competitions/trainings.html.twig');
    }

    #[Route("/tournois-badtour", "app_competitions_badtour")]
    public function badtour(): Response
    {
        return $this->render('public/competitions/badtour.html.twig');
    }

    #[Route("/interclubs", "app_competitions_interclubs")]
    public function interclubs(): Response
    {
        return $this->render('public/competitions/interclubs.html.twig');
    }
}
