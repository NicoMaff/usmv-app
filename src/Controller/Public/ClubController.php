<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/club")]
class ClubController extends AbstractController
{
    #[Route("/presentation", name: "app_club_introduction")]
    public function introduction(): Response
    {
        return $this->render("public/club/introduction.html.twig");
    }

    #[Route("/acteurs", name: "app_club_contributors")]
    public function contributors(): Response
    {
        return $this->render('public/club/contributors.html.twig');
    }

    #[Route("/gymnases", name: "app_club_gym")]
    public function infos(): Response
    {
        return $this->render("public/club/gym.html.twig");
    }
}
