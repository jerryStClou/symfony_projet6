<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, VehiculeRepository $vehiculeRepository): Response
    {


        return $this->render('panier/index.html.twig', [
            'vehicule' => $vehiculeRepository->find($session->get('panier')),
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function add(SessionInterface $session, $id): Response
    {
        $session->set('panier', $id);

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/flush', name: 'app_panier_flush')]
    public function flush(SessionInterface $session, VehiculeRepository $vehiculeRepository): Response
    {
        $session->set('panier', '');
        return $this->redirectToRoute('app_panier');
    }
}
