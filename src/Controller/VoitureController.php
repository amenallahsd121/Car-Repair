<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Image;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PictureService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/voiture')]
class VoitureController extends AbstractController
{


    //////////////////////////////////////// Afficher tous les voitures //////////////////////////////////////////

    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }


    

    //////////////////////////////////////// Recharger la page des voiture selon le filtre   //////////////////////////////////////////


    // Filtrage des voitures par prix (utilisé dans une requête AJAX)
    #[Route('/filter-cars', name: 'filter_cars', methods: ['POST'])]
    public function filterCars(Request $request, VoitureRepository $voitureRepository, LoggerInterface $logger): JsonResponse
    {
        // Récupération des valeurs min et max de prix de la requête AJAX
        $minPrice = (float) $request->request->get('minPrice');
        $maxPrice = (float) $request->request->get('maxPrice');

        // Requête à la base de données pour récupérer les voitures dans la plage de prix spécifiée
        $cars = $voitureRepository->findByPriceRange($minPrice, $maxPrice);

        // Sérialisation du résultat en JSON avec les chemins d'accès aux photos inclus
        $serializedCars = [];
        foreach ($cars as $car) {
            $photoPath = null;
            if ($car->getImages()->count() > 0) {
                $photoPath = '/uploads/voiture' . '/' . $car->getImages()->first()->getName();
            }

            $logger->info('Chemin de la photo pour la voiture avec l\'ID ' . $car->getId() . ': ' . $photoPath);

            $serializedCars[] = [
                'id' => $car->getId(),
                'Titre' => $car->getTitre(),
                'imageUrl' => $photoPath, // Modifier 'photoPath' en 'imageUrl'
                // Ajouter d'autres propriétés que vous souhaitez inclure dans la réponse
            ];
        }

        return new JsonResponse($serializedCars);
    }




    //////////////////////////////////////// Ajouter voiture //////////////////////////////////////////


    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $user = $this->getUser();
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voiture->setUtilisateur($user);
            $images = $form->get('image')->getData();

            foreach ($images as $image) {
                // Définir le dossier de destination
                $folder = '';

                // Appeler le service d'ajout d'image
                $file = $pictureService->add($image, $folder);

                $img = new Image();
                $img->setName($file);
                $voiture->addImage($img);
            }

            // Persister l'entité voiture avec les images associées
            $entityManager->persist($voiture);
            $entityManager->flush();

            // Redirection vers la page d'index ou toute autre page appropriée
            return $this->redirectToRoute('app_voiture_index');
        }

        return $this->render('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }



    //////////////////////////////////////// Modifier voiture //////////////////////////////////////////


    #[Route('/{id}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voiture $voiture, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si de nouvelles images sont sélectionnées
            $images = $form->get('image')->getData();

            if (!empty($images)) {
                // Supprimer les images existantes
                foreach ($voiture->getImages() as $image) {
                    $voiture->removeImage($image);
                    $entityManager->remove($image); // Facultatif : supprimer également le fichier du stockage
                }

                // Ajouter de nouvelles images
                foreach ($images as $image) {
                    // Définir le dossier de destination
                    $folder = '';
                    // Ajouter la nouvelle image
                    $file = $pictureService->add($image, $folder);

                    $img = new Image();
                    $img->setName($file);
                    $voiture->addImage($img);
                }
            }

            // Persister les modifications
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }



    //////////////////////////////////////// Supprimer voiture //////////////////////////////////////////

    #[Route('/{id}', name: 'app_voiture_delete', methods: ['GET', 'POST'])]
    public function delete(int $id, Voiture $voiture, VoitureRepository $voitureRepository, EntityManagerInterface $entityManager): Response
    {
        $voiture = $voitureRepository->find($id);
        $entityManager->remove($voiture);
        $entityManager->flush();

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }



    //////////////////////////////////////// Afficher une voiture specifique avec ses details //////////////////////////////////////////

    #[Route('/details/{id}', name: 'voiture_details', methods: ['GET', 'POST'])]
    public function details(int $id, VoitureRepository $voitureRepository, Request $request): Response
    {
        $voiture = $voitureRepository->find($id);
        $contacts = $voiture->getFormulaireContact();

        return $this->render('voiture/details.html.twig', [
            'voiture' => $voiture,
            'contacts' => $contacts,
        ]);
    }


    //////////////////////////////////////// Afficher les contacts relatifs à la voiture affichée //////////////////////////////////////////


    #[Route('/details/contacts/{id}', name: 'contacts_details', methods: ['GET', 'POST'])]
    public function contacts(int $id, VoitureRepository $voitureRepository, Request $request): Response
    {
        $voiture = $voitureRepository->find($id);
        $contacts = $voiture->getFormulaireContact();

        return $this->render('voiture/consultecontact.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
