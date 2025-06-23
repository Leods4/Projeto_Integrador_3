<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Horas Complementares</title>
  <link rel="stylesheet" href="/sistema_certificados1/public/inicio.css" />
</head>
<body>
  <header>
  
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  
    <div class="right">
        <a href="/sistema_certificados1/config/Router.php?rota=logout"><img src="/sistema_certificados1/public/logout-icon.png" alt="Sair" class="logout-icon"> Sair</a>
    </div>
  </header>    

  <main>
    <div class="card-container">
      
      <div class="card" >
        <img src="/sistema_certificados1/public/icon-historico.png" alt="Histórico de Horas" />
        <button class="btn" onclick="location.href='/sistema_certificados1/config/Router.php?rota=historico'">Histórico de Horas Complementares</button>
      </div>
  
      <div class="card">
        <img src="/sistema_certificados1/public/icon-validar.png" alt="Cadastrar Horas" />
        <button class="btn" onclick="location.href='/sistema_certificados1/config/Router.php?rota=validacao'">Validar Horas Complementares</button>
      </div>
  
      <div class="card">
        <img src="/sistema_certificados1/public/icon-perfil.png" alt="Perfil" />
        <button class="btn" onclick="location.href='/sistema_certificados1/config/Router.php?rota=perfil'">Perfil</button>
      </div>
  
    </div>
  </main>  

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual">2025/AnoAtual</div>
    <img src="/sistema_certificados1/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>

  <script>
    const ano = new Date().getFullYear();
    document.getElementById("ano-atual").textContent = `2025/${ano}`;
  </script>
</body>
</html>