<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{

     //////////////////////////////////////// Afficher services //////////////////////////////////////////

    #[Route('/service', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        // Récupération de tous les services
        $services = $serviceRepository->findAll();

        // Affichage de la liste des services
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }


         //////////////////////////////////////// Ajouter services //////////////////////////////////////////

    #[Route('/admin/service/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'utilisateur courant
        $user = $this->getUser();

        // Création d'une nouvelle instance de Service
        $service = new Service();

        // Création du formulaire
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Attribution de l'utilisateur au service
            $service->setUtilisateur($user);

            // Persistance du service et enregistrement en base de données
            $entityManager->persist($service);
            $entityManager->flush();

            // Redirection vers la liste des services
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affichage du formulaire de création de service
        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

         //////////////////////////////////////// Modifier services //////////////////////////////////////////

    #[Route('/admin/service/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire de modification
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des modifications en base de données
            $entityManager->flush();

            // Redirection vers la liste des services
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affichage du formulaire de modification
        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }


         //////////////////////////////////////// Supprimer services //////////////////////////////////////////

    #[Route('/admin/service/{id}', name: 'app_service_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        // Suppression du service
        $entityManager->remove($service);
        $entityManager->flush();

        // Redirection vers la liste des services
        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
