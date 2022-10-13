<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MembershipController extends AbstractController
{
    #[Route('/adhesions', name: 'app_membership_index')]
    public function index(): Response
    {
        return $this->render('public/membership/index.html.twig');
    }
}
