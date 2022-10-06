<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/club")]
class ClubController extends AbstractController
{
    #[Route("/acteurs", name: "app_club_contributors")]
    public function contributors(): Response
    {
        return $this->render('club/contributors.html.twig');
    }
}
