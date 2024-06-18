<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;
use App\Helpers\Upload;

class ProjectController
{
    private $projectModel;

    public function __construct()
    {
        $this->projectModel = new Project();
    }

    public function index()
    {
        $projects = $this->projectModel->getAllProjects();
        View::render('projects/index.php', ['projects' => $projects]);
    }

    public function show($id)
    {
        $project = $this->projectModel->getProjectById($id);
        View::render('projects/show.php', ['project' => $project]);
    }

    public function create($error = null)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        View::render('admin/projects/create.php', ['error' => $error]);
    }

    public function store()
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        try {
            $this->projectModel->setName($_POST['name']);
            $this->projectModel->setDescription($_POST['description']);
            $this->projectModel->setContent($_POST['content']);
            $this->projectModel->setPosition($_POST['position']);
            $this->projectModel->save();

            $imagePath = "/projects/" . $this->projectModel->getId() . "/";
            $imageName = Upload::uploadImage($_FILES["image"], $imagePath);

            Upload::moveTempFiles($_POST['dataJson'], $imagePath);

            $contentWithUpdatedPath = Upload::updateImagePathsInHtml($this->projectModel->getContent(), $imagePath);
            $this->projectModel->setContent($contentWithUpdatedPath);
            $this->projectModel->setImageName($imageName);

            $this->projectModel->save();

            header('Location: /projects');
        } catch (\Exception $e) {
            self::create($e->getMessage());
        }
    }

    public function edit($id, $error = null)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        $project = $this->projectModel->getProjectById($id);
        View::render('admin/projects/edit.php', ['project' => $project, 'error' => $error]);
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
            $this->projectModel->setId($id);
            $this->projectModel->setName($_POST['name']);
            $this->projectModel->setDescription($_POST['description']);
            $this->projectModel->setPosition($_POST['position']);
            $this->projectModel->setImageName($imageName);

            Upload::moveTempFiles($_POST['dataJson'], $imagePath);
            $contentWithUpdatedPath = Upload::updateImagePathsInHtml($_POST['content'], '/uploads' . $imagePath);

            $this->projectModel->setContent($contentWithUpdatedPath);

            $this->projectModel->save();

            header('Location: /projects');
        } catch (\Exception $e) {
            self::edit($id, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        $this->projectModel->deleteProject($id);
        header('Location: /admin/projects');
    }

    public function adminIndex()
    {
        Authorization::requirePermission(Permission::MANAGE_PROJECTS, '/home');
        $projects = $this->projectModel->getAllProjects();
        View::render('admin/projects/index.php', ['projects' => $projects]);
    }

}
