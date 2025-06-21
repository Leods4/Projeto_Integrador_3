<?php
// Conexão com o banco de dados
require_once 'Database.php'; // Inclui a classe de conexão
try {
    $pdo = Database::conectar();
} catch (RuntimeException $e) {
    die($e->getMessage()); // Encerra o script se a conexão falhar
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Inserção de usuários com senha segura
$usuarios = [
    ['2023001', '12345678901', 'João da Silva', 'joao@email.com', 'senha123', 0],
    ['2023002', '23456789012', 'Maria Oliveira', 'maria@email.com', 'maria456', 0],
    ['2023003', '34567890123', 'Carlos Pereira', 'carlos@email.com', 'carlos78', 0],
    ['ADM001', '99999999999', 'Admin Geral', 'admin@email.com', 'admin123', 1],
];

$stmt = $pdo->prepare("INSERT INTO usuarios (matricula, cpf, nome, email, senha_hash, is_admin)
                       VALUES (:matricula, :cpf, :nome, :email, :senha_hash, :is_admin)");

foreach ($usuarios as $usuario) {
    [$matricula, $cpf, $nome, $email, $senha, $is_admin] = $usuario;
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt->execute([
        ':matricula' => $matricula,
        ':cpf' => $cpf,
        ':nome' => $nome,
        ':email' => $email,
        ':senha_hash' => $senha_hash,
        ':is_admin' => $is_admin
    ]);
}

// Inserção de alunos
$alunos = [
    [1, 'Análise e Desenvolvimento de Sistemas', 3, 45],
    [2, 'Engenharia de Software', 2, 20],
    [3, 'Sistemas de Informação', 1, 0],
];

$stmt = $pdo->prepare("INSERT INTO alunos (id, curso, fase, total_horas)
                       VALUES (:id, :curso, :fase, :total_horas)");

foreach ($alunos as $aluno) {
    [$id, $curso, $fase, $total_horas] = $aluno;
    $stmt->execute([
        ':id' => $id,
        ':curso' => $curso,
        ':fase' => $fase,
        ':total_horas' => $total_horas
    ]);
}

// Inserção de administradores
$admins = [
    [4, 'Coordenador Geral'],
];

$stmt = $pdo->prepare("INSERT INTO administradores (id, tipo_administrador)
                       VALUES (:id, :tipo_administrador)");

foreach ($admins as $admin) {
    [$id, $tipo] = $admin;
    $stmt->execute([
        ':id' => $id,
        ':tipo_administrador' => $tipo
    ]);
}

// Inserção de certificados
$certificados = [
    // João (id 1)
    ['ACADEMICO', 'Curso de Python', 1, '2025-05-01', true, '2025-05-30', 'Curso introdutório concluído com êxito.', 'APROVADO', 20, 'Python Básico', 'IFSC', '2025-05-30', 'arquivo1.pdf'],
    ['SOCIOCULTURAL', 'Voluntariado em ONG', 1, '2025-04-01', true, '2025-04-15', 'Atuação em projeto social.', 'APROVADO_COM_RESSALVAS', 25, 'Projeto Social Cidadania', 'ONG Esperança', '2025-04-20', 'arquivo2.pdf'],
    
    // Maria (id 2)
    ['PROFISSIONAL', 'Estágio na empresa X', 2, '2025-03-10', true, '2025-06-10', 'Relatório de estágio apresentado.', 'ENTREGUE', 40, 'Estágio Profissional', 'Empresa X', '2025-06-01', 'arquivo3.pdf'],
    ['ACADEMICO', 'Curso de Banco de Dados', 2, '2025-02-05', true, '2025-03-05', 'Participação em curso técnico.', 'APROVADO', 15, 'SQL Intermediário', 'IFSC', '2025-03-06', 'arquivo4.pdf'],

    // Carlos (id 3)
    ['SOCIOCULTURAL', 'Oficina de Teatro', 3, '2025-01-10', true, '2025-01-30', 'Participou ativamente das apresentações.', 'REPROVADO', 10, 'Teatro e Expressão', 'Casa da Cultura', '2025-01-30', 'arquivo5.pdf'],
    ['PROFISSIONAL', 'Curso de Suporte Técnico', 3, '2025-04-10', true, '2025-05-10', 'Treinamento prático em TI.', 'APROVADO', 30, 'Suporte Técnico em TI', 'SENAI', '2025-05-11', 'arquivo6.pdf'],
];

$stmt = $pdo->prepare("INSERT INTO certificados (categoria, curso, requerente_id, data_criacao, iniciou_atividade, prazo_final, observacao, status, carga_horaria, nome_certificado, instituicao, data_emissao, arquivo)
                       VALUES (:categoria, :curso, :requerente_id, :data_criacao, :iniciou_atividade, :prazo_final, :observacao, :status, :carga_horaria, :nome_certificado, :instituicao, :data_emissao, :arquivo)");

foreach ($certificados as $c) {
    $stmt->execute([
        ':categoria' => $c[0],
        ':curso' => $c[1],
        ':requerente_id' => $c[2],
        ':data_criacao' => $c[3],
        ':iniciou_atividade' => $c[4],
        ':prazo_final' => $c[5],
        ':observacao' => $c[6],
        ':status' => $c[7],
        ':carga_horaria' => $c[8],
        ':nome_certificado' => $c[9],
        ':instituicao' => $c[10],
        ':data_emissao' => $c[11],
        ':arquivo' => $c[12],
    ]);
}

// Inserção de gerenciamentos
$gerenciamentos = [
    ['2025-06-10', 'Alterado status de PENDENTE para APROVADO', 'Revisado pelo coordenador geral.', 1]
];

$stmt = $pdo->prepare("INSERT INTO gerenciamentos (data_alteracao, alteracao, observacao, certificado_id)
                       VALUES (:data_alteracao, :alteracao, :observacao, :certificado_id)");

foreach ($gerenciamentos as $g) {
    [$data, $alteracao, $obs, $cert_id] = $g;
    $stmt->execute([
        ':data_alteracao' => $data,
        ':alteracao' => $alteracao,
        ':observacao' => $obs,
        ':certificado_id' => $cert_id
    ]);
}

echo "Inserções concluídas com sucesso!";
?>
