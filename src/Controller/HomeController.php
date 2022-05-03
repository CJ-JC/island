<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('home/accueil.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
}