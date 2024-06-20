<?php

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Helpers\Session;
use App\Helpers\View;
use App\Models\Contact;
use PDOException;

class ContactController
{
    public function index($context = 'user')
    {
        $contacts = Contact::getAllContacts();
        if (is_null($contacts)) {
            Session::set('error', 'Erreur lors de la recherche du contact.');
        } elseif (empty($contacts)) {
            Session::set('error', 'Aucun contact trouvé.');
        }

        if ($context == 'user') {
            View::render('contact/index.php', ['contacts' => $contacts]);
        } else if ($context == 'admin'){
            View::render('admin/contacts/index.php', ['contacts' => $contacts]);
        }
    }

    public function create()
    {
        View::render('admin/contacts/create.php', ['csrfToken' => Csrf::generateToken()]);
    }

    public function store()
    {
        Csrf::verifyToken($_POST['csrfToken']);
        $title = $_POST['title'];
        $text = $_POST['text'];
        $link = $_POST['link'] ?? null;

        try {
            Contact::create($title, $text, $link);
            Session::set('message', 'Nouveau contact créé avec succès !');
            self::index('admin');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la création du contact.');
            self::create();
        }
    }

    public function edit($id)
    {
        $contact = Contact::getById($id);
        if (is_null($contact)) {
            Session::set('error', 'Erreur lors de la recherche du contact.');
            self::index('admin');
        } elseif (empty($contact)) {
            Session::set('error', 'Contact non trouvé.');
            self::index('admin');
        } else {
            View::render('admin/contacts/edit.php', ['contact' => $contact, 'csrfToken' => Csrf::generateToken()]);
        }
    }

    public function update($id)
    {
        Csrf::verifyToken($_POST['csrfToken']);
        $title = $_POST['title'];
        $text = $_POST['text'];
        $link = $_POST['link'] ?? null;

        try {
            Contact::update($id, $title, $text, $link);
            Session::set('message', 'Contact mis à jour avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la mise à jour du contact.');
        }
        self::index('admin');
    }

    public function destroy($id)
    {
        Csrf::verifyToken($_POST['csrfToken']);

        try {
            Contact::destroy($id);
            Session::set('message', 'Contact supprimé avec succès !');
        } catch (PDOException $e) {
            Session::set('error', 'Erreur lors de la suppression du contact.');
        }
        self::index('admin');
    }
}
