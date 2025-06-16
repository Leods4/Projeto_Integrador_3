<?php
require_once __DIR__ . '/../core/Database.php';

class Certificado {
    private PDO $db;

    private ?int $id = null;
    private ?string $categoria = null;
    private ?string $curso = null;
    private ?int $requerenteId = null;
    private ?string $dataCriacao = null;
    private ?string $iniciouAtividade = null;
    private ?string $prazoFinal = null;
    private ?string $observacao = null;
    private ?string $status = null;
    private ?int $cargaHoraria = null;
    private ?string $nomeCertificado = null;
    private ?string $instituicao = null;
    private ?string $dataEmissao = null;
    private ?string $arquivo = null;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Setters (exemplos; todos podem ter validação extra)
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCategoria(string $categoria): void {
        $this->categoria = trim($categoria);
    }

    public function setCurso(string $curso): void {
        $this->curso = trim($curso);
    }

    public function setRequerenteId(int $id): void {
        $this->requerenteId = $id;
    }

    public function setCargaHoraria(int $horas): void {
        $this->cargaHoraria = $horas;
    }

    public function setNomeCertificado(string $nome): void {
        $this->nomeCertificado = trim($nome);
    }

    public function setInstituicao(string $instituicao): void {
        $this->instituicao = trim($instituicao);
    }

    public function setArquivo(string $arquivo): void {
        $this->arquivo = $arquivo;
    }

    public function setDataCriacao(string $data): void {
        $this->dataCriacao = $data;
    }

    public function setDataEmissao(string $data): void {
        $this->dataEmissao = $data;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setObservacao(string $obs): void {
        $this->observacao = $obs;
    }

    public function setPrazoFinal(string $data): void {
        $this->prazoFinal = $data;
    }

    public function setIniciouAtividade(string $valor): void {
        $this->iniciouAtividade = $valor;
    }

    // 🔹 Salva o certificado no banco
    public function salvar(): bool {
        try {
            $query = "
                INSERT INTO certificados (
                    categoria, curso, requerente_id, data_criacao, iniciou_atividade,
                    prazo_final, observacao, status, carga_horaria,
                    nome_certificado, instituicao, data_emissao, arquivo
                ) VALUES (
                    :categoria, :curso, :requerente_id, :data_criacao, :iniciou_atividade,
                    :prazo_final, :observacao, :status, :carga_horaria,
                    :nome_certificado, :instituicao, :data_emissao, :arquivo
                )
            ";

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
        } catch (PDOException $e) {
            error_log("Erro ao salvar certificado: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 Validação do arquivo (PDF)
    public function validarFormato(): bool {
        $extensao = pathinfo($this->arquivo ?? '', PATHINFO_EXTENSION);
        return strtolower($extensao) === 'pdf';
    }

    // 🔹 Métodos de atualização
    public function alterarStatus(string $novoStatus): bool {
        return $this->atualizarCampo('status', $novoStatus);
    }

    public function alterarCargaHoraria(int $novaCargaHoraria): bool {
        return $this->atualizarCampo('carga_horaria', $novaCargaHoraria);
    }

    public function alterarCategoria(string $novaCategoria): bool {
        return $this->atualizarCampo('categoria', $novaCategoria);
    }

    public function alterarObservacao(string $novaObservacao): bool {
        return $this->atualizarCampo('observacao', $novaObservacao);
    }

    // 🔹 Atualização genérica
    private function atualizarCampo(string $campo, $valor): bool {
        if (!$this->id) {
            throw new RuntimeException("ID do certificado não definido.");
        }

        $query = "UPDATE certificados SET $campo = :valor WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':valor' => $valor,
            ':id' => $this->id
        ]);
    }

    public function listarPorFiltros(array $filtros): array {
        $sql = "SELECT * FROM certificados WHERE 1=1";
        $params = [];

        if (!empty($filtros['aluno_id'])) {
            $sql .= " AND requerente_id = :aluno_id";
            $params[':aluno_id'] = $filtros['aluno_id'];
        }

        if (!empty($filtros['curso'])) {
            $sql .= " AND curso = :curso";
            $params[':curso'] = $filtros['curso'];
        }

        if (!empty($filtros['data_inicio']) && !empty($filtros['data_fim'])) {
            $sql .= " AND data_criacao BETWEEN :data_inicio AND :data_fim";
            $params[':data_inicio'] = $filtros['data_inicio'];
            $params[':data_fim'] = $filtros['data_fim'];
        } elseif (!empty($filtros['data_inicio'])) {
            $sql .= " AND data_criacao >= :data_inicio";
            $params[':data_inicio'] = $filtros['data_inicio'];
        } elseif (!empty($filtros['data_fim'])) {
            $sql .= " AND data_criacao <= :data_fim";
            $params[':data_fim'] = $filtros['data_fim'];
        }

        $sql .= " ORDER BY data_criacao DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // 🔹 Métodos estáticos
    public static function listarTodos(): array {
        $db = Database::conectar();
        $stmt = $db->query("SELECT * FROM certificados");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function criar(array $dados): bool {
        $db = Database::conectar();
        $stmt = $db->prepare("
            INSERT INTO certificados (nome_certificado, observacao, carga_horaria)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $dados['nome_certificado'] ?? '',
            $dados['observacao'] ?? '',
            $dados['carga_horaria'] ?? 0
        ]);
    }

    public static function deletar(int $id): bool {
        $db = Database::conectar();
        $stmt = $db->prepare("DELETE FROM certificados WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
