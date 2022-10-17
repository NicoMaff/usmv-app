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
}
