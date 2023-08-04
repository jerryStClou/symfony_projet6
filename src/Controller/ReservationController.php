<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Form\ReservationType;
use App\Repository\UserRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationController extends AbstractController
{
    public function __construct(protected ParameterBagInterface $parameterBag)
    {
    }
    #[Route('/reservation', name: 'app_reservation')]
    public function index(UserRepository $userRepository, Request $request, VehiculeRepository $vehiculeRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $userRepository->find($this->getUser());

        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ///////////////Envoie de l'adresse de l'user en bdd////////

            $user->setAdress($form->get('adress')->getData());
            $user->setPostalCode($form->get('postalCode')->getData());
            $user->setCity($form->get('city')->getData());

            $entityManager->persist($user);
            $entityManager->flush();
            //////////////calcule du total en fonction des dates/////////////////
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
            $interval = date_diff($startDate, $endDate);
            $days = $interval->days + 1;
            $vehicule = $vehiculeRepository->find($session->get('panier'));
            $total = $vehicule->getPrice() * $days;
            ///////////////////creation d'un tableau//////////////////////
            $reservation = [
                'endDate' => $endDate,
                'startDate' => $startDate,
                'total' => $total,
                'vehicule' => $vehicule
            ];
            /////////envoie du tableau en session///////////////

            $session->set('reservation', $reservation);
            return $this->redirectToRoute('app_payment');
        }
        return $this->render('reservation/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/payment', name: 'app_payment')]
    public function payment(SessionInterface $session)
    {
        $stripeKey = $this->parameterBag->get('stripeSecret');
        $reservation = $session->get('reservation');

        $stripe = new StripeClient($stripeKey);

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $reservation['vehicule']->getBrand() . '-' . $reservation['vehicule']->getModel() . '-' . $reservation['vehicule']->getYear(),
                    ],

                    'unit_amount' => $reservation['total'] * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($checkout_session->url, 303);
    }

    #[Route('/success', name: 'app_success')]
    public function appSuccess()
    {
        return $this->render('reservation/success.html.twig');
    }


    #[Route('/cancel', name: 'app_cancel')]
    public function appCancel()
    {
        return $this->render('reservation/cancel.html.twig');
    }
}
