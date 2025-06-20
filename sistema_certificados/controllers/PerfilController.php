<?php
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../models/Pessoa.php';
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../config/Database.php';

class PerfilController {

    public function mostrar() {
        Session::start();
        // Se não houver ID do usuário na sessão, redireciona para a página de login
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /sistema_certificados/views/login.php');
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];

        try {
            $db = Database::conectar();
            
            // Buscar dados da tabela 'usuarios'
            $pessoa = new Pessoa($db);
            $pessoa->setId($id_usuario);
            $dados_pessoa = $pessoa->visualizarPerfil();

            // Buscar dados da tabela 'alunos'
            $aluno = new Aluno($db);
            $aluno->setId($id_usuario);
            $dados_aluno = $aluno->buscarDetalhes(); // Precisaremos criar este método

            if (!$dados_pessoa || !$dados_aluno) {
                throw new Exception("Não foi possível carregar os dados completos do perfil.");
            }
            
            // Junta os dados dos dois modelos
            $perfil_completo = array_merge($dados_pessoa, $dados_aluno);

            // Carrega a view do perfil e passa os dados
            require __DIR__ . '/../views/perfil.php';

        } catch (Exception $e) {
            error_log("Erro ao carregar perfil: " . $e->getMessage());
            echo "Ocorreu um erro ao carregar o seu perfil. Tente novamente mais tarde.";
            exit;
        }
    }
} 