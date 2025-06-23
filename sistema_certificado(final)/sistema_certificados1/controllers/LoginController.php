<?php
require_once __DIR__ . '/../models/Pessoa.php';
require_once __DIR__ . '/../config/Session.php';
require_once 'InicioController.php';

class LoginController {
    
    public static function index() {
        Session::start();

        // Se já estiver logado, redireciona para início
        if (Session::get('usuario_id')) {
            InicioController::index();
            exit;
        }

        // Caso contrário, carrega a view de login
        require_once __DIR__ . '/../views/login.php';
    }

    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $pessoa = Pessoa::novaInstancia();
            if ($pessoa->autenticar($cpf, $senha)) {
                Session::set('usuario_id', $pessoa->getId());
                Session::set('isAdmin', $pessoa->isAdmin());
                Session::set('nome', $pessoa->getNome());
                
                InicioController::index();
                exit;
            }
        }

        // Se o login falhar
        $_SESSION['login_error'] = "Matrícula ou senha inválidos.";
        header('Location: /sistema_certificados1/views/login.php');
        exit;
    }

    public static function logout() {
        Session::start();
        Session::destroy();
        header('Location: /sistema_certificados1/views/login.php');
        exit;
    }
}
