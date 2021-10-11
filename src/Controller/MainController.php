<?php

namespace App\Controller;

use App\Form\SearchAnnonceType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AnnoncesRepository $annoncesRepo, Request $request): Response
    {
        $annonces = $annoncesRepo->findBy(['active' => true ], ['created_at' => 'desc']);

        $form = $this->createForm(SearchAnnonceType::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonces = $annoncesRepo->search($search->get('mots')->getData());
        }

        return $this->render('main/index.html.twig', [
            'annonces' => $annonces,
            'form' => $form->createView()
        ]);
    }
}
