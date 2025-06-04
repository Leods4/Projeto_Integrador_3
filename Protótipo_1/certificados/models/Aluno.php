<?php
require_once 'Pessoa.php';

class Aluno extends Pessoa {
    private $curso;
    private $fase;
    private $totalHoras;

    public function __construct($db) {
        parent::__construct($db);
    }

    // Getters e Setters
    public function setCurso($curso) {
        $this->curso = $curso;
    }

    public function setFase($fase) {
        $this->fase = $fase;
    }

    public function setTotalHoras($totalHoras) {
        $this->totalHoras = $totalHoras;
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
}