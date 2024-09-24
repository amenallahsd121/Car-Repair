<?php

namespace App\Controller;

use App\Entity\Temoignage;
use App\Form\TemoignageType;
use App\Repository\TemoignageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemoignageController extends AbstractController
{

         //////////////////////////////////////// Afficher temoignages //////////////////////////////////////////

    #[Route('/employe/temoignage', name: 'app_temoignage_index', methods: ['GET'])]
    public function index(TemoignageRepository $temoignageRepository): Response
    {
        // Récupération de tous les témoignages
        $temoignages = $temoignageRepository->findAll();

        // Affichage de la liste des témoignages
        return $this->render('temoignage/index.html.twig', [
            'temoignages' => $temoignages,
        ]);
    }



       //////////////////////////////////////// Ajouter temoignages //////////////////////////////////////////

    #[Route('/temoignage/new', name: 'app_temoignage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de Temoignage
        $temoignage = new Temoignage();

        // Création du formulaire de création de témoignage
        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de la note depuis la requête
            $note = $request->request->get('note');
            // Vérification si la note est vide, la fixer à 0 (si 0 le temoignage est en etat non approuvé)
            if (empty($note)) {
                $note = 0;
            }
            // Attribution de la note au témoignage
            $temoignage->setNote((int)$note);

            // Si l'état du témoignage est null, le fixer à 0
            if ($temoignage->getEtat() === null) {
                $temoignage->setEtat(0);
            }

            // Persistance du témoignage et enregistrement en base de données
            $entityManager->persist($temoignage);
            $entityManager->flush();

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('accueil_app', [], Response::HTTP_SEE_OTHER);
        }

        // Affichage du formulaire de création de témoignage
        return $this->render('temoignage/new.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
        ]);
    }



    //////////////////////////////////////// Modifier temoignages //////////////////////////////////////////

    #[Route('/employe/temoignage/{id}/edit', name: 'app_temoignage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Temoignage $temoignage, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire de modification de témoignage
        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des modifications en base de données
            $entityManager->flush();

            // Redirection vers la liste des témoignages
            return $this->redirectToRoute('app_temoignage_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affichage du formulaire de modification de témoignage
        return $this->render('temoignage/edit.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
        ]);
    }


             //////////////////////////////////////// Supprimer temoignages //////////////////////////////////////////

    #[Route('/employe/temoignage/{id}', name: 'app_temoignage_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Temoignage $temoignage, EntityManagerInterface $entityManager): Response
    {
        // Suppression du témoignage
        $entityManager->remove($temoignage);
        $entityManager->flush();

        // Redirection vers la liste des témoignages
        return $this->redirectToRoute('app_temoignage_index', [], Response::HTTP_SEE_OTHER);
    }



         //////////////////////////////////////// Approuver temoignages //////////////////////////////////////////

    #[Route('/employe/temoignage/temoignage/{id}/accept', name: 'app_temoignage_accept', methods: ['GET'])]
    public function accept(Temoignage $temoignage, EntityManagerInterface $entityManager): Response
    {
        // Modification de l'état du témoignage à accepté
        $temoignage->setEtat(1);
        $entityManager->flush();

        // Redirection vers la liste des témoignages
        return $this->redirectToRoute('app_temoignage_index');
    }
}
