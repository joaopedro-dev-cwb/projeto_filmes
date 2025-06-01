<?php include '../App/views/header.php'; ?>

<?php
    $controller = $_GET['controller'] ?? 'filme';
    $action = $_GET['action'] ?? 'listar';

    $controllerName = ucfirst($controller) . 'Controller';

    require_once '../App/controllers/' . $controllerName . '.php';

    $controllerObject = new $controllerName();

    if (method_exists($controllerObject, $action)) {
        $controllerObject->$action();
    } else {
        echo "Ação não encontrada!";
    }

?>

<?php include '../App/views/footer.php'; ?>