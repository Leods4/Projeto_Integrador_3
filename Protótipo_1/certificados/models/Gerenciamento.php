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

    // Método para ser chamado pelo certificado para salvar alterações
    public function registrarAlteracao() {
        $query = "INSERT INTO gerenciamento (
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
}