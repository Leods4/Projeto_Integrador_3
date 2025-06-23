<?php
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../models/Certificado.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Gerenciamento.php';

class ValidacaoController {

    public static function index() {
        Session::start();
        if (!Session::get('isAdmin')) {
            header('Location: /sistema_certificados1/config/Router.php?rota=login');
            exit;
        }

        $db = Database::conectar();
        $certificadoModel = new Certificado($db);

        // Debug: Verificar dados recebidos
        error_log("DEBUG: POST data: " . print_r($_POST, true));

        // Filtros do formulário
        $filtros = [
            'nome' => $_POST['nome'] ?? '',
            'matricula' => $_POST['matricula'] ?? '',
            'fase' => $_POST['fase'] ?? '',
            'curso' => $_POST['curso'] ?? '',
            'status' => $_POST['status'] ?? '',
            'data_inicio' => '',
            'data_fim' => ''
        ];
        if (!empty($_POST['periodo'])) {
            $periodo = $_POST['periodo'];
            $filtros['data_inicio'] = $periodo . '-01';
            $filtros['data_fim'] = $periodo . '-31';
        }
        // Corrige status do filtro para o formato do banco
        if ($filtros['status'] === 'APROVADO COM RESSALVAS') {
            $filtros['status'] = 'APROVADO_COM_RESSALVAS';
        }

        // Debug: Verificar filtros processados
        error_log("DEBUG: Filtros processados: " . print_r($filtros, true));

        // Verificar se há filtros aplicados
        $temFiltros = false;
        foreach ($filtros as $key => $value) {
            if ($key !== 'data_inicio' && $key !== 'data_fim' && !empty($value)) {
                $temFiltros = true;
                break;
            }
        }

        // Debug: Verificar se tem filtros
        error_log("DEBUG: Tem filtros: " . ($temFiltros ? 'SIM' : 'NÃO'));

        if ($temFiltros) {
            // Aplicar filtros
            $certificados = $certificadoModel->listarPorFiltros($filtros);
            error_log("DEBUG: Certificados com filtros: " . count($certificados));
        } else {
            // Buscar todos os certificados do banco
            $certificados = $certificadoModel->listarPorFiltros([]);
            error_log("DEBUG: Todos os certificados: " . count($certificados));
            // Ordenar por blocos de status e data decrescente
            $statusOrder = ['ENTREGUE', 'APROVADO', 'APROVADO COM RESSALVAS', 'APROVADO_COM_RESSALVAS', 'REPROVADO'];
            usort($certificados, function($a, $b) use ($statusOrder) {
                $aStatus = ($a['status'] === 'APROVADO_COM_RESSALVAS') ? 'APROVADO COM RESSALVAS' : $a['status'];
                $bStatus = ($b['status'] === 'APROVADO_COM_RESSALVAS') ? 'APROVADO COM RESSALVAS' : $b['status'];
                $aIdx = array_search($aStatus, $statusOrder);
                $bIdx = array_search($bStatus, $statusOrder);
                if ($aIdx === $bIdx) {
                    return strtotime($b['data_criacao']) <=> strtotime($a['data_criacao']);
                }
                return $aIdx <=> $bIdx;
            });
        }
        // Corrige status para exibição
        foreach ($certificados as &$cert) {
            if (isset($cert['status']) && $cert['status'] === 'APROVADO_COM_RESSALVAS') {
                $cert['status'] = 'APROVADO COM RESSALVAS';
            }
        }
        unset($cert);
        $avaliados = [];

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
        $status = $_POST['status'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
        $carga_horaria = $_POST['carga_horaria'] ?? null;
        $observacao = $_POST['observacao'] ?? null;
        $tipo = $_POST['tipo'] ?? null;

        if ($id_certificado) {
            $db = Database::conectar();
            $certificado = new Certificado($db);
            $certificado->setId((int)$id_certificado);

            // Inicializa o gerenciamento
            $gerenciamento = new Gerenciamento($db);
            $gerenciamento->setCertificadoId((int)$id_certificado);
            $gerenciamento->setDataAlteracao(date('Y-m-d H:i:s'));

            // Armazena as alterações para registrar
            $alteracoes = [];

            if ($categoria) {
                $certificado->alterarCategoria($categoria);
                $alteracoes[] = "Categoria alterada para '{$categoria}'";
            }

            if ($carga_horaria !== null) {
                $certificado->alterarCargaHoraria((int)$carga_horaria);
                $alteracoes[] = "Carga horária alterada para '{$carga_horaria}' horas";
            }

            if ($observacao !== null) {
                $certificado->alterarObservacao($observacao);
                $alteracoes[] = "Observação alterada";
            }

            if ($tipo !== null) {
                $certificado->alterarTipo($tipo);
                $alteracoes[] = "Tipo alterado para '{$tipo}'";
            }

            if ($status) {
                if ($status === 'APROVADO COM RESSALVAS') {
                    $status = 'APROVADO_COM_RESSALVAS';
                }
                $certificado->alterarStatus($status);
                $alteracoes[] = "Status alterado para '{$status}'";
            }

            // Registra no gerenciamento
            if (!empty($alteracoes)) {
                $gerenciamento->setAlteracao(implode("; ", $alteracoes));
                $gerenciamento->setObservacao($observacao ?? '');
                $gerenciamento->registrarAlteracao();
            }
        }

        header('Location: /sistema_certificados1/config/Router.php?rota=validacao');
        exit;
    }


    public static function editarCertificado() {
        Session::start();
        if (!Session::get('isAdmin') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /sistema_certificados1/config/Router.php?rota=login');
            exit;
        }
        $id_certificado = $_POST['id_certificado'] ?? null;
        if ($id_certificado) {
            $db = Database::conectar();
            $certificado = new Certificado($db);
            $certificado->setId((int)$id_certificado);
            $categoria = $_POST['categoria'] ?? null;
            $carga_horaria = $_POST['carga_horaria'] ?? null;
            $observacao = $_POST['observacao'] ?? null;
            $status = $_POST['status'] ?? null;
            if ($categoria) $certificado->alterarCategoria($categoria);
            if ($carga_horaria !== null) $certificado->alterarCargaHoraria((int)$carga_horaria);
            if ($observacao !== null) $certificado->alterarObservacao($observacao);
            if ($status) $certificado->alterarStatus($status);
        }
        header('Location: /sistema_certificados1/config/Router.php?rota=validacao');
        exit;
    }
}