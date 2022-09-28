<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage_index')]
    public function index(): Response
    {
        return $this->render('public/homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
