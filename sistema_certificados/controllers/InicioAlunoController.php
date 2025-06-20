<?php
require_once __DIR__ . '/../config/Session.php';

class LoginController {
    
    public function index() {
        // Apenas carrega a view de inicio
        require_once __DIR__ . '/../views/inicio_alunos.html';
    }


    public function logout() {
        Session::destroy();
        header('Location: /sistema_certificados/login/index');
        exit;
    }
}
