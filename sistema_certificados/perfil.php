<?php
// Ponto de entrada para a página de perfil do aluno

require_once __DIR__ . '/controllers/PerfilController.php';

$controller = new PerfilController();
$controller->mostrar(); 