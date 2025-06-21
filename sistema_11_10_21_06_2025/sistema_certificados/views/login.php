<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Login - Sistema de Horas Complementares</title>
  <link rel="stylesheet" href="/sistema_certificados/public/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
  <header class="header-base flex-end-layout">
    <div class="center">
      <h1>Sistema de Horas Complementares FMP</h1>
    </div>
  </header>

  <main>
    <div class="login-box">

      <img src="/sistema_certificados/public/logo.png" alt="Logo FMP" class="logo" />


      <h2>LOGIN</h2>
  
      <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p style="color: red; text-align: center;">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']); // Limpa a mensagem de erro da sessão
        }
      ?>

      <form action="/sistema_certificados/config/Router.php?rota=login_in" method="POST">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required />
  
        <label for="senha">Senha:</label>
        <div class="senha-container">
          <input type="password" id="senha" name="senha" required />
          <i id="toggleSenha" class="fa-solid fa-eye-slash toggle-senha" onclick="toggleSenha()"></i>
        </div>
  
        <button type="submit" class="btn">Entrar</button>
      </form>

    </div>
  </main>
  

  <footer>
    <div>3ª Fase de ADS</div>
    <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
    <div id="ano-atual"></div>
    <img src="/sistema_certificados/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
  </footer>
   
</body>
<script>
  const ano = new Date().getFullYear();
  document.getElementById("ano-atual").textContent = `2025/${ano}`;

  function toggleSenha() {
    const senhaInput = document.getElementById("senha");
    const icon = document.getElementById("toggleSenha");
  
    if (senhaInput.type === "password") {
      senhaInput.type = "text";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    } else {
      senhaInput.type = "password";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  }
  
</script>
</html>