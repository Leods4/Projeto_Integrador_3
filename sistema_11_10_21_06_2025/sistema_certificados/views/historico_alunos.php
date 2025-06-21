<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Garante que $resultados está definido como array
$resultados = $resultados ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Horas - Aluno</title>
  <link rel="stylesheet" href="/sistema_certificados/public/historico_validar.css">
</head>
<body>
  <header>
    <div class="left">
      <a href="/sistema_certificados/config/Router.php?rota=inicio">
        <img src="/sistema_certificados/public/home-icon.png" alt="Início" />
        Início
      </a>
    </div>
  
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  
    <div class="right">
      <a href="/sistema_certificados/config/Router.php?rota=logout">
        <img src="/sistema_certificados/public/logout-icon.png" alt="Sair" />
        Sair
      </a>
    </div>
  </header>

  <main>
    <section class="content">
      <div class="section-header">
        <div class="filters">
          <h2>Histórico de Horas</h2>
          <!-- Filtros para alunos podem ser adicionados futuramente -->
        </div>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Área</th>
              <th>Tipo</th>
              <th>Nome</th>
              <th>Horas Atribuídas</th>
              <th>Período</th>
              <th>Situação</th>
              <th>Download</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($resultados)): ?>
              <tr><td colspan="8">Nenhum registro encontrado.</td></tr>
            <?php else: ?>
              <?php foreach ($resultados as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['id'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($item['categoria'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($item['tipo'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($item['nome_certificado'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($item['carga_horaria'] ?? '-') ?></td>
                  <td>
                    <?= !empty($item['data_criacao']) ? date('m/Y', strtotime($item['data_criacao'])) : '-' ?>
                  </td>
                  <td>
                    <span class="status-label <?= strtolower($item['status'] ?? 'pendente') ?>">
                      <?= strtoupper($item['status'] ?? '-') ?>
                    </span>
                  </td>
                  <td>
                    <?php if (!empty($item['arquivo'])): ?>
                      <a href="/sistema_certificados/uploads/<?= urlencode($item['arquivo']) ?>" download>
                        <img src="/sistema_certificados/public/download-icon.png" alt="Ícone download" />
                      </a>
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual">2025/AnoAtual</div>
    <img src="/sistema_certificados/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>

  <script>
    const ano = new Date().getFullYear();
    document.getElementById("ano-atual").textContent = `2025/${ano}`;
  </script>
</body>
</html>
