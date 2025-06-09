<?php
require_once __DIR__ . '/../models/Film.php';

class FilmController {
    private $viewsPath;

    public function __construct() {
        // Define o caminho absoluto para a pasta de views
        $this->viewsPath = __DIR__ . '/../views/';
    }

    private function renderError($code, $message = null) {
        http_response_code($code);
        $errorMessage = $message;
        $viewFile = $this->viewsPath . "errors/{$code}.php";
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Error {$code}: " . ($message ?? 'An error occurred'));
        }
        exit;
    }

    private function validateFormData($data, $file = null) {
        // Basic field validation
        $requiredFields = ['title', 'director', 'release_year', 'duration'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required.");
            }
        }

        // Release year validation
        $year = (int)$data['release_year'];
        if ($year < 1888 || $year > (int)date('Y')) {
            $this->renderError(400, "Release year must be between 1888 and " . date('Y'));
        }

        // Duration validation
        $duration = (int)$data['duration'];
        if ($duration <= 0) {
            $this->renderError(400, "Duration must be greater than 0 minutes");
        }

        // File validation if uploaded
        if ($file && $file['size'] > 0) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->renderError(400, "File upload failed with error code: " . $file['error']);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                $this->renderError(400, 'Invalid file type. Only JPG, PNG and WebP are allowed.');
            }

            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                $this->renderError(400, 'File size too large. Maximum size is 5MB.');
            }
        }
    }

    public function index() {
        try {
            $filmModel = new Film();
            $films = $filmModel->getAll();
            
            // Caminho absoluto para a view
            $viewFile = $this->viewsPath . 'films/index.php';
            
            if (file_exists($viewFile)) {
                require $viewFile;
            } else {
                $this->renderError(404, 'View file not found');
            }
        } catch (Exception $e) {
            $this->renderError(500, $e->getMessage());
        }
    }    public function show($id) {
        try {
            if (!$id) {
                $this->renderError(404, 'Film ID not provided');
            }

            $film = new Film();
            $film = $film->getById($id);
            
            if (!$film) {
                $this->renderError(404, 'Film not found');
            }

            $viewFile = $this->viewsPath . 'films/show.php';
            
            if (file_exists($viewFile)) {
                require $viewFile;
            } else {
                $this->renderError(404, 'View file not found');
            }
        } catch (Exception $e) {
            $this->renderError(500, $e->getMessage());
        }
    }    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->renderError(401, 'You must be logged in to create a movie.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'director' => $_POST['director'] ?? '',
                    'release_year' => $_POST['release_year'] ?? '',
                    'duration' => $_POST['duration'] ?? '',
                    'genres' => $_POST['genres'] ?? [],
                    'user_id' => $_SESSION['user_id']
                ];

                // Validate form data and file
                $file = isset($_FILES['cover_image']) ? $_FILES['cover_image'] : null;
                $this->validateFormData($data, $file);
                
                // Create the film
                $filmModel = new Film();
                $filmId = $filmModel->create($data, $file);
                
                $_SESSION['success'] = 'Film created successfully!';
                header('Location: /projeto_filmes/public/index.php?action=films&id=' . $filmId);
                exit;
            } catch (Exception $e) {
                $this->renderError(400, $e->getMessage());
            }
        }

        // Get genres for the form
        $genreStmt = Database::getInstance()->query("SELECT * FROM genres ORDER BY name");
        $genres = $genreStmt->fetchAll();
        
        require $this->viewsPath . 'films/create.php';
    }    public function edit($id) {
        $filmModel = new Film();
        $film = $filmModel->getById($id);

        if (!$film) {
            $_SESSION['error'] = 'Film not found';
            header('Location: /projeto_filmes/public/index.php?action=films');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'release_year' => $_POST['release_year'] ?? '',
                    'duration' => $_POST['duration'] ?? '',
                    'genres' => $_POST['genres'] ?? []
                ];

                // Validate required fields
                $requiredFields = ['title', 'release_year', 'duration'];
                foreach ($requiredFields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("Field {$field} is required.");
                    }
                }

                // Handle file upload
                $file = isset($_FILES['cover_image']) && $_FILES['cover_image']['size'] > 0 
                    ? $_FILES['cover_image'] 
                    : null;

                $filmModel->update($id, $data, $file);
                
                $_SESSION['success'] = 'Film updated successfully!';
                header('Location: /projeto_filmes/public/index.php?action=films&id=' . $id);
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        // Get genres for the form
        $genreStmt = Database::getInstance()->query("SELECT * FROM genres ORDER BY name");
        $genres = $genreStmt->fetchAll();

        require $this->viewsPath . 'films/edit.php';
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $filmModel = new Film();
                $filmModel->delete($id);
                
                $_SESSION['success'] = 'Film deleted successfully!';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        header('Location: /projeto_filmes/public/index.php?action=films');
        exit;
    }
    public function byGenre($genre) {
        $film = new Film();
        $films = $film->getByGenre($genre);
        $viewFile = $this->viewsPath . 'films/index.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View not found: " . $viewFile);
        }
    }
}