<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="app_offre_index", methods={"GET"})
     */
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_offre_new", methods={"GET", "POST"})
     */
    function new (Request $request, OffreRepository $offreRepository): Response {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->add($offre);

            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move($this->getParameter('images_directory'), $fichier);

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $offre->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_offre_show", methods={"GET"})
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_offre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->add($offre);
            return $this->redirectToRoute(
                'app_offre_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_offre_delete", methods={"POST"})
     */
    public function delete(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $offre->getId(),
                $request->request->get('_token')
            )
        ) {
            $offreRepository->remove($offre);
        }

        return $this->redirectToRoute(
            'app_offre_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    /**
     * @Route("/supprime/image/{id}", name="offre_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

}