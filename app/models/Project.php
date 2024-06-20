<?php

namespace App\Models;

use App\Core\Database;
use PDOException;

class Project
{
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
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->content = $content;
        $this->imageName = $imageName;
        $this->position = $position;
    }

    public static function getAllProjects()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM projects ORDER BY position");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Project::getAllProjects() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function getProjectById($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Project::getProjectById() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public function save()
    {
        if ($this->id === null) {
            $this->id = Project::create([
                'name' => $this->name,
                'description' => $this->description,
                'content' => $this->content,
                'image_name' => $this->imageName,
                'position' => $this->position
            ]);
        } else {
            $this->id = Project::update([
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'content' => $this->content,
                'image_name' => $this->imageName,
                'position' => $this->position
            ]);
        }
        return $this->id;
    }

    public static function create($data)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO projects (name, description, content, image_name, position) VALUES (:name, :description, :content, :image_name, :position)");
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':image_name', $data['image_name']);
            $stmt->bindParam(':position', $data['position'], \PDO::PARAM_INT);
            $stmt->execute();

            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log('PDOException - Project::create() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function update($data)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE projects SET name = :name, description = :description, content = :content, image_name = :image_name, position = :position WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], \PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':image_name', $data['image_name']);
            $stmt->bindParam(':position', $data['position'], \PDO::PARAM_INT);
            $stmt->execute();
            return $data['id'];
        } catch (PDOException $e) {
            error_log('PDOException - Project::update() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function destroy($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('PDOException - Project::destroy() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
