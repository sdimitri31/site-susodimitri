<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Project;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;
use App\Helpers\Upload;
use Exception;
use PDOException;

class ProjectController
{
    public function __construct()
    {
    }

    public function index($context = 'user')
    {
        $projects = Project::getAllProjects();
        if (is_null($projects)) {
            Session::set('error', 'Erreur lors de la recherche du projet.');
        } elseif (empty($projects)) {
            Session::set('error', 'Aucun projet trouvé.');
        }

        if ($context == 'user') {
            View::render('projects/index.php', ['projects' => $projects]);
        } else if ($context == 'admin') {
            View::render('admin/projects/index.php', ['projects' => $projects]);
        }
    }

    public function show($id)
    {
        $project = Project::getProjectById($id);
        if (is_null($project)) {
            Session::set('error', 'Erreur lors de la recherche du projet.');
            self::index();
            exit();
        } elseif (empty($project)) {
            Session::set('error', 'Projet non trouvé.');
            self::index();
            exit();
        }

        View::render('projects/show.php', ['project' => $project]);
    }

    public function create()
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        View::render('admin/projects/create.php');
    }

    public function store()
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        try {
            // Upload in temp folder first
            $imageName = Upload::uploadImage($_FILES["image"], 'temp/');

            // Generate id
            $project = new Project(
                null,
                $_POST['name'],
                $_POST['description'],
                $_POST['content'],
                $imageName,
                $_POST['position']
            );

            if ($project->save() === null) {
                throw new Exception('Une erreur est survenue lors de l\'enregistrement du projet');
            }

            // Move images to project folder
            $imagePath = "/projects/" . $project->getId() . "/";
            Upload::moveTempFiles([['name' => $imageName]], $imagePath);
            Upload::moveTempFiles($_POST['dataJson'], $imagePath);

            // Update path of images in content
            $contentWithUpdatedPath = Upload::updateImagePathsInHtml($project->getContent(), $imagePath);
            $project->setContent($contentWithUpdatedPath);

            // Final save
            if ($project->save() === null) {
                throw new Exception('Une erreur est survenue lors de l\'enregistrement du projet');
            }

            Session::set('message', 'Projet ajouté avec succès !');
            self::index('admin');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            self::create();
        }
    }

    public function edit($id)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        $project = Project::getProjectById($id);
        if (is_null($project)) {
            Session::set('error', 'Erreur lors de la recherche du projet.');
            self::index('admin');
            exit();
        } elseif (empty($project)) {
            Session::set('error', 'Projet non trouvé.');
            self::index('admin');
            exit();
        }
        View::render('admin/projects/edit.php', ['project' => $project]);
    }

    public function update($id)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        try {
            $imagePath = "/projects/" . $id . "/";

            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 4) {
                $imageName = $_POST['imageName'];
            } else {
                $imageName = Upload::uploadImage($_FILES["image"], $imagePath);
            }
            Upload::moveTempFiles($_POST['dataJson'], $imagePath);
            $contentWithUpdatedPath = Upload::updateImagePathsInHtml($_POST['content'], '/uploads' . $imagePath);

            $project = new Project(
                $id,
                $_POST['name'],
                $_POST['description'],
                $contentWithUpdatedPath,
                $imageName,
                $_POST['position']
            );

            if ($project->save() === null) {
                throw new Exception('Une erreur est survenue lors de la mise à jour du projet');
            }

            Session::set('message', 'Projet édité avec succès !');
            self::index('admin');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            self::edit($id);
        }
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        try {
            Project::destroy($id);
            Session::set('message', 'Projet supprimé avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la suppression du projet.');
        }
        self::index('admin');
    }


}
