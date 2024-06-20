<?php

namespace App\Helpers;

use Exception;

class Upload
{
    public const uploadDir = __DIR__ . '/../../public/uploads/';

    public static function updateImagePathsInHtml($html, $nouveauChemin)
    {
        return preg_replace_callback(
            '/(<img[^>]+src=")([^"]+)("[^>]*>)/i',
            function ($matches) use ($nouveauChemin) {
                // Extraction du nom de fichier depuis l'ancien chemin
                $nomFichier = basename($matches[2]);
                // Construction du nouveau chemin avec le nom de fichier
                $nouveauSrc = $nouveauChemin . $nomFichier;
                // Retourne la balise img avec le nouveau chemin
                return $matches[1] . $nouveauSrc . $matches[3];
            },
            $html
        );
    }

    public static function endStringWithSlash($string)
    {
        if (mb_substr($string, -1) == '/') {
            return $string;
        }
        return $string . '/';
    }

    public static function createDirectory($folder)
    {
        if (!file_exists($folder)) {
            if (!mkdir($folder, 0777, true)) {
                throw new Exception("Impossible de créer le dossier");
            }
        }
    }

    public static function moveTempFiles($data, $folder)
    {
        if (is_string($data)) {
            // Si c'est une chaîne, décodez-la en tableau
            $dataTable = json_decode($data, true);
        } elseif (is_array($data)) {
            // Si c'est déjà un tableau, utilisez-le directement
            $dataTable = $data;
        } else {
            // Si le format est incorrect, retournez sans rien faire
            return;
        }

        if ($dataTable === null) {
            return;
        }
        // Now you can process the dataTable array as needed
        $tempDir = self::uploadDir . 'temp/';
        $destDir = self::uploadDir . $folder;
        foreach ($dataTable as $row) {
            self::moveFile($row['name'], $tempDir, $destDir);
        }
    }

    public static function moveFile($filename, $fromFolder, $toFolder)
    {
        $source = Upload::endStringWithSlash($fromFolder) . $filename;
        $destination = Upload::endStringWithSlash($toFolder) . $filename;

        if (!file_exists($toFolder)) {
            if (!mkdir($toFolder, 0777, true)) {
                throw new Exception("Impossible de créer le dossier de destination.");
            }
        }

        return rename($source, $destination);
    }

    public function quillImageUpload()
    {
        try {
            $destinationFolder = $_POST['destinationFolder'];
            $newFilename = self::uploadImage($_FILES['image'], $destinationFolder);
            $fileUrl = '/uploads/' . self::endStringWithSlash($destinationFolder) . $newFilename;
            echo json_encode(['success' => true, 'file' => $fileUrl, 'filename' => $newFilename]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public static function uploadImage(array $image, $destinationFolder)
    {
        if (!isset($image) || ($image['error'] != 0)) {
            throw new Exception("No file uploaded or there was an upload error");
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Vérifier si le fichier est une image
        $mime_type = mime_content_type($image['tmp_name']);
        if (strpos($mime_type, 'image') === false) {
            throw new Exception("Le fichier n'est pas une image valide.");
        }

        // Vérifier l'extension du fichier
        $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception("Erreur : seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
        }

        // Vérifier la taille du fichier (limite à 10 Mo)
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo en octets
        if ($image['size'] > $maxFileSize) {
            throw new Exception("Le fichier est trop volumineux. Taille maximale autorisée : 10 Mo.");
        }

        $newFileName = uniqid() . '.' . $fileExtension;
        $destinationFolder = self::uploadDir . $destinationFolder;
        $destination = self::endStringWithSlash($destinationFolder) . $newFileName;

        self::createDirectory($destinationFolder);

        // Déplacer le fichier vers le dossier de destination
        if (!move_uploaded_file($image['tmp_name'], $destination)) {
            throw new Exception("Erreur lors de l'upload du fichier.");
        }

        return $newFileName;
    }
}
