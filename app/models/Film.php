<?php
class Film {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM films ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM films WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO films (title, director, release_year, genre, description) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['director'],
            $data['release_year'],
            $data['genre'],
            $data['description']
        ]);
    }
}