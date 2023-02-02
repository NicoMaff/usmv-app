<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class AdminHomeController extends AbstractController
{
    #[Route('/home', name: 'app_admin_home_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminHomeController',
        ]);
    }

    #[Route("/articles", name: "app_admin_home_articles")]
    public function articles(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render("admin/articles.html.twig", [
            "articles" => $articles
        ]);
    }
}
