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
  <link rel="stylesheet" href="/sistema_certificados1/public/historico_validar.css">
</head>
<body>
  <header>
    <div class="left">
      <a href="/sistema_certificados1/config/Router.php?rota=inicio">
        <img src="/sistema_certificados1/public/home-icon.png" alt="Início" />
        Início
      </a>
    </div>
  
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  
    <div class="right">
      <a href="/sistema_certificados1/config/Router.php?rota=logout">
        <img src="/sistema_certificados1/public/logout-icon.png" alt="Sair" />
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
                      <a href="/sistema_certificados1/uploads/<?= urlencode($item['arquivo']) ?>" download>
                        <img src="/sistema_certificados1/public/download-icon.png" alt="Ícone download" />
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
    <div class="footer-content" style="display: flex; justify-content: space-between; align-items: center; width: 100vw; max-width: 100vw; padding: 0 30px;">
      <div style="flex:1; text-align: left; font-weight: bold; min-width: 180px;">3ª Fase de ADS</div>
      <div style="flex:2; display: flex; justify-content: center; align-items: center; gap: 40px; min-width: 400px;">
        <span style="font-size: 0.98em;">Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</span>
        <span id="ano-atual" style="font-weight: bold; min-width: 80px; text-align: left;"></span>
      </div>
      <div style="flex:1; text-align: right; min-width: 120px;">
        <img src="/sistema_certificados1/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" style="height: 32px;" />
      </div>
    </div>
  </footer>

  <script>
    document.getElementById("ano-atual").textContent = `2025/${new Date().getFullYear()}`;
  </script>
</body>
</html>
