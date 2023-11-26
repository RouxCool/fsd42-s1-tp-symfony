<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventCreateType;
use App\Form\EventUpdateType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventController extends AbstractController
{
    #[Route('/events', name: 'app_event')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findLast()
        ]);
    }

    #[Route('/events/add', name: 'app_event_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $request = Request::createFromGlobals();

        $event = new Event();
        $user = $this->getUser();
        $form = $this->createForm(EventCreateType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event');
        } else {
            return $this->render('event/add.html.twig', [
                'form' => $form,
            ]);
        }
    }

    #[Route('/events/{id}', name: 'app_event_infos')]
    public function infos(EventRepository $eventRepository, CommentRepository $commentRepository, string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $eventDetail = $eventRepository->find($id);
        $request = Request::createFromGlobals();
        if ($eventDetail === null) {
            throw $this->createNotFoundException("Cet article n'existe pas");
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEvent($eventDetail);

            $user = $this->getUser();
            $comment->setUser($user);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_infos', ['id' => $id]);
        }

        $comments = $commentRepository->findOne($eventDetail->getId());
        return $this->render('event/info.html.twig', [
            'eventDetail' => $eventDetail,
            'comments' => $comments,
            'form' => $form
        ]);
    }

    #[Route('/events/{id}/update', name: 'app_event_update')]
    public function update(EntityManagerInterface $entityManager, EventRepository $eventRepository, string $id): Response
    {
        $eventUpdate = $eventRepository->find($id);
        if ($eventUpdate->getId() !== null && $eventUpdate->getUser() !== $this->getUser()) {
            throw new AccessDeniedException("Cet article ne vous appartient pas");
        }

        $request = Request::createFromGlobals();

        $form = $this->createForm(EventUpdateType::class, $eventUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eventUpdate);
            $entityManager->flush();
            return $this->redirectToRoute('app_event_infos', ['id' => $id]);
        }

        return $this->render('event/update.html.twig', [
            'event' => $eventUpdate,
            'form' => $form
        ]);

    }
}
