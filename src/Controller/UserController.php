<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/user/annonces/ajout', name: 'user_annonces_ajout')]
    public function ajoutAnnonce(Request $request): Response
    {

        $annonce = new Annonces;

        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonce->setUser($this->getUser());
            $annonce->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute("user");
        }

        return $this->render('user/annonces/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
