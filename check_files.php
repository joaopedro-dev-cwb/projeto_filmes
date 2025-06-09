<?php
// Check if all required files exist and are readable
$requiredFiles = [
    __DIR__ . '/public/index.php',
    __DIR__ . '/public/assets/css/style.css',
    __DIR__ . '/App/config/database.php',
    __DIR__ . '/App/controllers/AuthController.php',
    __DIR__ . '/App/controllers/FilmController.php',
    __DIR__ . '/App/models/Film.php',
    __DIR__ . '/App/models/User.php',
    __DIR__ . '/App/views/home.php',
    __DIR__ . '/App/views/auth/login.php',
    __DIR__ . '/App/views/auth/register.php',
    __DIR__ . '/App/views/auth/reset_password.php',
    __DIR__ . '/App/views/films/create.php',
    __DIR__ . '/App/views/films/edit.php',
    __DIR__ . '/App/views/films/index.php',
    __DIR__ . '/App/views/films/show.php',
    __DIR__ . '/App/views/partials/header.php',
    __DIR__ . '/App/views/partials/footer.php'
];

$missingFiles = [];
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $missingFiles[] = $file;
    }
}

if (empty($missingFiles)) {
    echo "All required files are present.\n";
} else {
    echo "Missing files:\n";
    foreach ($missingFiles as $file) {
        echo "- $file\n";
    }
}
