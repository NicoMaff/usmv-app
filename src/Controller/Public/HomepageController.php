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
        $articles = $articleRepo->findLast10();
        $events = $eventRepo->findLast5();

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // $contactRepo->add($contact, true);

            $email = (new Email())
                ->from('hello@example.com')
                ->to('you@example.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

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

    #[Route("/email", "app_homepage_mail")]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('n1code7@outlook.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('test 3')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        // dd($email);
        $mailer->send($email);

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     // $contact = $form->getData();

        //     // $contactRepo->add($contact, true);





        //     $this->addFlash("success", "Votre question / demande d'information(s) a bien été transmise !");

        //     return $this->redirectToRoute("app_homepage_mail");
        // }

        return $this->render('public/homepage/sendEmail.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
