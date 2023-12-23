<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Comment;
use App\Form\PublicationCreateType;
use App\Form\PublicationUpdateType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PublicationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PublicationController extends AbstractController
{
    #[Route('/publications', name: 'app_publication')]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findLast()
        ]);
    }

    #[Route('/publications/add', name: 'app_publication_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $request = Request::createFromGlobals();

        $publication = new Publication();
        $user = $this->getUser();
        $form = $this->createForm(PublicationCreateType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setUser($user);
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication');
        } else {
            return $this->render('publication/add.html.twig', [
                'form' => $form,
            ]);
        }
    }

    #[Route('/publications/{id}', name: 'app_publication_infos')]
    public function infos(PublicationRepository $publicationRepository, CommentRepository $commentRepository, string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $publicationDetail = $publicationRepository->find($id);
        $request = Request::createFromGlobals();
        if ($publicationDetail === null) {
            throw $this->createNotFoundException("Cet article n'existe pas");
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPublication($publicationDetail);

            $user = $this->getUser();
            $comment->setUser($user);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_infos', ['id' => $id]);
        }

        $comments = $commentRepository->findOne($publicationDetail->getId());
        return $this->render('publication/info.html.twig', [
            'publicationDetail' => $publicationDetail,
            'comments' => $comments,
            'form' => $form
        ]);
    }

    #[Route('/publications/{id}/update', name: 'app_publication_update')]
    public function update(EntityManagerInterface $entityManager, PublicationRepository $publicationRepository, string $id): Response
    {
        $publicationUpdate = $publicationRepository->find($id);
        if ($publicationUpdate->getId() !== null && $publicationUpdate->getUser() !== $this->getUser()) {
            throw new AccessDeniedException("Cet article ne vous appartient pas");
        }

        $request = Request::createFromGlobals();

        $form = $this->createForm(PublicationUpdateType::class, $publicationUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publicationUpdate);
            $entityManager->flush();
            return $this->redirectToRoute('app_publication_infos', ['id' => $id]);
        }

        return $this->render('publication/update.html.twig', [
            'publication' => $publicationUpdate,
            'form' => $form
        ]);

    }
}
