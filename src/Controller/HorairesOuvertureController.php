<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\HorairesOuverture;
use App\Form\HorairesOuvertureType;
use App\Repository\HorairesOuvertureRepository;

class HorairesOuvertureController extends AbstractController
{

    //////////////////////////////////////// Ajouter Horaires d'Ouverture //////////////////////////////////////////

    #[Route('admin/horaires-ouverture/ajouter', name: 'ajouter_horaires_ouverture')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuel pour quand ajouter des horaires on peut savoir l'utilisateur lié a l'ajout de chaque horaires
        $user = $this->getUser();
        
        // Créer une nouvelle instance d'HorairesOuverture
        $horairesOuverture = new HorairesOuverture();
        
        // Créer le formulaire
        $form = $this->createForm(HorairesOuvertureType::class, $horairesOuverture);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Définir l'utilisateur pour les horaires d'ouverture
            $horairesOuverture->setUtilisateur($user);
            
            // Persister et flush les horaires d'ouverture
            $entityManager->persist($horairesOuverture);
            $entityManager->flush();

            // Rediriger vers la page d'affichage des horaires d'ouverture
            return $this->redirectToRoute('horaires_ouverture');
        }

        return $this->render('horaires_ouverture/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    //////////////////////////////////////// Afficher Horaires d'Ouverture //////////////////////////////////////////

    #[Route('/horaires-ouverture', name: 'horaires_ouverture')]
    public function index(HorairesOuvertureRepository $horairesOuvertureRepository): Response
    {

        return $this->render('horaires_ouverture/afficher.html.twig', [
            'horairesOuvertures' => $horairesOuvertureRepository->findAll(),
        ]);
    }


    //////////////////////////////////////// Modifier Horaires d'Ouverture //////////////////////////////////////////

    #[Route('admin/horaires-ouverture/{id}', name: 'modifier_horaires_ouverture')]
    public function update(Request $request, HorairesOuverture $horairesOuverture, EntityManagerInterface $entityManager): Response
    {

        // Créer le formulaire de modification
        $form = $this->createForm(HorairesOuvertureType::class, $horairesOuverture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour les horaires d'ouverture
            $entityManager->flush();

            // Rediriger vers la page d'affichage des horaires d'ouverture
            return $this->redirectToRoute('horaires_ouverture');
        }

        return $this->render('horaires_ouverture/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    //////////////////////////////////////// Supprimer Horaires d'Ouverture //////////////////////////////////////////

    #[Route('admin/horaires-ouverture/{id}/supprimer', name: 'supprimer_horaires_ouverture')]
    public function delete(Request $request, HorairesOuverture $horairesOuverture, EntityManagerInterface $entityManager): Response
    {
        // Supprimer les horaires d'ouverture
        $entityManager->remove($horairesOuverture);
        $entityManager->flush();

        // Rediriger vers la page d'affichage des horaires d'ouverture
        return $this->redirectToRoute('horaires_ouverture');
    }
}
