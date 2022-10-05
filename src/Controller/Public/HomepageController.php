<?php

namespace App\Controller\Public;

use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage_index')]
    public function index(ArticleRepository $articleRepo, EventRepository $eventRepo): Response
    {
        $articles = $articleRepo->findLast10();
        $events = $eventRepo->findLast5();

        return $this->render('public/homepage/index.html.twig', [
            "articles" => $articles,
            "events" => $events
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

    // #[Route("/calendrier", name: "app_homepage_fullCalendar")]
    // public function fullCalendar(EventRepository $repository): Response
    // {
    //     $events = $repository->findAll();

    //     return $this->render("public/homepage/displayCalendar.html.twig", [
    //         "events" => $events
    //     ]);
    // }

    #[Route("/liste-evenements", name: "app_homepage_listEvents")]
    public function listEvents(EventRepository $repository): Response
    {
        $events = $repository->findAll();

        return $this->render("public/homepage/listEvents.html.twig", [
            "events" => $events
        ]);
    }
}
