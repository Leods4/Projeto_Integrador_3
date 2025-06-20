<?php
require_once __DIR__ . '/../models/Certificado.php';
require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../config/Database.php';

class HistoricoAdminController {
    public static function filtrar() {
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
            // Inclui a view PHP e passa $resultados
            include __DIR__ . '/../views/historico_adm.php';
            exit;
        }
    }
} 