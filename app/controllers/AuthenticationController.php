<?php

namespace App\Controllers;

use App\Controllers\LoginAttemptController;
use App\Helpers\Csrf;
use App\Helpers\Session;
use App\Helpers\View;
use App\Models\Configuration;
use App\Models\User;
use Exception;

class AuthenticationController
{
    public function showLoginForm($context = 'user', $error = null)
    {
        View::render('auth/login.php', ['context' => $context, 'csrfToken' => Csrf::generateToken(), 'error' => $error]);
    }

    public function login($context = 'user')
    {
        try {
            $loginAttemptController = new LoginAttemptController();
            $username = $_POST['username'];
            $password = $_POST['password'];
            $csrfToken = $_POST['csrf_token'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            if (!Csrf::verifyToken($csrfToken)) {
                throw new Exception("Erreur CSRF détectée.");
            }

            $user = User::findByUsername($username);

            if (!$user) {
                throw new Exception("Identifiants incorrect.");
            }

            if ($user->isLocked()) {
                throw new Exception("Compte verrouillé. Contactez l'administrateur pour plus d'informations");
            }

            $config = new Configuration();
            $maxAttempts = $config->getValue('user_max_login_attempts');
            $lockTime = $config->getValue('user_lock_time');

            if ($loginAttemptController->checkAndBlockUser($username, $ipAddress, $maxAttempts, $lockTime)) {
                throw new Exception("Compte verrouillé. Trop de tentatives de connexion infructueuses.");
            }

            if (!$this->verifyPassword($password, $user->getPasswordHash())) {
                throw new Exception("Identifiants incorrect.");
            }

            $redirectUrl = '/home';
            if ($context === 'admin') {
                if ($user->getRole() !== 'admin') {
                    throw new Exception("Identifiants incorrect.");
                }
                $redirectUrl = '/admin';
            }

            $loginAttemptController->logAttempt($username, $ipAddress, 1);

            $user->setLastLoginAt(date('Y-m-d H:i:s'));
            $user->save();

            Session::set('user_id', $user->getId());
            Session::set('user_role', $user->getRole());

            header("Location: $redirectUrl");
            exit;
        } catch (Exception $e) {
            $loginAttemptController = new LoginAttemptController();
            $loginAttemptController->logAttempt($username, $ipAddress, 0);
            self::showLoginForm($context, $e->getMessage());
        }
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /');
        exit;
    }

    public function verifyPassword($clearPassword, $hashedPassword)
    {
        return password_verify($clearPassword, $hashedPassword);
    }

    public static function getAuthenticatedUser()
    {
        if (Session::get('user_id') !== null) {
            return User::findById(Session::get('user_id'));
        }
        return null;
    }

}
