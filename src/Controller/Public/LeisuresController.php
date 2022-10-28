<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeisuresController extends AbstractController
{
    #[Route('/leisures', name: 'app_leisures_index')]
    public function index(): Response
    {
        return $this->render('public/leisures/index.html.twig');
    }
}
