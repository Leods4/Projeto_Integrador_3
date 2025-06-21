<?php
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../models/Pessoa.php';
require_once __DIR__ . '/../config/Database.php';

class PerfilController {

    public static function index() {
        Session::start();

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /sistema_certificados/views/login.php');
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];

        try {
            $db = Database::conectar();

            $pessoa = new Pessoa($db);
            $pessoa->setId($id_usuario);
            $perfil = $pessoa->visualizarPerfil(); // Já retorna nome, email e matrícula

            if (!$perfil) {
                throw new Exception("Não foi possível carregar o perfil.");
            }

            require __DIR__ . '/../views/perfil.php';

        } catch (Exception $e) {
            error_log("Erro ao carregar perfil: " . $e->getMessage());
            echo "Ocorreu um erro ao carregar o seu perfil. Tente novamente mais tarde.";
            exit;
        }
    }
}
