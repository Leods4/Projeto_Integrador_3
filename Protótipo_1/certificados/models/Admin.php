<?php
require_once 'Pessoa.php';

class Admin extends Pessoa {
    private $tipoAdministrador;

    public function __construct($db) {
        parent::__construct($db);
    }

    // Getter e Setter
    public function setTipoAdministrador($tipo) {
        $this->tipoAdministrador = $tipo;
    }

    public function getTipoAdministrador() {
        return $this->tipoAdministrador;
    }

    // Salvar Admin (insere em usuarios e depois em administradores)
    public function salvarAdmin() {
        $salvo = parent::salvar();

        if (!$salvo) {
            return false;
        }

        $idUsuario = $this->db->lastInsertId();

        $query = "INSERT INTO administradores (id, tipo_administrador) 
                  VALUES (:id, :tipo)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $idUsuario,
            ':tipo' => $this->tipoAdministrador
        ]);
    }
}
