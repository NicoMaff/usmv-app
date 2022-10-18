<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/jeunes")]
class YouthsController extends AbstractController
{
    #[Route("/infos", "app_youths_infos")]
    public function infos(): Response
    {
        return $this->render('/public/youths/infos.html.twig');
    }

    #[Route("/ecole-de-badminton", "app_youths_school")]
    public function school(): Response
    {
        return $this->render("public/youths/school.html.twig");
    }

    #[Route("/section-sportive", "app_youths_sportSection")]
    public function sportSection(): Response
    {
        return $this->render("public/youths/sportSection.html.twig");
    }

    #[Route("/demande-inscription-tournois", "app_youths_tournamentForm")]
    public function tournamentForm(): Response
    {
        return $this->render("public/youths/tournamentForm.html.twig");
    }

    #[Route("/liste-competitions", "app_youths_tournamentsList")]
    public function tournamentsList(): Response
    {
        return $this->render("public/youths/tournamentsList.html.twig");
    }
}
