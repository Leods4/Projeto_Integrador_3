<?php
require_once __DIR__ . '/../models/Pessoa.php';
require_once __DIR__ . '/../Session.php';

class LoginController {
    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $pessoa = Pessoa::novaInstancia();
            if ($pessoa->autenticar($cpf, $senha)) {
                Session::set('usuario_id', $pessoa->getId());
                Session::set('isAdmin', $pessoa->isAdmin());
                Session::set('nome', $pessoa->getNome());
                
                if ($pessoa->isAdmin()) {
                    header('Location: views/inicio_adm.html');
                } else {
                    header('Location: views/inicio_alunos.html');
                }
                exit;
            } else {
                header('Location: views/login.html?erro=1');
                exit;
            }
        }
    }
    public static function logout() {
        Session::destroy();
        header('Location: views/login.html');
        exit;
    }
} 