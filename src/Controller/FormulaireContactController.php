<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormulaireContactType;
use App\Entity\FormulaireContact;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;


class FormulaireContactController extends AbstractController
{


    //////////////////////////////////////// Afficher Contact //////////////////////////////////////////

    #[Route('/formulaire/contact/{id}', name: 'app_formulaire_contact')]
    public function formulaireContact(int $id, Request $request, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la voiture en fonction de son ID puisque l'ensemble des contacts sont liés a une voiture 
        // donc au moment de l'ajout on doit presiser la voiture que le client va nous contacter pour s'informer sur la quelle  
        $voiture = $voitureRepository->find($id);

        // Créer une nouvelle instance de FormulaireContact
        $formulaireContact = new FormulaireContact();

        // Créer le formulaire
        $form = $this->createForm(FormulaireContactType::class, $formulaireContact);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Définir la voiture pour le formulaire de contact
            $formulaireContact->setVoiture($voiture);

            // Persister et flush le formulaire de contact

            // persist : represente un ordre envoyé par Doctrine (ORM du symfony) d'initialiser l'entité pour l envoie au base de données 
            $entityManager->persist($formulaireContact);
            // flush : ordre d'ecriture en base
            $entityManager->flush();

            // Rediriger vers la page d'accueil
            return $this->redirectToRoute('accueil_app');
        }

        return $this->render('formulaire_contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


       //////////////////////////////////////// Supprimer contact //////////////////////////////////////////

    #[Route('/formulaire/contact/delete/{id}', name: 'delete_formulaire_contact', methods: ['GET', 'POST'])]
    public function deleteFormulaireContact($id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le formulaire de contact en fonction de son ID
        $formulaireContact = $entityManager->getRepository(FormulaireContact::class)->find($id);
        
        // Vérifier si le formulaire de contact existe
        if (!$formulaireContact) {
            throw $this->createNotFoundException('Formulaire de contact introuvable');
        }
    
        // Récupérer l'ID de la voiture associée au formulaire de contact
        $voitureId = $formulaireContact->getVoiture()->getId();
        
        // Supprimer le formulaire de contact
        $entityManager->remove($formulaireContact);
        $entityManager->flush();
    
        // Rediriger vers les détails de la voiture associée au formulaire de contact supprimé
        return $this->redirectToRoute('voiture_details', ['id' => $voitureId]);
    }

    
}
