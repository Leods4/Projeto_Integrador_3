<?php
require_once __DIR__ . '/../controllers/CadastroHorasController.php';
require_once __DIR__ . '/../controllers/HistoricoController.php';
require_once __DIR__ . '/../controllers/InicioController.php';
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/PerfilController.php';
require_once __DIR__ . '/../controllers/ValidacaoController.php';

$rota = $_GET['rota'] ?? '';

switch ($rota) {
    case 'cadastro':
        CadastroHorasController::index();
        break;
    case 'cadastrar':
        CadastroHorasController::cadastrar();
        break;
    case 'historico':
        HistoricoController::index();
        break;
    case 'filtrar':
        HistoricoController::filtrar();
        break;
    case 'filtrar_nome':
        HistoricoController::filtrarPorNome();
        break;
    case 'inicio':
        InicioController::index();
        break;
    case 'login':
        LoginController::index();
        break;
    case 'login_in':
        LoginController::login();
        break;
    case 'perfil':
        PerfilController::index();
        break;
    case 'validacao':
        ValidacaoController::index();
        break;
    case 'processarValidacao':
        ValidacaoController::processarValidacao();
        break;
    case 'editarCertificado':
        ValidacaoController::editarCertificado();
        break;
    case 'logout':
        LoginController::logout();
        break;
    default:
        // Rota padrão ou erro
        header('Location: /sistema_certificados1/views/login.php');
        exit;
}
