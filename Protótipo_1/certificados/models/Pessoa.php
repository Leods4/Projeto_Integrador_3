<?php

class Pessoa {
    protected $db;
    protected $id;
    protected $nome;
    protected $email;
    protected $senhaHash;
    protected $isAdmin;

    public function __construct($db) {
        $this->db = $db;
    }

    // Getters e Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    }

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }

    // Métodos principais
    public function salvar() {
        $query = "INSERT INTO usuarios (nome, email, senha_hash, is_admin) 
                  VALUES (:nome, :email, :senha_hash, :is_admin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nome' => $this->nome,
            ':email' => $this->email,
            ':senha_hash' => $this->senhaHash,
            ':is_admin' => $this->isAdmin
        ]);
    }

    public function autenticar($email, $senha) {
        $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            // Preenche atributos
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->isAdmin = $usuario['is_admin'];
            return true;
        }

        return false;
    }
}