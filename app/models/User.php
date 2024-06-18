<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private $id;
    private $username;
    private $passwordHash;
    private $role;
    private $createdAt;
    private $updatedAt;
    private $lastLoginAt;
    private $lockedUntil;

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    public function getLockedUntil()
    {
        return $this->lockedUntil;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function setLockedUntil($lockedUntil)
    {
        $this->lockedUntil = $lockedUntil;
    }

    public function __construct($id, $username, $passwordHash, $role, $createdAt, $updatedAt, $lastLoginAt, $lockedUntil)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->lastLoginAt = $lastLoginAt;
        $this->lockedUntil = $lockedUntil;
    }

    public function isLocked()
    {
        return $this->lockedUntil !== null && $this->lockedUntil > date('Y-m-d H:i:s');
    }

    public function lockAccount()
    {
        $this->lockedUntil = date('Y-m-d H:i:s', time() + 99999999);
    }

    public function save()
    {
        $db = (new Database())->getPdo();
        if ($this->id === null) {
            $stmt = $db->prepare("INSERT INTO users (username, password_hash, role, created_at, updated_at)
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$this->username, $this->passwordHash, $this->role, $this->createdAt, $this->updatedAt]);
            $this->id = $db->lastInsertId();
        } else {
            $stmt = $db->prepare("UPDATE users
                                  SET username = ?, password_hash = ?, role = ?, updated_at = ?, last_login_at = ?, locked_until = ?
                                  WHERE id = ?");
            $stmt->execute([$this->username, $this->passwordHash, $this->role, $this->updatedAt, $this->lastLoginAt, $this->lockedUntil, $this->id]);
        }
    }

    public static function getAllUsers()
    {
        $db = (new Database())->getPdo();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $db = (new Database())->getPdo();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new self(
            $user['id'],
            $user['username'],
            $user['password_hash'],
            $user['role'],
            $user['created_at'],
            $user['updated_at'],
            $user['last_login_at'],
            $user['locked_until']
        );
    }

    public static function findByUsername($username)
    {
        $db = (new Database())->getPdo();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new self(
            $user['id'],
            $user['username'],
            $user['password_hash'],
            $user['role'],
            $user['created_at'],
            $user['updated_at'],
            $user['last_login_at'],
            $user['locked_until']
        );
    }

    public function delete()
    {
        $db = (new Database())->getPdo();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
