<?php

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Helpers\Session;
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
        if (is_null($users)) {
            Session::set('error', 'Erreur lors de la recherche des utilisateurs.');
        } elseif (empty($users)) {
            Session::set('error', 'Aucun utilisateur trouvé.');
        }
        View::render('admin/users/index.php', ['users' => $users]);
    }

    public function show($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::getUserById($id);
        if (is_null($user)) {
            Session::set('error', 'Erreur lors de la recherche de l\'utilisateurs.');
            self::index();
            exit();
        } elseif (empty($user)) {
            Session::set('error', 'Utilisateur non trouvé.');
            self::index();
            exit();
        }
        View::render('admin/users/show.php', ['user' => $user]);
    }

    public function create($context = 'user')
    {
        View::render('users/create.php', ['context' => $context, 'csrfToken' => Csrf::generateToken()]);
    }

    public function store($context = 'user')
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

            if (User::getUserByUsername($username)) {
                throw new Exception("Nom d'utilisateur déjà utilisé.");
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $user = new User(null, $username, $passwordHash, $role, $createdAt, $updatedAt, null, null);
            if ($user->save() === null) {
                throw new Exception('Une erreur est survenue lors de l\'inscription.');
            }

            header("Location: $redirectUrl");
            exit;
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            self::create($context);
        }
    }

    public function edit($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        $user = User::getUserById($id);
        if (is_null($user)) {
            Session::set('error', 'Erreur lors de la recherche de l\'utilisateur.');
        } elseif (empty($user)) {
            Session::set('error', 'Utilisateur non trouvé.');
        }
        View::render('admin/users/edit.php', ['user' => $user]);
    }

    public function update($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        try {
            $user = User::getUserById($id);
            $user->setUsername($_POST['username']);
            $user->setRole($_POST['role']);
            $user->setUpdatedAt(date('Y-m-d H:i:s'));

            // Optionnel: Mise à jour du mot de passe
            if (!empty($_POST['password'])) {
                $user->setPasswordHash(password_hash($_POST['password'], PASSWORD_BCRYPT));
            }

            if ($user->save() === null) {
                throw new Exception('Une erreur est survenue lors de la mise à jour de l\'utilisateur.');
            }
            Session::set('message', 'Utilisateur modifié avec succès !');
            self::index();
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            self::edit($id);
        }
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
        try {
            User::destroy($id);
            Session::set('message', 'Utilisateur supprimé avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la suppression de l\'utilisateur.');
        }
        self::index('admin');
    }
}
