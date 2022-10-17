<?php

namespace App\Controller\Public;

use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage_index')]
    public function index(Request $request, ArticleRepository $articleRepo, EventRepository $eventRepo, ContactRepository $contactRepo, MailerInterface $mailer): Response
    {
        $articles = $articleRepo->findLast10();
        $events = $eventRepo->findLast5();

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $contactRepo->add($contact, true);

            $email = (new Email())
                ->from($contact->getEmail())
                ->to("n1code7@outlook.fr")
                // ->replyTo($contact->getEmail())
                ->subject("Formulaire de contact - Question générale")
                ->text("test")
                ->html("<strong>TEST</strong>");

            $mailer->send($email);

            $this->addFlash("success", "Votre question / demande d'information(s) a bien été transmise !");

            return $this->redirectToRoute("app_homepage_index");
        }

        return $this->render('public/homepage/index.html.twig', [
            "form" => $form->createView(),
            "articles" => $articles,
            "events" => $events,
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
        $articles = $repository->findAllAndSortDesc();

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
