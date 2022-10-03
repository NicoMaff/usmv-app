<?php

namespace App\Controller\Public;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function displayAllArticles(ArticleRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $repository->findAll();

        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt("page", 1),
            12
        );

        return $this->render("public/homepage/displayAllArticles.html.twig", [
            "articles" => $articles,
            "pagination" => $pagination
        ]);
    }
}


// $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([], ['created_at' => 'desc']);

// $articles = $paginator->paginate(
//     $donnees, // Requête contenant les données à paginer (ici nos articles)
//     $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//     6 // Nombre de résultats par page
// );

// return $this->render('articles/index.html.twig', [
//     'articles' => $articles,
// ]);
