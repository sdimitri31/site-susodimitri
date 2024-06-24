<?php

namespace App\Controllers;

use App\Helpers\AlertMessage;
use App\Helpers\Authorization;
use App\Helpers\Csrf;
use App\Helpers\Session;
use App\Helpers\View;
use App\Models\Contact;
use App\Models\Permission;
use PDOException;

class ContactController
{
    public function index($context = 'user')
    {
        $contacts = Contact::getAllContacts();
        if (is_null($contacts)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la recherche du contact.');
        } elseif (empty($contacts)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Aucun contact trouvé.');
        }

        if ($context == 'user') {
            View::render('contact/index.php', ['contacts' => $contacts]);
        } else if ($context == 'admin'){
            Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
            View::render('admin/contacts/index.php', ['contacts' => $contacts]);
        }
    }

    public function create()
    {
        Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
        View::render('admin/contacts/create.php', ['csrfToken' => Csrf::generateToken()]);
    }

    public function store()
    {
        Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
        Csrf::verifyToken($_POST['csrfToken']);
        $title = $_POST['title'];
        $text = $_POST['text'];
        $link = $_POST['link'] ?? null;

        try {
            Contact::create($title, $text, $link);
            AlertMessage::setAlert(AlertMessage::SUCCESS, 'Nouveau contact créé avec succès !');
            self::index('admin');
        } catch (PDOException $e) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la création du contact.');
            self::create();
        }
    }

    public function edit($id)
    {
        Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
        $contact = Contact::getById($id);
        if (is_null($contact)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la recherche du contact.');
            self::index('admin');
        } elseif (empty($contact)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Contact non trouvé.');
            self::index('admin');
        } else {
            View::render('admin/contacts/edit.php', ['contact' => $contact, 'csrfToken' => Csrf::generateToken()]);
        }
    }

    public function update($id)
    {
        Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
        Csrf::verifyToken($_POST['csrfToken']);
        $title = $_POST['title'];
        $text = $_POST['text'];
        $link = $_POST['link'] ?? null;

        try {
            Contact::update($id, $title, $text, $link);
            AlertMessage::setAlert(AlertMessage::SUCCESS, 'Contact mis à jour avec succès !');
        } catch (PDOException $e) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la mise à jour du contact.');
        }
        self::index('admin');
    }

    public function destroy($id)
    {
        Authorization::requirePermission(Permission::MANAGE_CONTACTS, '/login');
        Csrf::verifyToken($_POST['csrfToken']);

        try {
            Contact::destroy($id);
            AlertMessage::setAlert(AlertMessage::SUCCESS, 'Contact supprimé avec succès !');
        } catch (PDOException $e) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la suppression du contact.');
        }
        self::index('admin');
    }
}
