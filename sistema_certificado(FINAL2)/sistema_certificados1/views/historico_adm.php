<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
  }
// Se houver resultados do filtro, use-os. Caso contrário, apenas exibe a tabela vazia.
$resultados = $resultados ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Horas</title>
  <link rel="stylesheet" href="/sistema_certificados1/public/historico_validar.css">
</head>
<body>
  <header>
    <div class="left">
      <a href="/sistema_certificados1/config/Router.php?rota=inicio" style="color: black; text-decoration: none;">
        <img src="/sistema_certificados1/public/home-icon.png" alt="Home" class="home-icon"> Início
      </a>
    </div>
  
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  
    <div class="right">
      <a href="/sistema_certificados1/config/Router.php?rota=logout">
        <img src="/sistema_certificados1/public/logout-icon.png" alt="Sair" class="logout-icon"> Sair
      </a>
    </div>
  </header>
  
  <div class="section-header">
    <form class="filters" method="POST" action="/sistema_certificados1/config/Router.php?rota=filtrar">
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
          <option value="Análise e Desenvolvimento de Sistemas">Análise e Desenvolvimento de Sistemas</option>
          <option value="Administração">Administração</option>
          <option value="Pedagogia">Pedagogia</option>
          <option value="Tecnologia em Processos Gerenciais">Tecnologia em Processos Gerenciais</option>
        </select>
      </label>
      <label>Período:
        <input type="month" name="periodo" />
      </label>
      <button type="submit" class="btn">Filtrar</button>
      <button type="reset" class="btn-outline" id="btn-reset">Remover filtros</button>
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
              <td>
                <span class="status-label <?php
                  $status = strtolower(str_replace([' ', '-', 'ç', 'ã', 'é'], ['_', '', 'c', 'a', 'e'], $linha['status'] ?? ''));
                  if ($status === 'aprovado_com_ressalvas') echo 'aprovado_com_ressalvas';
                  elseif ($status === 'aprovado') echo 'aprovado';
                  elseif ($status === 'reprovado') echo 'reprovado';
                  elseif ($status === 'entregue') echo 'entregue';
                  else echo '';
                ?>">
                  <?= strtoupper(str_replace('_', ' ', htmlspecialchars($linha['status'] ?? ''))) ?>
                </span>
              </td>
              <td>
                <?php if (!empty($linha['arquivo'])): ?>
                  <a href="/sistema_certificados1/uploads/<?= htmlspecialchars(basename($linha['arquivo'])) ?>" download><img src="/sistema_certificados1/public/download-icon.png" alt="Ícone download" /></a>
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
    // Submete o formulário após resetar os filtros
    document.getElementById('btn-reset').addEventListener('click', function(e) {
      var form = this.form;
      setTimeout(function() {
        form.submit();
      }, 10);
    });
  </script>
</body>
</html> 