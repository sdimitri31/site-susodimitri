<?php

namespace App\Models;

use App\Core\Database;
use PDOException;

class Project
{
    private $db;
    private $id;
    private $name;
    private $description;
    private $content;
    private $imageName;
    private $position;

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getImageName()
    {
        return $this->imageName;
    }
    public function getPosition()
    {
        return $this->position;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function __construct($id = null, $name = '', $description = '', $content = '', $imageName = '', $position = '')
    {
        $this->db = (new Database())->getPdo();
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->content = $content;
        $this->imageName = $imageName;
        $this->position = $position;
    }

    public function getAllProjects()
    {
        $stmt = $this->db->prepare("SELECT * FROM projects ORDER BY position");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProjectById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function save()
    {
        if ($this->id === null) {
            $stmt = $this->db->prepare("INSERT INTO projects (name, description, content, image_name, position)
                                        VALUES (:name, :description, :content, :image_name, :position)");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':image_name', $this->imageName);
            $stmt->bindParam(':position', $this->position, \PDO::PARAM_INT);
            try {
                $stmt->execute();
                $this->id = $this->db->lastInsertId();
                return $this->id;
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            $stmt = $this->db->prepare("UPDATE projects SET name = :name, description = :description, content = :content, image_name = :image_name, position = :position WHERE id = :id");
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':image_name', $this->imageName);
            $stmt->bindParam(':position', $this->position, \PDO::PARAM_INT);
            try {
                $stmt->execute();
                return $this->id;
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    public function addProject($data)
    {
        $stmt = $this->db->prepare("INSERT INTO projects (name, description, content, image_name, position) VALUES (:name, :description, :content, :image_name, :position)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image_name', $data['imageName']);
        $stmt->bindParam(':position', $data['position'], \PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateProject($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE projects SET name = :name, description = :description, content = :content, image_name = :image_name, position = :position WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image_name', $data['imageName']);
        $stmt->bindParam(':position', $data['position'], \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteProject($id)
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
