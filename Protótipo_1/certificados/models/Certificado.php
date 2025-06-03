<?php

class Certificado {
    private $db;

    private $id;
    private $categoria;
    private $curso;
    private $requerenteId;
    private $dataCriacao;
    private $iniciouAtividade;
    private $prazoFinal;
    private $observacao;
    private $status;
    private $cargaHoraria;
    private $nomeCertificado;
    private $instituicao;
    private $dataEmissao;
    private $arquivo;

    public function __construct($db) {
        $this->db = $db;
    }

    // Setters (somente os principais por clareza)
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }

    public function setRequerenteId($id) {
        $this->requerenteId = $id;
    }

    public function setCargaHoraria($horas) {
        $this->cargaHoraria = $horas;
    }

    public function setNomeCertificado($nome) {
        $this->nomeCertificado = $nome;
    }

    public function setInstituicao($instituicao) {
        $this->instituicao = $instituicao;
    }

    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    public function setDataCriacao($data) {
        $this->dataCriacao = $data;
    }

    public function setDataEmissao($data) {
        $this->dataEmissao = $data;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setObservacao($obs) {
        $this->observacao = $obs;
    }

    public function setPrazoFinal($data) {
        $this->prazoFinal = $data;
    }

    public function setIniciouAtividade($valor) {
        $this->iniciouAtividade = $valor;
    }

    // Método para salvar certificado
    public function salvar() {
        $query = "INSERT INTO certificados (
            categoria, curso, requerente_id, data_criacao, iniciou_atividade,
            prazo_final, observacao, status, carga_horaria,
            nome_certificado, instituicao, data_emissao, arquivo
        ) VALUES (
            :categoria, :curso, :requerente_id, :data_criacao, :iniciou_atividade,
            :prazo_final, :observacao, :status, :carga_horaria,
            :nome_certificado, :instituicao, :data_emissao, :arquivo
        )";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':categoria' => $this->categoria,
            ':curso' => $this->curso,
            ':requerente_id' => $this->requerenteId,
            ':data_criacao' => $this->dataCriacao,
            ':iniciou_atividade' => $this->iniciouAtividade,
            ':prazo_final' => $this->prazoFinal,
            ':observacao' => $this->observacao,
            ':status' => $this->status,
            ':carga_horaria' => $this->cargaHoraria,
            ':nome_certificado' => $this->nomeCertificado,
            ':instituicao' => $this->instituicao,
            ':data_emissao' => $this->dataEmissao,
            ':arquivo' => $this->arquivo
        ]);
    }

    // Alterar status do certificado
    public function alterarStatus($novoStatus) {
        $query = "UPDATE certificados SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':status' => $novoStatus,
            ':id' => $this->id
        ]);
    }

    // Alterar categoria do certificado
    public function alterarCategoria($novaCategoria) {
        $query = "UPDATE certificados SET categoria = :categoria WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':categoria' => $novaCategoria,
            ':id' => $this->id
        ]);
    }

    // Validação simples de formato do arquivo
    public function validarFormato() {
        $extensao = pathinfo($this->arquivo, PATHINFO_EXTENSION);
        return strtolower($extensao) === 'pdf';
    }

    // (Opcional) buscar certificados de um aluno
    public function listarPorAluno($alunoId) {
        $query = "SELECT * FROM certificados WHERE requerente_id = :id ORDER BY data_criacao DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $alunoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // (Opcional) define o ID do certificado (necessário para alterações)
    public function setId($id) {
        $this->id = $id;
    }
}