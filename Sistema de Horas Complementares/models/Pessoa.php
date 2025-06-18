<?php

require_once 'Database.php';

class Pessoa {
    protected PDO $db;
    protected ?int $id = null;
    protected ?string $matricula = null;
    protected ?string $nome = null;
    protected ?string $cpf = null;
    protected ?string $email = null;
    protected ?string $senhaHash = null;
    protected bool $isAdmin = false;

    public function __construct(?PDO $db = null) {
        // Usa a conexão do Database caso nenhuma seja passada
        $this->db = $db ?? Database::conectar();
    }

    // Método de fábrica para facilitar criação
    public static function novaInstancia(): self {
        return new self(Database::conectar());
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setMatricula(string $matricula): void {
        $this->matricula = trim($matricula);
    }

    public function setNome(string $nome): void {
        $this->nome = trim($nome);
    }

    public function setCpf(string $cpf): void {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new InvalidArgumentException("CPF inválido.");
        }
        $this->cpf = $cpf;
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido.");
        }
        $this->email = strtolower(trim($email));
    }

    public function setSenha(string $senha): void {
        $this->senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    }

    public function setIsAdmin(bool $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getMatricula(): ?string {
        return $this->matricula;
    }

    public function getNome(): ?string {
        return $this->nome;
    }

    public function getCpf(): ?string {
        return $this->cpf;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    // CRUD
    public function salvar(): bool {
        $query = "
            INSERT INTO usuarios (matricula, nome, cpf, email, senha_hash, is_admin)
            VALUES (:matricula, :nome, :cpf, :email, :senha_hash, :is_admin)
        ";
        $stmt = $this->db->prepare($query);

        $resultado = $stmt->execute([
            ':matricula' => $this->matricula,
            ':nome' => $this->nome,
            ':cpf' => $this->cpf,
            ':email' => $this->email,
            ':senha_hash' => $this->senhaHash,
            ':is_admin' => $this->isAdmin ? 1 : 0
        ]);

        if ($resultado) {
            $this->id = (int)$this->db->lastInsertId();
        }

        return $resultado;
    }

    public function autenticar(string $cpf, string $senha): bool {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        $query = "SELECT * FROM usuarios WHERE cpf = :cpf LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cpf' => $cpf]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            $this->id = (int)$usuario['id'];
            $this->nome = $usuario['nome'];
            $this->cpf = $usuario['cpf'];
            $this->email = $usuario['email'];
            $this->matricula = $usuario['matricula'] ?? null;
            $this->isAdmin = (bool)$usuario['is_admin'];
            return true;
        }

        return false;
    }

    public function visualizarPerfil(): ?array {
        if (!$this->id) {
            throw new RuntimeException("ID do usuário não definido.");
        }

        $query = "SELECT id, nome, cpf, email, matricula, is_admin FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $this->id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function carregarPorId(int $id): bool {
        $query = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        $usuario = $stmt->fetch();

        if ($usuario) {
            $this->id = (int)$usuario['id'];
            $this->matricula = $usuario['matricula'];
            $this->nome = $usuario['nome'];
            $this->cpf = $usuario['cpf'];
            $this->email = $usuario['email'];
            $this->senhaHash = $usuario['senha_hash'];
            $this->isAdmin = (bool)$usuario['is_admin'];
            return true;
        }

        return false;
    }
}
