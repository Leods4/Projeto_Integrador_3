<?php

class Pessoa {
    protected $db;
    protected $id;
    private $matricula;
    protected $nome;
    protected $cpf;
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

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
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

    public function getMatricula() {
        return $this->matricula;
    }   

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }

    // Métodos principais
    public function salvar() {
        $query = "INSERT INTO usuarios (nome, cpf, email, senha_hash, is_admin) 
                  VALUES (:nome, :cpf, :email, :senha_hash, :is_admin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nome' => $this->nome,
            ':cpf' => $this->cpf,
            ':email' => $this->email,
            ':senha_hash' => $this->senhaHash,
            ':is_admin' => $this->isAdmin
        ]);
    }

    public function autenticar($cpf, $senha) {
        $query = "SELECT * FROM usuarios WHERE cpf = :cpf LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cpf' => $cpf]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            // Preenche atributos
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
            $this->cpf = $usuario['cpf'];
            $this->email = $usuario['email'];
            $this->isAdmin = $usuario['is_admin'];
            return true;
        }

        return false;
    }

    // Método para visualizar perfil
    public function visualizarPerfil() {
        if (!$this->id) {
            throw new Exception("ID do usuário não definido.");
        }
    
        $query = "SELECT id, nome, cpf, email, matricula, is_admin 
                  FROM usuarios 
                  WHERE id = :id 
                  LIMIT 1";
    
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $this->id]);
    
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
        return $perfil ?: null;
    }
}
