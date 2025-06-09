<?php
class Film {
    private $db;
    private $uploadDir = 'uploads/covers/';

    const FIRST_MOVIE_YEAR = 1888; // Year of the first film ever made

    public function __construct() {
        $this->db = Database::getInstance();
    }

    private function validateFilmData($data) {
        $currentYear = (int)date('Y');
        
        if (isset($data['release_year'])) {
            $year = (int)$data['release_year'];
            if ($year < self::FIRST_MOVIE_YEAR || $year > $currentYear) {
                throw new Exception("Release year must be between " . self::FIRST_MOVIE_YEAR . " and " . $currentYear);
            }
        }

        if (isset($data['duration'])) {
            $duration = (int)$data['duration'];
            if ($duration <= 0) {
                throw new Exception("Duration must be greater than 0 minutes");
            }
        }

        if (empty($data['title'])) {
            throw new Exception("Title is required");
        }

        if (empty($data['director'])) {
            throw new Exception("Director is required");
        }
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT f.*, 
                   GROUP_CONCAT(DISTINCT g.name) as genre_names,
                   COUNT(DISTINCT r.id) as review_count,
                   AVG(r.rating) as average_rating
            FROM films f
            LEFT JOIN film_genres fg ON f.id = fg.film_id
            LEFT JOIN genres g ON fg.genre_id = g.id
            LEFT JOIN reviews r ON f.id = r.film_id
            GROUP BY f.id
            ORDER BY f.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        // Get film details with genres and average rating
        $stmt = $this->db->prepare("
            SELECT f.*, 
                   GROUP_CONCAT(DISTINCT g.name) as genre_names,
                   COUNT(DISTINCT r.id) as review_count,
                   AVG(r.rating) as average_rating
            FROM films f
            LEFT JOIN film_genres fg ON f.id = fg.film_id
            LEFT JOIN genres g ON fg.genre_id = g.id
            LEFT JOIN reviews r ON f.id = r.film_id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt->execute([$id]);
        $film = $stmt->fetch();

        if ($film) {
            // Get reviews for the film
            $reviewStmt = $this->db->prepare("
                SELECT r.*, u.name as user_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.film_id = ?
                ORDER BY r.created_at DESC
            ");
            $reviewStmt->execute([$id]);
            $film['reviews'] = $reviewStmt->fetchAll();
        }

        return $film;
    }public function create($data, $file = null) {
        try {
            $this->validateFilmData($data);
            $this->db->beginTransaction();

            $coverPath = $file ? $this->handleFileUpload($file) : null;
            
            if ($coverPath) {
                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/projeto_filmes/public/' . $coverPath;
                $this->resizeImage($absolutePath);
            }

            $stmt = $this->db->prepare("
                INSERT INTO films (title, description, director, release_year, duration, cover_image, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['director'],
                $data['release_year'],
                $data['duration'],
                $coverPath,
                $data['user_id']
            ]);

            $filmId = $this->db->lastInsertId();

            // Handle genres
            if (!empty($data['genres'])) {
                $genreStmt = $this->db->prepare("INSERT INTO film_genres (film_id, genre_id) VALUES (?, ?)");
                foreach ($data['genres'] as $genreId) {
                    $genreStmt->execute([$filmId, $genreId]);
                }
            }

            $this->db->commit();
            return $filmId;
        } catch (Exception $e) {
            $this->safeRollback();
            throw $e;
        }
    }

    public function update($id, $data, $file = null) {
        try {
            $this->validateFilmData($data);
            $this->db->beginTransaction();

            $film = $this->getById($id);
            if (!$film) {
                throw new Exception('Film not found');
            }

            $coverPath = $file ? $this->handleFileUpload($file) : $film['cover_image'];

            // If new file uploaded, delete old one
            if ($file && $coverPath) {
                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/projeto_filmes/public/' . $coverPath;
                $this->resizeImage($absolutePath);
            }

            $stmt = $this->db->prepare("
                UPDATE films 
                SET title = ?, description = ?, director = ?, release_year = ?, 
                    duration = ?, cover_image = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['director'],      // <-- Adicione esta linha!
                $data['release_year'],
                $data['duration'],
                $coverPath,
                $id
            ]);

            // Update genres
            $this->db->prepare("DELETE FROM film_genres WHERE film_id = ?")->execute([$id]);
            
            if (!empty($data['genres'])) {
                $genreStmt = $this->db->prepare("INSERT INTO film_genres (film_id, genre_id) VALUES (?, ?)");
                foreach ($data['genres'] as $genreId) {
                    $genreStmt->execute([$id, $genreId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->safeRollback();
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $this->db->beginTransaction();

            // Get film info for cover image deletion
            $film = $this->getById($id);
            if (!$film) {
                throw new Exception('Film not found');
            }

            // Delete cover image if exists
            if ($film['cover_image']) {
                $filePath = $_SERVER['DOCUMENT_ROOT'] . '/projeto_filmes/public/' . $film['cover_image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete related records
            $this->db->prepare("DELETE FROM film_genres WHERE film_id = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM reviews WHERE film_id = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM films WHERE id = ?")->execute([$id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->safeRollback();
            throw $e;
        }
    }

    private function handleFileUpload($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            if ($file && $file['error'] === UPLOAD_ERR_NO_FILE) {
                return null;
            }
            throw new Exception('File upload failed: ' . $this->getUploadError($file['error']));
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG and WebP are allowed.');
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            throw new Exception('File size too large. Maximum size is 5MB.');
        }

        // Create uploads directory if it doesn't exist
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/projeto_filmes/public/' . $this->uploadDir;
        if (!file_exists($uploadPath)) {
            if (!mkdir($uploadPath, 0777, true)) {
                throw new Exception('Failed to create upload directory.');
            }
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $targetPath = $uploadPath . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move uploaded file.');
        }

        return $this->uploadDir . $filename;
    }

        private function resizeImage($filePath, $width = 400, $height = 600) {
        $info = getimagesize($filePath);
        if (!$info) return false;

        switch ($info['mime']) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $src = imagecreatefrompng($filePath);
                break;
            case 'image/webp':
                $src = imagecreatefromwebp($filePath);
                break;
            default:
                return false;
        }

        $dst = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));

        // Salva a imagem redimensionada sobrescrevendo o arquivo original
        switch ($info['mime']) {
            case 'image/jpeg':
                imagejpeg($dst, $filePath, 90);
                break;
            case 'image/png':
                imagepng($dst, $filePath, 9);
                break;
            case 'image/webp':
                imagewebp($dst, $filePath, 90);
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);
        return true;
    }

    private function getUploadError($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload';
            default:
                return 'Unknown upload error';
        }
    }

    public function getFeatured($limit = 6) {
        $stmt = $this->db->prepare("SELECT * FROM films ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getAllGenres() {
        $stmt = $this->db->query("SELECT DISTINCT genre FROM films WHERE genre IS NOT NULL ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getByGenre($genre) {
        $stmt = $this->db->prepare("SELECT * FROM films WHERE genre = ? ORDER BY created_at DESC");
        $stmt->execute([$genre]);
        return $stmt->fetchAll();
    }

    private function safeRollback() {
    if ($this->db->inTransaction()) {
        $this->db->rollBack();
    }
}
}