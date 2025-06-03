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

    // Buscar admin por ID
    public function buscarPorId($id) {
        $query = "SELECT u.*, a.tipo_administrador
                  FROM usuarios u
                  JOIN administradores a ON u.id = a.id
                  WHERE u.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dados) {
            $this->id = $dados['id'];
            $this->nome = $dados['nome'];
            $this->email = $dados['email'];
            $this->isAdmin = $dados['is_admin'];
            $this->tipoAdministrador = $dados['tipo_administrador'];
            return true;
        }

        return false;
    }

    // Exemplo: visualizar histórico geral de alterações em certificados
    public function visualizarHistoricoGeral() {
        $query = "SELECT g.*, c.nome_certificado, u.nome AS nome_aluno
                  FROM gerenciamentos g
                  JOIN certificados c ON c.id = g.certificado_id
                  JOIN alunos a ON c.requerente_id = a.id
                  JOIN usuarios u ON u.id = a.id
                  ORDER BY g.data_alteracao DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Exemplo: registrar alteração de um certificado
    public function registrarAlteracao($certificadoId, $tipoAlteracao, $observacao = '') {
        $query = "INSERT INTO gerenciamentos (data_alteracao, alteracao, observacao, certificado_id)
                  VALUES (NOW(), :alteracao, :observacao, :certificado_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':alteracao' => $tipoAlteracao,
            ':observacao' => $observacao,
            ':certificado_id' => $certificadoId
        ]);
    }
}
