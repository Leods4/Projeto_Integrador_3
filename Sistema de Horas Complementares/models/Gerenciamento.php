<?php

require_once 'Database.php';

class Gerenciamento {
    private PDO $db;

    private ?int $id = null;
    private ?string $dataAlteracao = null;
    private ?string $alteracao = null;
    private ?string $observacao = null;
    private ?int $certificadoId = null;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // ▸ Setters
    public function setDataAlteracao(string $data): void {
        $this->dataAlteracao = $data;
    }

    public function setAlteracao(string $alteracao): void {
        $this->alteracao = trim($alteracao);
    }

    public function setObservacao(string $observacao): void {
        $this->observacao = trim($observacao);
    }

    public function setCertificadoId(int $id): void {
        $this->certificadoId = $id;
    }

    /**
     * Registra uma alteração associada a um certificado
     * Deve ser chamado após uma modificação relevante no certificado
     */
    public function registrarAlteracao(): bool {
        try {
            $query = "
                INSERT INTO gerenciamento (
                    data_alteracao, alteracao, observacao, certificado_id
                ) VALUES (
                    :data_alteracao, :alteracao, :observacao, :certificado_id
                )
            ";

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':data_alteracao' => $this->dataAlteracao,
                ':alteracao' => $this->alteracao,
                ':observacao' => $this->observacao,
                ':certificado_id' => $this->certificadoId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao registrar alteração: " . $e->getMessage());
            return false;
        }
    }
}
