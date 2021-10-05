<?php

namespace App\Controller;

use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AnnoncesRepository $annoncesRepo): Response
    {
        return $this->render('main/index.html.twig', [
            'annonces' => $annoncesRepo->findBy(['active' => true ], ['created_at' => 'desc']),
        ]);
    }
}
