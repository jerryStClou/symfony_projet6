<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
    #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/vehicule/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {


        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photos = $form->get('photo')->getData();
            foreach ($photos as $photo) {

                $nomFichier = uniqid() . '.' . $photo->guessExtension();

                $photo->move('images/vehicule', $nomFichier);

                $photo = new Photo();
                $photo->setReference($nomFichier);
                $vehicule->addPhoto($photo);

                $entityManager->persist($photo);
            }
            $entityManager->persist($vehicule);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $form->get('photo')->getData();
            if ($photos != []) {
                // Supprimer les anciennes photos associées au véhicule
                foreach ($vehicule->getPhoto() as $photo) {
                    // supprimer les fichiers physiques liés aux photos ici s'ils sont stockés sur le serveur.

                    $nom = $photo->getReference();
                    unlink("images/vehicule/" . $nom);
                    $entityManager->remove($photo);
                }
                foreach ($photos as $photo) {
                    $fichier = uniqid() . '.' . $photo->guessExtension();
                    $photo->move(
                        "images/vehicule/",
                        $fichier
                    );
                    $nouvellePhoto = new Photo();
                    $nouvellePhoto->setReference($fichier);
                    $vehicule->addPhoto($nouvellePhoto);
                    $entityManager->persist($nouvellePhoto);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vehicule->getId(), $request->request->get('_token'))) {
            $photos = $vehicule->getPhoto();
            foreach ($photos as $photo) {
                $entityManager->remove($photo);
                $nom = $photo->getReference();
                unlink("images/vehicule/" . $nom);
            }
            $entityManager->remove($vehicule);
            $entityManager->flush();
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}
