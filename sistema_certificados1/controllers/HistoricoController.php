<?php
require_once __DIR__ . '/../models/Certificado.php';
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../config/Database.php';

class HistoricoController {

    public static function index() {
        Session::start();

        $db = Database::conectar();
        $certificado = new Certificado($db);

        // Verifica se é admin
        if (Session::get('isAdmin')) {
            // Carrega a view de admin (sem filtros por padrão)
            $resultados = []; // Pode ser modificado para carregar todos ou nenhum
            include __DIR__ . '/../views/historico_adm.php';
            exit;
        }

        // Se não for admin (usuário aluno), aplica o filtro pelo nome da sessão
        if (isset($_SESSION['nome'])) {
            $filtros = [
                'nome' => $_SESSION['nome'],
                'matricula' => '',
                'fase' => '',
                'curso' => '',
                'data_inicio' => '',
                'data_fim' => ''
            ];

            $resultados = $certificado->listarPorFiltros($filtros);
            include __DIR__ . '/../views/historico_alunos.php';
            exit;
        } else {
            http_response_code(401); // Unauthorized
            echo "Sessão inválida. Nome não encontrado.";
        }
    }

    public static function filtrar() {
        Session::start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'nome' => $_POST['nome'] ?? '',
                'matricula' => $_POST['matricula'] ?? '',
                'fase' => $_POST['fase'] ?? '',
                'curso' => $_POST['curso'] ?? '',
                'data_inicio' => '',
                'data_fim' => ''
            ];
            if (!empty($_POST['periodo'])) {
                $periodo = $_POST['periodo'];
                $filtros['data_inicio'] = $periodo . '-01';
                $filtros['data_fim'] = $periodo . '-31';
            }
            $db = Database::conectar();
            $certificado = new Certificado($db);
            $resultados = $certificado->listarPorFiltros($filtros);
            include __DIR__ . '/../views/historico_adm.php';
            exit;
        }
    }

    public static function filtrarPorNome() {
        Session::start();

        if (isset($_SESSION['nome'])) {
            $nomeSessao = $_SESSION['nome'];

            $filtros = [
                'nome' => $nomeSessao,
                'matricula' => '',
                'fase' => '',
                'curso' => '',
                'data_inicio' => '',
                'data_fim' => ''
            ];

            $db = Database::conectar();
            $certificado = new Certificado($db);
            $resultados = $certificado->listarPorFiltros($filtros);
            include __DIR__ . '/../views/historico_adm.php';
            exit;
        } else {
            http_response_code(401);
            echo "Sessão inválida. Nome não encontrado.";
        }
    }
}
