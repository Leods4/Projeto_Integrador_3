<?php
// Se houver resultados do filtro, use-os. Caso contrário, apenas exibe a tabela vazia.
$resultados = $resultados ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Horas</title>
  <link rel="stylesheet" href="views/historico_validar.css">
</head>
<body>
  <header>
    <div class="left">
      <a href="inicio_alunos.html">
        <img src="views/home-icon.png" alt="Início" />
        Início
      </a>
    </div>
  
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  
    <div class="right">
      <a href="logout.html">
        <img src="views/logout-icon.png" alt="Sair" />
        Sair
      </a>
    </div>
  </header>
  
  <div class="section-header">
    <form class="filters" method="POST" action="/pi/router.php?rota=filtrar">
      <h2>Histórico de Horas</h2>
      <label>Nome:
        <input type="text" name="nome" class="input-small" />
      </label>
      <label>Matrícula:
        <input type="text" name="matricula" class="input-small" />
      </label>
      <label>Fase:
        <select name="fase">
          <option value="">Selecionar</option>
          <option value="1ª">1ª</option>
          <option value="2ª">2ª</option>
          <option value="3ª">3ª</option>
          <option value="4ª">4ª</option>
          <option value="5ª">5ª</option>
          <option value="6ª">6ª</option>
          <option value="7ª">7ª</option>
          <option value="8ª">8ª</option>
        </select>
      </label>
      <label>Curso:
        <select name="curso">
          <option value="">Selecionar</option>
          <option value="ADM">ADM</option>
          <option value="ADS">ADS</option>
          <option value="PED">PED</option>
          <option value="TPG">TPG</option>
        </select>
      </label>
      <label>Período:
        <input type="month" name="periodo" />
      </label>
      <button type="submit" class="btn">Filtrar</button>
      <button type="reset" class="btn-outline">Remover filtros</button>
    </form>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Matrícula</th>
          <th>Aluno</th>
          <th>Fase</th>
          <th>Curso</th>
          <th>Área</th>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Horas</th>
          <th>Período</th>
          <th>Observação</th>
          <th>Situação</th>
          <th>Download</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($resultados)): ?>
          <?php foreach ($resultados as $linha): ?>
            <tr>
              <td><?= htmlspecialchars($linha['id'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['matricula'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['nome_aluno'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['fase'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['curso'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['categoria'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['tipo'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['nome_certificado'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['carga_horaria'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['data_criacao'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['observacao'] ?? '') ?></td>
              <td><?= htmlspecialchars($linha['status'] ?? '') ?></td>
              <td>
                <?php if (!empty($linha['arquivo'])): ?>
                  <a href="<?= htmlspecialchars($linha['arquivo']) ?>" download><img src="views/download-icon.png" alt="Ícone download" /></a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="13">Nenhum resultado encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe da Silva</div>
    <div id="ano-atual">2025/AnoAtual</div>
    <img src="views/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>

  <script>
    const ano = new Date().getFullYear();
    document.getElementById("ano-atual").textContent = `2025/${ano}`;
  </script>
</body>
</html> 