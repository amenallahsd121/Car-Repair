<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurController extends AbstractController
{


        //////////////////////////////////////// Afficher utilisateur //////////////////////////////////////////
        
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération du référentiel Utilisateur
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        // Récupération des utilisateurs avec le rôle ROLE_EMPLOYE
        $employeUsers = $userRepository->findBy(['roles' => 'ROLE_EMPLOYE']);
    
        // Affichage de la liste des utilisateurs employés
        return $this->render('/utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
            'employeUsers' => $employeUsers,
        ]);
    }
    


     //////////////////////////////////////// Supprimer utilisateur //////////////////////////////////////////

    #[Route('/admin/user/{id}/delete', name: 'app_delete_user')]
    public function delete(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        // Suppression de l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'User deleted successfully.');

        // Redirection vers la liste des utilisateurs
        return $this->redirectToRoute('app_utilisateur');
    }


     //////////////////////////////////////// Modifier utilisateur //////////////////////////////////////////
     
    #[Route('/admin/user/{id}/update', name: 'app_update_user')]
    public function update(Utilisateur $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Création du formulaire de modification d'utilisateur
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Si l'utilisateur a fourni un nouveau mot de passe, le hasher et le définir
            if ($form->get('plainPassword')->getData() !== null) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
    
            // Enregistrement des modifications en base de données
            $entityManager->flush();
    
            // Message flash de succès
            $this->addFlash('success', 'User updated successfully.');
    
            // Redirection vers la liste des utilisateurs
            return $this->redirectToRoute('app_utilisateur');
        }
    
        // Affichage du formulaire de modification d'utilisateur
        return $this->render('utilisateur/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }    
}
