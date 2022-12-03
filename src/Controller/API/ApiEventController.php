<?php

namespace App\Controller\API;

use App\Entity\Event;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("api/")]
// #[IsGranted("ROLE_ADMIN")]
class ApiEventController extends AbstractController
{
    /**
     * CREATE
     * Create a new event
     */
    #[Route("event", "api_event_createOne", methods: "POST")]
    public function createOne(EventRepository $repository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $event = $serializer->deserialize($jsonReceived, Event::class, "json");

        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $event->setIsVisible(true);

        $repository->add($event, true);
        return $this->json($event, 201);
    }

    /**
     * READ
     * Get a list of all events
     */
    #[Route("events", "api_event_readAll", methods: "GET")]
    public function readAll(EventRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200);
    }

    /**
     * READ
     * Get one event details from its id
     */
    #[Route("event/{id}", "api_event_readOne", methods: "GET")]
    public function readOne(EventRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200);
    }

    /**
     * UPDATE
     * Update one event details from its id
     */
    #[Route("event/{id}", "api_event_updateOne", methods: "PATCH")]
    public function updateOne(EventRepository $repository, Request $request, int $id, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $event = $repository->find($id);
        $jsonReceived = $request->getContent();
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
        if ($updatedEvent->getUrlImage()) {
            $event->setUrlImage($updatedEvent->getUrlImage());
        }
        if ($updatedEvent->isIsVisible()) {
            $event->setIsVisible($updatedEvent->isIsVisible());
        }

        $errors = $validator->validate($event);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $event->setUpdatedAt(new \DateTimeImmutable());

        $repository->add($event, true);
        return $this->json($event, 201);
    }

    /**
     * DELETE
     * Delete one event from its id
     */
    #[Route("event/{id}", "api_event_deleteOne", methods: "DELETE")]
    public function deleteOne(EventRepository $repository, int $id): JsonResponse
    {
        $repository->remove($repository->find($id));
        return $this->json(
            status: 200,
            data: [
                "status" => 200,
                "message" => "L'événement portant l'id #$id a bien été supprimé."
            ]
        );
    }
}
