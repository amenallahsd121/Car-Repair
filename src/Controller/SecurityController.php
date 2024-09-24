<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\TemoignageRepository;

class SecurityController extends AbstractController
{

    
   //////////////////////////////////////// Route de la page d'accueil + Envoi des témoignages //////////////////////////////////////////

    #[Route('/accueil', name: 'accueil_app')]
    public function index(TemoignageRepository $temoignageRepository): Response
    {
        // Redirection vers la page de profil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('profil_app');
        }

        // Récupération de tous les témoignages
        $temoignages = $temoignageRepository->findAll();

        // Affichage de la page d'accueil avec les témoignages
        return $this->render('accueil.html.twig', [
            'temoignages' => $temoignages,
        ]);
    }



   //////////////////////////////////////// Route vers la page du profil connecté //////////////////////////////////////////

    #[Route('/profil', name: 'profil_app')]
    public function profil(): Response
    {
        // Affichage de la page du profil
        return $this->render('profil.html.twig', []);
    }



    //////////////////////////////////////// Route de la page de connexion //////////////////////////////////////////

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirection vers la page de profil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('profil_app');
        }

        // Récupération d'éventuelles erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Récupération du dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affichage de la page de connexion avec les données nécessaires
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



   //////////////////////////////////////// Route de déconnexion //////////////////////////////////////////

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
