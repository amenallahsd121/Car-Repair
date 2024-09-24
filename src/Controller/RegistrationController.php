<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\AuthentificationUtilisateurAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{

       //////////////////////////////////////// Ajouter employé par admin //////////////////////////////////////////

    #[Route('/admin/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AuthentificationUtilisateurAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance d'utilisateur
        $user = new Utilisateur();

        // Création du formulaire d'inscription avec les données de l'utilisateur
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification de la soumission et de la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Attribution du rôle "ROLE_EMPLOYE" à l'utilisateur
            $user->setRoles('ROLE_EMPLOYE');

            // Hachage du mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Persistance de l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection vers la liste des utilisateurs après l'ajout réussi
            return $this->redirectToRoute('app_utilisateur');
        }

        // Affichage du formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    

       //////////////////////////////////////// Ajouter Admin //////////////////////////////////////////

    // #[Route('/registeradmin', name: 'app_registeradmin')]
    // public function registeradmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AuthentificationUtilisateurAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new Utilisateur();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user->setRoles('ROLE_ADMIN');
    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();
            

    //         return $this->redirectToRoute('app_utilisateur');
    //     }

    //     return $this->render('registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }




}
