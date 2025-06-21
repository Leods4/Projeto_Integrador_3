<?php
require_once 'controllers/LoginController.php';
require_once 'config/Session.php';

Session::start();

if (Session::get('nome') || Session::get('isAdmin')) {
    // Sessão válida → envia para roteador de início, que decide entre aluno ou admin
    header('Location: /sistema_certificados/config/Router.php?rota=inicio');
    exit;
}

// Sessão inválida ou não iniciada → chama login
LoginController::index();
exit;
