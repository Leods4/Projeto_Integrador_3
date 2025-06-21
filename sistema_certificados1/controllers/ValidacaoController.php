<?php
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../models/Certificado.php';
require_once __DIR__ . '/../config/Database.php';

class ValidacaoController {

    public static function index() {
        Session::start();
        if (!Session::get('isAdmin')) {
            header('Location: /sistema_certificados1/config/Router.php?rota=login');
            exit;
        }

        $db = Database::conectar();
        $certificadoModel = new Certificado($db);
        
        // Busca certificados com status 'ENTREGUE'
        $certificados = $certificadoModel->listarPorFiltros(['status' => 'ENTREGUE']);

        // Carrega a view de validação com os dados
        require_once __DIR__ . '/../views/validacao.php';
    }

    public static function processarValidacao() {
        Session::start();
        if (!Session::get('isAdmin') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /sistema_certificados1/config/Router.php?rota=login');
            exit;
        }

        $id_certificado = $_POST['id_certificado'] ?? null;
        $acao = $_POST['acao'] ?? null;

        if ($id_certificado && $acao) {
            $db = Database::conectar();
            $certificado = new Certificado($db);
            $certificado->setId((int)$id_certificado);

            if ($acao === 'aprovar') {
                $categoria = $_POST['categoria'] ?? null;
                $carga_horaria = $_POST['carga_horaria'] ?? null;

                if (empty($categoria) || !isset($carga_horaria) || $carga_horaria === '') {
                    Session::set('validation_error', 'Para aprovar, é necessário selecionar a área e definir a carga horária.');
                    header('Location: /sistema_certificados1/config/Router.php?rota=validacao');
                    exit;
                }

                $certificado->alterarStatus('APROVADO');
                $certificado->alterarCategoria($categoria);
                $certificado->alterarCargaHoraria((int)$carga_horaria);

            } elseif ($acao === 'reprovar') {
                // Futuramente, pode-se adicionar um campo para justificativa
                $certificado->alterarStatus('REPROVADO');
            }
        }

        // Redireciona de volta para a página de validação
        header('Location: /sistema_certificados1/config/Router.php?rota=validacao');
        exit;
    }
}