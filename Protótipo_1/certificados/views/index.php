<?php

session_start();

// Carrega configurações do banco de dados
require_once __DIR__ . '/../config/config.php';

// Carrega o roteador principal
require_once __DIR__ . '/../core/Router.php';

// Inicia o roteamento (controlador + ação)
Router::route();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Sistema de Horas Complementares</title>
  <link rel="stylesheet" href="style0.css" />
</head>
<body>
  <header>
    <h1>Sistema de Horas Complementares FMP</h1>
  </header>

  <main>
    <div class="login-box">
      <h2>LOGIN</h2>
      <form action="index1.html" method="GET">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" />

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" />

        <button type="submit" class="btn">Entrar</button>
      </form>
    </div>
  </main>

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual">2025/2025</div>
    <img src="logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>
   
</body>
<script>
  const ano = new Date().getFullYear();
  document.getElementById("ano-atual").textContent = `2025/${ano}`;

  function redirecionar(event) {
    event.preventDefault();
    window.location.href = "index1.html";}
</script>
</html>