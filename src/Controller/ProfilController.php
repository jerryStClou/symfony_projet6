<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Form\ProfilePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class ProfilController extends AbstractController
{

    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profil/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profil/edit/password', name: 'app_profil_edit_password')]
    public function editPassword(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(ProfilePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordHasher->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                // version plus longue
                $newPassword = $passwordHasher->hashPassword($user, $form->get('newPassword')->getData());
                $user->setPassword($newPassword);
                // version plus courte
                // $user->setPassword(
                //     $passwordHasher->hashPassword($user, $form->get('newPassword')->getData())
                // );
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_profil');
            } else {
                $this->addFlash('error', 'Old Password is incorrect');
                return $this->redirectToRoute('app_profil_edit_password');
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profil');
        }
        return $this->render('profil/editPassword.html.twig', [
            'form' => $form,
        ]);
    }
}
