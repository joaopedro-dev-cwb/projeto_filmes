<?php
require_once __DIR__ . '/../models/Film.php';

class FilmController {
 private $viewsPath;

    public function __construct() {
        // Define o caminho absoluto para a pasta de views
        $this->viewsPath = __DIR__ . '/../views/';
    }

    public function index() {
        $filmModel = new Film();
        $films = $filmModel->getAll();
        
        // Caminho absoluto para a view
        $viewFile = $this->viewsPath . 'films/index.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View não encontrada: " . $viewFile);
        }
    }

    public function show($id) {
        $film = new Film();
        $film = $film->getById($id);
        require_once __DIR__ . '../views/films/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = new Film();
            if ($film->create($_POST)) {
                header('Location: /films');
            }
        } else {
            require_once '../views/films/create.php';
        }
    }
}