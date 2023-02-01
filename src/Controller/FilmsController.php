<?php

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class FilmsController extends AbstractController
{
    #[Route('/films', name: 'app_films')]
    public function createFilm(Request $request, ManagerRegistry $doctrine): Response
    {

        $film = new Films();
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirect('/');
        }

        return $this->render('films/index.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/films_list', name: 'app_films_list')]
    public function listFilms(ManagerRegistry $doctrine): Response
        {
            $films = $doctrine->getManager()
                ->getRepository(Films::class)
                ->findAll();

            return $this->render('films/list.html.twig', [
                'films' => $films,
            ]);
        }
}
