<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Horas Complementares</title>
  <link rel="stylesheet" href="/sistema_certificados1/public/cadastroHoras.css">
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
    <div class="form-box">
      <h2>Cadastrar Horas Complementares</h2>
      <form action="/sistema_certificados1/config/Router.php?rota=cadastrar" method="POST" enctype="multipart/form-data">
        <div class="form-line">
            <label for="evento">Nome Evento - Curso - Atividade:</label>
            <input type="text" id="evento" name="evento" required />
        </div>
        <div class="form-line">
            <label for="instituicao">Instituição:</label>
            <input type="text" id="instituicao" name="instituicao" required />
        </div>
        <div class="form-line">
            <label for="ano">Ano:</label>
            <select id="ano" name="ano" required>
                <option value="">Selecionar</option>
                <!-- Options will be populated by JS -->
            </select>
        </div>
        <div class="form-line">
          <label for="comprovante">Upload do comprovante:</label>
          <div class="upload-wrapper">
            <input type="file" id="comprovante" name="comprovante" required>
            <img src="/sistema_certificados1/public/upload-icon.png" alt="ícone upload">
          </div>
        </div>      

        <button type="submit">Enviar</button>
      </form>
    </div>
  </main>

  <div id="popup-sucesso" class="popup oculto">
    <p>Horas Cadastradas com Sucesso!</p>
  </div>

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual">2025/AnoAtual</div>
    <img src="/sistema_certificados1/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>

  <script>
    const ano = new Date().getFullYear();
    document.getElementById("ano-atual").textContent = `2025/${ano}`;

    // Popula o seletor de anos
    const selectAno = document.getElementById('ano');
    const anoAtual = new Date().getFullYear();
    for (let i = anoAtual; i >= anoAtual - 5; i--) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = i;
      selectAno.appendChild(option);
    }
  </script>
</body>
</html>
