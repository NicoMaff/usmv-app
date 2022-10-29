<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnersController extends AbstractController
{
    #[Route('/partners', name: 'app_partners_index')]
    public function index(): Response
    {
        return $this->render('public/partners/index.html.twig');
    }
}
