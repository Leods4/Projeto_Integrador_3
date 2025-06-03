<?php
require_once 'Pessoa.php';

class Aluno extends Pessoa {
    private $matricula;
    private $curso;
    private $fase;
    private $totalHoras;

    public function __construct($db) {
        parent::__construct($db);
    }

    // Getters e Setters
    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }

    public function setFase($fase) {
        $this->fase = $fase;
    }

    public function setTotalHoras($totalHoras) {
        $this->totalHoras = $totalHoras;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getCurso() {
        return $this->curso;
    }

    public function getFase() {
        return $this->fase;
    }

    public function getTotalHoras() {
        return $this->totalHoras;
    }

    // Salvar Aluno (insere em usuarios e depois em alunos)
    public function salvarAluno() {
        // Primeiro salva na tabela usuarios
        $salvo = parent::salvar();

        if (!$salvo) {
            return false;
        }

        // Recupera o ID do usuário recém-criado
        $idUsuario = $this->db->lastInsertId();

        // Agora insere na tabela alunos
        $query = "INSERT INTO alunos (id, matricula, curso, fase, total_horas) 
                  VALUES (:id, :matricula, :curso, :fase, :total_horas)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $idUsuario,
            ':matricula' => $this->matricula,
            ':curso' => $this->curso,
            ':fase' => $this->fase,
            ':total_horas' => $this->totalHoras
        ]);
    }

    // Buscar aluno por ID
    public function buscarPorId($id) {
        $query = "SELECT u.*, a.matricula, a.curso, a.fase, a.total_horas
                  FROM usuarios u
                  JOIN alunos a ON u.id = a.id
                  WHERE u.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dados) {
            $this->id = $dados['id'];
            $this->nome = $dados['nome'];
            $this->email = $dados['email'];
            $this->isAdmin = $dados['is_admin'];
            $this->matricula = $dados['matricula'];
            $this->curso = $dados['curso'];
            $this->fase = $dados['fase'];
            $this->totalHoras = $dados['total_horas'];
            return true;
        }

        return false;
    }
}