<?php

require_once 'Pessoa.php';
require_once __DIR__ . '/../config/Database.php';

class Aluno extends Pessoa {
    private ?string $curso = null;
    private ?int $fase = null;
    private ?int $totalHoras = null;

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    // Setters
    public function setCurso(string $curso): void {
        $this->curso = trim($curso);
    }

    public function setFase(int $fase): void {
        if ($fase < 1) {
            throw new InvalidArgumentException("Fase inválida.");
        }
        $this->fase = $fase;
    }

    public function setTotalHoras(int $totalHoras): void {
        if ($totalHoras < 0) {
            throw new InvalidArgumentException("Total de horas inválido.");
        }
        $this->totalHoras = $totalHoras;
    }

    // Getters
    public function getCurso(): ?string {
        return $this->curso;
    }

    public function getFase(): ?int {
        return $this->fase;
    }

    public function getTotalHoras(): ?int {
        return $this->totalHoras;
    }

    // Salvar aluno (tabela usuarios + tabela alunos)
    public function salvarAluno(): bool {
        try {
            $this->db->beginTransaction();

            if (!parent::salvar()) {
                $this->db->rollBack();
                return false;
            }

            $idUsuario = (int) $this->db->lastInsertId();

            $query = "
                INSERT INTO alunos (id, matricula, curso, fase, total_horas)
                VALUES (:id, :matricula, :curso, :fase, :total_horas)
            ";
            $stmt = $this->db->prepare($query);

            $stmt->execute([
                ':id' => $idUsuario,
                ':matricula' => $this->getMatricula(),
                ':curso' => $this->curso,
                ':fase' => $this->fase,
                ':total_horas' => $this->totalHoras
            ]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro ao salvar aluno: " . $e->getMessage());
            return false;
        }
    }
}
