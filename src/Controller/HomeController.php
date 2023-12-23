<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'publications' => $publicationRepository->findManyResult(4)
        ]);
    }
}
