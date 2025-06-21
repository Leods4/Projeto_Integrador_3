<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A variável correta agora é $perfil, conforme retornado pelo controller
if (!isset($perfil)) {
    echo "Erro: dados do perfil não foram carregados.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Perfil</title>
  <link rel="stylesheet" href="/sistema_certificados/public/perfil.css" />
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
    <div class="profile-box">
      <h2>Perfil do Aluno</h2>
      <form>
        <div class="form-line">
          <label for="nome">Nome Completo:</label>
          <input type="text" id="nome" value="<?= htmlspecialchars($perfil['nome'] ?? '') ?>" disabled />
        </div>
        <div class="form-line">
          <label for="email">E-mail Acadêmico:</label>
          <input type="text" id="email" value="<?= htmlspecialchars($perfil['email'] ?? '') ?>" disabled />
        </div>
        <div class="form-line">
          <label for="matricula">Matrícula:</label>
          <input type="text" id="matricula" value="<?= htmlspecialchars($perfil['matricula'] ?? '') ?>" disabled />
        </div>
      </form>
    </div>
  </main>

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual">2025/AnoAtual</div>
    <img src="/sistema_certificados/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>

  <script>
    document.getElementById("ano-atual").textContent = `2025/${new Date().getFullYear()}`;
  </script>
</body>
</html>
