<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error</title>
    <link rel="stylesheet" href="/projeto_filmes/public/assets/css/style.css">
    <style>
        .error-container {
            text-align: center;
            padding: 50px 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .error-code {
            font-size: 72px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 24px;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .back-home {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    $pageTitle = "500 - Internal Server Error";
    require_once __DIR__ . '/../partials/header.php';
    ?>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message"><?= isset($errorMessage) ? htmlspecialchars($errorMessage) : 'Internal Server Error' ?></div>
        <p>Sorry, something went wrong on our end. Please try again later.</p>
        <a href="/projeto_filmes/public/" class="back-home">Back to Home</a>
    </div>
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
