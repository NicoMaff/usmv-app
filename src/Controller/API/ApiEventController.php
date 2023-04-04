<?php

namespace App\Controller\API;

use App\Entity\Event;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class ApiEventController extends AbstractController
{
    /**
     * CREATE
     * An ADMIN can create a new event.
     * If no image is uploaded, a default image will be added to the event.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("event", "api_event_createEvent", methods: "POST")]
    public function createEvent(EventRepository $repository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, SluggerInterface $slugger): JsonResponse
    {
        // Request using multipart/form-data
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            // Request using raw Body
            $jsonReceived = $request->getContent();
        }

        // Request using multipart/form-data
        if ($request->files->get("file")) {
            $uploadedFile = $request->files->get("file");
        }

        $event = $serializer->deserialize($jsonReceived, Event::class, "json");



        if (isset($uploadedFile)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/events/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $event->setImageName($newFileName);
            $event->setImageUrl($destination . $newFileName);
        } else {
            $event->setImageName("event-default-image.png");
            $event->setImageUrl($this->getParameter("kernel.project_dir") . "/public/assets/img/events/event-default-image.jpeg");
        }

        /** Set event visibility on true by default */
        if ($event->isVisible() === NULL) {
            $event->setVisible(true);
        }

        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($event, true);
        return $this->json($event, 201);
    }

    /**
     * READ
     * An ADMIN can get an event's details from its id.
     */
    #[Route("/event/{id}", "api_event_readEvent", methods: "GET")]
    public function readEvent(EventRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200);
    }

    /**
     * READ
     * An ADMIN can get all events' details.
     */
    #[Route("/events", "api_event_readAllEvents", methods: "GET")]
    public function readAllEvents(EventRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200);
    }

    /**
     * UPDATE
     * An admin can update one event details from its id.
     * WARNING : if the user send file, the method POST is required because multipart/form-data only support POST method (and no PATCH).
     * Only one image is stored by event.
     * If a new image is uploaded, it will replace the older.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/event/{id}", "api_event_updateOne", methods: ["PATCH", "POST"])]
    public function updateOne(EventRepository $repository, Request $request, int $id, ValidatorInterface $validator, SerializerInterface $serializer, SluggerInterface $slugger): JsonResponse
    {
        // Request using multipart/form-data
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            // Request using raw Body
            $jsonReceived = $request->getContent();
        }

        // Request using multipart/form-data
        if ($request->files->get("file")) {
            $uploadedFile = $request->files->get("file");
        }

        $event = $repository->find($id);
        $updatedEvent = $serializer->deserialize($jsonReceived, Event::class, "json");

        if ($updatedEvent->getStartDate()) {
            $event->setStartDate($updatedEvent->getStartDate());
        }
        if ($updatedEvent->getEndDate()) {
            $event->setEndDate($updatedEvent->getEndDate());
        }
        if ($updatedEvent->getContent()) {
            $event->setContent($updatedEvent->getContent());
        }
        if ($updatedEvent->isVisible() !== NULL) {
            $event->setVisible($updatedEvent->isVisible());
        }
        if (isset($uploadedFile)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/events/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($event->getImageName() && file_exists($event->getImageUrl())) {
                unlink($event->getImageUrl());
            }

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $event->setImageName($newFileName);
            $event->setImageUrl($destination . $newFileName);
        }
        $event->setUpdatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($event, true);
        return $this->json($event, 201);
    }

    /**
     * PATCH
     * An ADMIN can directly toggle one article visibility 
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/event/toggle-visibility/{id}", name: "api_article_toggleVisibility", methods: "PATCH")]
    public function toggleVisibility(EventRepository $repository, int $id): JsonResponse
    {
        $event = $repository->find($id);
        $event->setVisible(!$event->isVisible());
        $repository->add($event, true);

        return $this->json($event, 201, context: ["groups" => "event:read"]);
    }

    /**
     * DELETE
     * An ADMIN can delete an event from its id.
     * If an event is deleted, its image will be also removed from the server.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/event/{id}", "api_event_deleteEvent", methods: "DELETE")]
    public function deleteEvent(EventRepository $repository, int $id): JsonResponse
    {
        $event = $repository->find($id);
        if ($event->getImageName() && file_exists($event->getImageUrl())) {
            unlink($event->getImageUrl());
        }
        $repository->remove($event, true);
        return $this->json(
            status: 200,
            data: [
                "status" => 200,
                "message" => "The event with the id #$id has been correctly deleted."
            ]
        );
    }
}
