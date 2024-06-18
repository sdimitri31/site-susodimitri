<?php

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;
use Exception;

class UserController
{
    public function index()
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $users = User::getAllUsers();
        View::render('admin/users/index.php', ['users' => $users]);
    }

    public function show($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::findById($id);
        View::render('admin/users/show.php', ['user' => $user]);
    }

    public function showRegistrationForm($context = 'user', $error = null)
    {
        View::render('users/create.php', ['context' => $context, 'csrfToken' => Csrf::generateToken(), 'error' => $error]);
    }

    public function create($context = 'user')
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = Role::USER;
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');
        $csrfToken = $_POST['csrf_token'];
        $redirectUrl = '/login';

        if ($context === 'admin') {
            Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
            $role = $_POST['role'];
            $redirectUrl = '/admin/users';
        }

        try {
            if (!Csrf::verifyToken($csrfToken)) {
                throw new Exception("Erreur CSRF détectée.");
            }

            if (User::findByUsername($username)) {
                throw new Exception("Nom d'utilisateur déjà utilisé.");
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $user = new User(null, $username, $passwordHash, $role, $createdAt, $updatedAt, null, null);
            $user->save();

            header("Location: $redirectUrl");
            exit;
        } catch (Exception $e) {
            self::showRegistrationForm($context, $e->getMessage());
        }
    }

    public function edit($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::findById($id);
        View::render('admin/users/edit.php', ['user' => $user]);
    }

    public function update($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::findById($id);
        $user->setUsername($_POST['username']);
        $user->setRole($_POST['role']);
        $user->setUpdatedAt(date('Y-m-d H:i:s'));

        // Optionnel: Mise à jour du mot de passe
        if (!empty($_POST['password'])) {
            $user->setPasswordHash(password_hash($_POST['password'], PASSWORD_BCRYPT));
        }

        $user->save();
        header('Location: /admin/users');
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::findById($id);
        $user->delete();
        header('Location: /admin/users');
    }
}
