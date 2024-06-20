<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Contact
{
    public static function getAllContacts()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM contacts");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Contact::getAll() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function getById($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Contact::getById() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function create($title, $text, $link)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO contacts (title, text, link) VALUES (:title, :text, :link)");
            $stmt->execute(['title' => $title, 'text' => $text, 'link' => $link]);
        } catch (PDOException $e) {
            error_log('PDOException - Contact::create() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public static function update($id, $title, $text, $link)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE contacts SET title = :title, text = :text, link = :link WHERE id = :id");
            $stmt->execute(['title' => $title, 'text' => $text, 'link' => $link, 'id' => $id]);
        } catch (PDOException $e) {
            error_log('PDOException - Contact::update() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public static function destroy($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log('PDOException - Contact::destroy() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
