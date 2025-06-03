<?php

class Gerenciamento {
    private $db;

    private $id;
    private $dataAlteracao;
    private $alteracao;
    private $observacao;
    private $certificadoId;

    public function __construct($db) {
        $this->db = $db;
    }

    // Setters
    public function setDataAlteracao($data) {
        $this->dataAlteracao = $data;
    }

    public function setAlteracao($alteracao) {
        $this->alteracao = $alteracao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function setCertificadoId($id) {
        $this->certificadoId = $id;
    }

    // Método para registrar uma nova alteração
    public function registrar() {
        $query = "INSERT INTO gerenciamentos (
                    data_alteracao, alteracao, observacao, certificado_id
                  ) VALUES (
                    :data_alteracao, :alteracao, :observacao, :certificado_id
                  )";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':data_alteracao' => $this->dataAlteracao,
            ':alteracao' => $this->alteracao,
            ':observacao' => $this->observacao,
            ':certificado_id' => $this->certificadoId
        ]);
    }

    // Listar todas as alterações de um certificado específico
    public function listarPorCertificado($certificadoId) {
        $query = "SELECT * FROM gerenciamentos
                  WHERE certificado_id = :id
                  ORDER BY data_alteracao DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $certificadoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar todo o histórico (admin)
    public function listarTodos() {
        $query = "SELECT g.*, c.nome_certificado, u.nome AS nome_aluno
                  FROM gerenciamentos g
                  JOIN certificados c ON g.certificado_id = c.id
                  JOIN alunos a ON a.id = c.requerente_id
                  JOIN usuarios u ON u.id = a.id
                  ORDER BY g.data_alteracao DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}