<?php

namespace App\Controllers;

use App\Models\Configuration;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;

class ConfigurationController
{
    private $configurationModel;

    public function __construct()
    {
        $this->configurationModel = new Configuration();
    }

    public function initConfig()
    {
        include realpath(__DIR__ . '/../../config/config.php');
        foreach ($appConfig as $key => $config) {
            foreach ($config as $type => $value) {
                $existingConfig = $this->configurationModel->get($key);
                if (!$existingConfig) {
                    $this->configurationModel->create($key, $value, $type);
                }
            }
        }
    }

    public static function checkMaintenanceMode()
    {
        $configurationModel = new Configuration();
        $maintenanceMode = $configurationModel->getValue('maintenance_mode');

        $isMaintenancePage = strpos($_SERVER['REQUEST_URI'], '/maintenance') !== false;

        if ($isMaintenancePage) {
            return;
        }

        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $isAdminLoginPage = strpos($_SERVER['REQUEST_URI'], '/admin/login') !== false;

        if ($maintenanceMode && !$isAdmin && !$isAdminLoginPage) {
            header('Location: /maintenance');
            exit();
        }
    }

    public function index()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');
        $configs = $this->configurationModel->getAll();
        View::render('admin/configuration/index.php', ['configs' => $configs]);
    }

    public function update()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setting_name = $_POST['setting_name'];
            $setting_value = $_POST['setting_value'];

            $success = $this->configurationModel->update($setting_name, $setting_value);
            if ($success) {
                $_SESSION['message'] = "Paramètre mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du paramètre.";
            }
        }
        self::index();
    }

    public function create()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');
        View::render('admin/configuration/create.php');
    }

    public function store()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setting_name = $_POST['setting_name'];
            $setting_value = $_POST['setting_value'];
            $setting_type = $_POST['setting_type'];

            $success = $this->configurationModel->create($setting_name, $setting_value, $setting_type);

            if ($success) {
                $_SESSION['message'] = "Nouveau paramètre créé avec succès.";
                self::index();
            } else {
                $_SESSION['error'] = "Erreur lors de la création du paramètre.";
                self::create();
            }
        }
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');
        $success = $this->configurationModel->destroy($id);

        if ($success) {
            $_SESSION['message'] = "Paramètre supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du paramètre.";
        }
        self::index();
    }
}
