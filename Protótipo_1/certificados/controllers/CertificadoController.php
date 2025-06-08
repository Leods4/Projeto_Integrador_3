<?php
require_once __DIR__ . '/../models/Certificado.php';

class CertificadoController {
    public function index() {
        $certificados = Certificado::listarTodos();
        include __DIR__ . '/../views/historico.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $_POST;
            Certificado::criar($dados);
            header('Location: /certificados?controller=certificado&action=index');
            exit;
        } else {
            include __DIR__ . '/../views/certificado_form.php';
        }
    }

    public function deletar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Certificado::deletar($id);
        }
        header('Location: /certificados?controller=certificado&action=index');
        exit;
    }
}
