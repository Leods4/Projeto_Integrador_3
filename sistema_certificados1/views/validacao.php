<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Garante que o admin está logado
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: /sistema_certificados1/config/Router.php?rota=login');
    exit;
}
// Garante que $certificados está definido
$certificados = $certificados ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Validar Horas Complementares</title>
    <link rel="stylesheet" href="/sistema_certificados1/public/historico_validar.css">
    <style>
        .actions-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .actions-form button {
            padding: 5px 10px;
            cursor: pointer;
            border: 1px solid;
            border-radius: 5px;
        }
        .btn-approve {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        .actions-form select, .actions-form input[type="number"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
        <div class="left">
            <a href="/sistema_certificados1/config/Router.php?rota=inicio" style="color: black; text-decoration: none;">
                <img src="/sistema_certificados1/public/home-icon.png" alt="Home" class="home-icon"> Início
            </a>
        </div>
        <div class="center">
            <h1>Validação de Horas Complementares</h1>
        </div>
        <div class="right">
            <a href="/sistema_certificados1/config/Router.php?rota=logout">
                <img src="/sistema_certificados1/public/logout-icon.png" alt="Sair" class="logout-icon"> Sair
            </a>
        </div>
    </header>

    <?php
        if (isset($_SESSION['validation_error'])) {
            echo '<p style="color: red; text-align: center; margin-top: 10px;">' . $_SESSION['validation_error'] . '</p>';
            unset($_SESSION['validation_error']); 
        }
    ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Curso</th>
                    <th>Nome Certificado</th>
                    <th>Carga Horária</th>
                    <th>Área</th>
                    <th>Horas</th>
                    <th>Download</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($certificados)): ?>
                    <tr><td colspan="8">Nenhum certificado pendente de validação.</td></tr>
                <?php else: ?>
                    <?php foreach ($certificados as $certificado): ?>
                        <tr>
                            <td><?= htmlspecialchars($certificado['nome_aluno']) ?></td>
                            <td><?= htmlspecialchars($certificado['curso']) ?></td>
                            <td><?= htmlspecialchars($certificado['nome_certificado']) ?></td>
                            <td><?= htmlspecialchars($certificado['carga_horaria']) ?></td>
                            <td>
                                <select name="categoria" form="form-<?= $certificado['id'] ?>">
                                    <option value="">Selecione</option>
                                    <option value="ACADEMICO">Acadêmico</option>
                                    <option value="SOCIOCULTURAL">Sociocultural</option>
                                    <option value="PROFISSIONAL">Profissional</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="carga_horaria" value="<?= htmlspecialchars($certificado['carga_horaria']) ?>" form="form-<?= $certificado['id'] ?>" title="Horas a serem atribuídas" style="width: 80px;">
                            </td>
                            <td>
                                <a href="/sistema_certificados1/uploads/<?= htmlspecialchars(basename($certificado['arquivo'])) ?>" download>
                                    <img src="/sistema_certificados1/public/download-icon.png" alt="Ícone download" />
                                </a>
                            </td>
                            <td>
                                <form class="actions-form" method="POST" action="/sistema_certificados1/config/Router.php?rota=processarValidacao" id="form-<?= $certificado['id'] ?>">
                                    <input type="hidden" name="id_certificado" value="<?= $certificado['id'] ?>">
                                    <button type="submit" name="acao" value="aprovar" class="btn-approve">Aprovar</button>
                                    <button type="submit" name="acao" value="reprovar" class="btn-reject">Reprovar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <div>3ª Fase de ADS</div>
        <div>Alunos: Leonardo de Oliveira, Maria Eduarda Schmidt e Philipe Jean da Silva</div>
        <div id="ano-atual"></div>
        <img src="/sistema_certificados1/public/logo-fmp.png" alt="Logo FMP" class="logo-footer" />
    </footer>

    <script>
        document.getElementById("ano-atual").textContent = `2025/${new Date().getFullYear()}`;
    </script>
</body>
</html> 