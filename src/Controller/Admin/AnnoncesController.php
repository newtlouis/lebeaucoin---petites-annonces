<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use App\Form\CategoriesType;
use App\Repository\AnnoncesRepository;

use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/annonces", name="admin_annonces_")
 * @package App\Controller\Admin
 */

class AnnoncesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnoncesRepository $annoncesRepo): Response
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $annoncesRepo->findAll(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifAnnonce(Annonces $annonce, Request $request): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('admin_annonces_home');
        }

        return $this->render('admin/annonces/ajout.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/activer/{id}', name: 'activer')]
    public function activerAnnonce(Annonces $annonce): Response
    {
       $annonce->setActive(($annonce->getActive()) ? false : true );

       $em = $this->getDoctrine()->getManager();
       $em->persist($annonce);
       $em->flush();

       return new Response("true");
        
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimerAnnonce(Annonces $annonce): Response
    {
       $em = $this->getDoctrine()->getManager();
       $em->remove($annonce);
       $em->flush();
       $this->addFlash('message', 'L\'annonce a bien été supprimée ');

       return $this->redirectToRoute("admin_annonces_home");
        
    }
}
