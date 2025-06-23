<?php
require_once __DIR__ . '/../config/Session.php';

class InicioController {

    public static function index() {
        Session::start();

        if (Session::get('isAdmin')) {
            // É admin → redireciona para área de admin
            header('Location: /sistema_certificados1/views/inicio_adm.php');
            exit;
        }

        // Não é admin → carrega página de aluno
        require_once __DIR__ . '/../views/inicio_alunos.php';
    }

}
