<?php

namespace App\Controller\Public;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage_index')]
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findLast10();

        return $this->render('public/homepage/index.html.twig', [
            "articles" => $articles,
        ]);
    }

    #[Route("/article/{id}", name: "app_homepage_displayArticle")]
    public function displayArticle(ArticleRepository $repository, int $id): Response
    {
        $article = $repository->find($id);

        return $this->render("public/homepage/displayArticle.html.twig", [
            "article" => $article,
        ]);
    }

    #[Route("/articles", name: "app_homepage_displayAllArticles")]
    public function displayAllArticles(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render("public/homepage/displayAllArticles.html.twig", [
            "articles" => $articles
        ]);
    }
}
