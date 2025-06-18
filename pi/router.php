<?php
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/FiltroController.php';

$rota = $_GET['rota'] ?? '';

switch ($rota) {
    case 'login':
        LoginController::login();
        break;
    case 'logout':
        LoginController::logout();
        break;
    case 'filtrar':
        FiltroController::filtrar();
        break;
    default:
        header('Location: views/login.html');
        exit;
} 