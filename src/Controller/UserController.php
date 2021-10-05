<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            foreach($images as $image){
                $ficher = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $ficher);

                $img = new Images();
                $img->setName($ficher);
                $annonce->addImage($img);
            }

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

    #[Route('/user/profil/modifier', name: 'user_profil_modifier')]
    public function editProfil(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute("user");
        }

        return $this->render('user/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/pass/modifier', name: 'user_pass_modifier')]
    public function editPass(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Le mot de passe a bien été mis à jour');

                return $this->redirectToRoute('user');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('user/editPass.html.twig');
    }
}
