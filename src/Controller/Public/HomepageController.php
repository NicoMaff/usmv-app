<?php

namespace App\Controller\Public;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\EventRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage_index')]
    public function index(Request $request, ArticleRepository $articleRepo, EventRepository $eventRepo, ContactRepository $contactRepo, MailerInterface $mailer): Response
    {
        if ($articleRepo->findLast10()) {
            $articles = $articleRepo->findLast10();
        }

        if ($eventRepo->findAllFromToday()) {
            $eventsReceived = $eventRepo->findAllFromToday(); // to get all events from today (today is included)
            $eventsSelected = [];

            for ($i = 0; $i < 5 && $i < count($eventsReceived); $i++) {
                array_push($eventsSelected, $eventsReceived[$i]);
            }
            // $events = array_reverse($eventsSelected); // to put newest events first
            $events = ($eventsSelected); // to put newest events first
        } else {
            $events = [];
        }

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactRepo->add($contact, true);

            $email = (new Email())
                ->sender("no-reply@villeparisisbadminton77.fr")
                // ->from($contact->getFirstName() . " " . $contact->getLastName() . " <" . $contact->getRecipient() . ">")
                // ->from(Address::create($contact->getFirstName() . " " . $contact->getLastName() . " <" . $contact->getEmail() . ">"))
                ->to($contact->getRecipient())
                ->replyTo($contact->getEmail())
                ->subject("[Site USMV Badminton] - Formulaire de contact")
                ->html(
                    "<h1 style=\"font-size: 22px; font-weight: 580;\">Formulaire de contact</h1>
                    <p>" . $contact->getMessage() . "</p>"
                );

            $mailer->send($email);

            if ($contact->getSendCopy()) {
                $copyEmail = (new Email())
                    ->from(Address::create("Ne pas répondre <no-reply@villeparisisbadminton77.fr>"))
                    ->to($contact->getEmail())
                    ->subject("[Site USMV Badminton] - Copie du formulaire de contact")
                    ->html(
                        "<h1 style=\"font-size: 22px; font-weight: 580;\">Copie du formulaire de contact</h1>
                        <h2 style=\"font-size: 18px; font-weight: 480;\">Mon message :</h2>
                        <p>" . $contact->getMessage() . "</p>"
                    );

                $mailer->send($copyEmail);
            }

            $this->addFlash("success", "Votre question / demande d'information(s) a bien été transmise !");

            return $this->redirectToRoute("app_homepage_index");
        }

        return $this->render('public/homepage/index.html.twig', [
            "form" => $form->createView(),
            "articles" => $articles,
            "events" => $events,
        ]);
    }

    #[Route("/article/{id}", name: "app_homepage_displayOneArticle")]
    public function displayOneArticle(ArticleRepository $repository, int $id): Response
    {
        $article = $repository->find($id);

        return $this->render("public/homepage/displayOneArticle.html.twig", [
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

    #[Route("/evenement/{id}", "app_homepage_displayOneEvent")]
    public function displayOnEvent(EventRepository $repository, int $id): Response
    {
        $event = $repository->find($id);

        return $this->render("public/homepage/displayOneEvent.html.twig", [
            "event" => $event
        ]);
    }

    #[Route("/evenements", name: "app_homepage_displayAllEvents")]
    public function displayAllEvents(EventRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $events = $repository->find400SortByStartDate();

        $pagination = $paginator->paginate(
            $events,
            $request->query->getInt("page", 1),
            12
        );

        return $this->render("public/homepage/displayAllEvents.html.twig", [
            "events" => $events,
            "pagination" => $pagination
        ]);
    }
}
