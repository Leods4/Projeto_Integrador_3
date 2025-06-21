<?php

require_once 'Pessoa.php';
require_once __DIR__ . '/../config/Database.php';

class Admin extends Pessoa {
    private ?string $tipoAdministrador = null;

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    // Setter
    public function setTipoAdministrador(string $tipo): void {
        $tipo = trim($tipo);
        if (empty($tipo)) {
            throw new InvalidArgumentException("Tipo de administrador não pode ser vazio.");
        }
        $this->tipoAdministrador = $tipo;
    }

    // Getter
    public function getTipoAdministrador(): ?string {
        return $this->tipoAdministrador;
    }

    // Salvar Admin (em usuarios e depois em administradores)
    public function salvarAdmin(): bool {
        try {
            $this->db->beginTransaction();

            if (!parent::salvar()) {
                $this->db->rollBack();
                return false;
            }

            $idUsuario = (int) $this->db->lastInsertId();

            $query = "
                INSERT INTO administradores (id, tipo_administrador)
                VALUES (:id, :tipo)
            ";
            $stmt = $this->db->prepare($query);

            $stmt->execute([
                ':id' => $idUsuario,
                ':tipo' => $this->tipoAdministrador
            ]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro ao salvar administrador: " . $e->getMessage());
            return false;
        }
    }
}
