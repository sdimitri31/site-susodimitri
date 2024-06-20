<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Configuration;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;
use PDOException;

class ConfigurationController
{
    private $configuration;

    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    public static function initConfig()
    {
        include realpath(__DIR__ . '/../../config/config.php');
        $configuration = new Configuration();
        foreach ($appConfig as $key => $config) {
            foreach ($config as $type => $value) {
                try {
                    $existingConfig = $configuration->get($key);
                    if (!$existingConfig) {
                        $configuration->create($key, $value, $type);
                    }
                } catch (PDOException $e) {
                    error_log('PDOException - ConfigurationController::initConfig(): ' . $e->getMessage(), 0);
                    // Log the error, but continue with the next configuration
                }
            }
        }
    }

    public static function checkMaintenanceMode()
    {
        $configuration = new Configuration();
        $maintenanceMode = $configuration->getValue('maintenance_mode');
        $maintenanceMode = filter_var($maintenanceMode, FILTER_VALIDATE_BOOLEAN);
        
        if ($maintenanceMode) {
            // Maintenance mode activé
            $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
            $isAdminLoginPage = strpos($_SERVER['REQUEST_URI'], '/admin/login') !== false;

            // Rediriger vers la page de maintenance si ce n'est pas un admin ou une page de connexion admin
            if (!$isAdmin && !$isAdminLoginPage) {
                header('Location: /maintenance');
                exit();
            }
        }
    }

    public function index()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');
        $configs = $this->configuration->getAll();
        View::render('admin/configuration/index.php', ['configs' => $configs]);
    }

    public function update()
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::index();
            exit();
        }

        $setting_name = $_POST['setting_name'];
        $setting_value = $_POST['setting_value'];

        try {
            $this->configuration->update($setting_name, $setting_value);
            Session::set('message', 'Paramètre mis à jour avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la mise à jour du paramètre.');
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

            try {
                $this->configuration->create($setting_name, $setting_value, $setting_type);
                Session::set('message', 'Nouveau paramètre créé avec succès !');
                self::index();
            } catch (PDOException $e) {
                Session::set('error', 'Erreur lors de la création du paramètre.');
                self::create();
            }
        }
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_CONFIGURATION, '/home');

        try {
            $this->configuration->destroy($id);
            Session::set('message', 'Paramètre supprimé avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la suppression du paramètre.');
        }

        self::index();
    }
}
