<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RegisterController extends AbstractController{
    #[Route('/register/adduser', name: 'app_register')]
    public function registerUser(Request $request, EntityManagerInterface $entityManager, Recaptcha3Validator $recaptcha3Validator,UrlGeneratorInterface $urlGenerator): Response
    {
         // Create user entity
         $user = new User();

         // Create form
         $form = $this->createForm(RegisterType::class, $user);
 
         // Handle request
         $form->handleRequest($request);
 
         // Initialize message variable
 
         if ($form->isSubmitted()) {
            if ($form->isValid()) {
            // Get score
            $recaptchaResponse = $recaptcha3Validator->getLastResponse();
            $score = $recaptchaResponse ? $recaptchaResponse->getScore() : 0;
            // Check if user is a bot
            if ($score < 0.5) {
                $this->addFlash('danger', "L'utilisateur est un bot"); //utilisation de addflash pour éviter l'erreur de turbodriver
                return $this->redirectToRoute('app_register');
            } else {
                $entityManager->persist($user);
                $entityManager->flush();
        
                $this->addFlash('success', "Registration successful!");
                return new RedirectResponse($urlGenerator->generate('app_register'));
            }
            }else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }
 
         return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
