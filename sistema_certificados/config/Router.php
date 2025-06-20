<?php
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/HistoricoAdminController.php';

$rota = $_GET['rota'] ?? '';

switch ($rota) {
    case 'index':
        LoginController::index();
    case 'login':
        LoginController::login();
        break;
    case 'logout':
        LoginController::logout();
        break;
    case 'filtrar':
        HistoricoAdminController::filtrar();
        break;
    default:
        // Rota padrão ou erro
        header('Location: /sistema_certificados/views/login.php');
        exit;
} 