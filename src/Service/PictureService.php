<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    // Méthode pour ajouter une image
    public function add(UploadedFile $picture, ?string $folder = '')
    {
        // Générer un nom de fichier unique pour l'image
        $filename = md5(uniqid(rand(), true)) . '.' . $picture->getClientOriginalExtension();

        // Récupérer le chemin du répertoire de destination depuis les paramètres
        $path = $this->params->get('images_directory') . $folder;

        // Déplacer le fichier téléchargé vers le répertoire de destination
        $picture->move($path, $filename);

        // Retourner le nom du fichier
        return $filename;
    }

    // Méthode pour supprimer une image
    public function delete(string $filename, ?string $folder = '')
    {
        // Construire le chemin complet vers le fichier image
        $path = $this->params->get('images_directory') . $folder . '/' . $filename;

        // Vérifier si le fichier existe et le supprimer
        if (file_exists($path)) {
            unlink($path);
            return true;
        }

        return false;
    }
}
