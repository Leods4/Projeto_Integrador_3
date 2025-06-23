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
$avaliados = $avaliados ?? [];
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
        .btn-ressalva {
            background-color: #ffc107;
            color: #333;
            border-color: #ffc107;
        }
        .actions-form select, .actions-form input[type="number"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100vw;
            background: #fff;
            color: #000;
            text-align: center;
            padding: 10px 0 5px 0;
            z-index: 100;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .logo-footer {
            height: 30px;
        }
        @media (max-width: 900px) {
            header .right {
                right: 10px !important;
            }
            header .center h1 {
                font-size: 1.2em !important;
            }
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
            <h1 style="font-size:2.1em; font-weight:bold; margin: 0; text-align:center; width:100vw;">Sistema de Horas Complementares FMP</h1>
        </div>
        <div class="right" style="position: absolute; right: 40px; top: 0; height: 60px; display: flex; align-items: center;">
            <a href="/sistema_certificados1/config/Router.php?rota=logout" style="color: black; text-decoration: none; font-size: 1.2em; font-weight: bold; display: flex; align-items: center;">
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

    <div class="section-header" style="background-color: #1e2c66; padding: 10px 40px 10px 40px; border-radius: 12px 12px 0 0; color: #fff; margin-bottom: 0;">
        <div style="display: flex; align-items: center; justify-content: flex-start; flex-wrap: wrap; gap: 12px; width: 100%;">
            <span style="font-size: 1.25em; font-weight: bold; min-width: 180px;">Validação de Horas</span>
            <form class="filters" method="POST" action="/sistema_certificados1/config/Router.php?rota=validacao" style="flex:1; display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-start; align-items: center; margin: 0;">
                <label>Nome: <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" /></label>
                <label>Matrícula: <input type="text" name="matricula" value="<?= htmlspecialchars($_POST['matricula'] ?? '') ?>" /></label>
                <label>Fase:
                    <select name="fase">
                        <option value="">Selecionar</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?= $i ?>ª" <?= (($_POST['fase'] ?? '') == $i.'ª') ? 'selected' : '' ?>><?= $i ?>ª</option>
                        <?php endfor; ?>
                    </select>
                </label>
                <label>Curso:
                    <select name="curso">
                        <option value="">Selecionar</option>
                        <option value="Análise e Desenvolvimento de Sistemas" <?= (($_POST['curso'] ?? '') == 'Análise e Desenvolvimento de Sistemas') ? 'selected' : '' ?>>Análise e Desenvolvimento de Sistemas</option>
                        <option value="Administração" <?= (($_POST['curso'] ?? '') == 'Administração') ? 'selected' : '' ?>>Administração</option>
                        <option value="Pedagogia" <?= (($_POST['curso'] ?? '') == 'Pedagogia') ? 'selected' : '' ?>>Pedagogia</option>
                        <option value="Tecnologia em Processos Gerenciais" <?= (($_POST['curso'] ?? '') == 'Tecnologia em Processos Gerenciais') ? 'selected' : '' ?>>Tecnologia em Processos Gerenciais</option>
                    </select>
                </label>
                <label>Período:
                    <input type="month" name="periodo" value="<?= htmlspecialchars($_POST['periodo'] ?? '') ?>" />
                </label>
                <label>Situação:
                    <select name="status">
                        <option value="">Selecionar</option>
                        <option value="ENTREGUE" <?= (($_POST['status'] ?? '') == 'ENTREGUE') ? 'selected' : '' ?>>ENTREGUE</option>
                        <option value="APROVADO" <?= (($_POST['status'] ?? '') == 'APROVADO') ? 'selected' : '' ?>>APROVADO</option>
                        <option value="REPROVADO" <?= (($_POST['status'] ?? '') == 'REPROVADO') ? 'selected' : '' ?>>REPROVADO</option>
                        <option value="APROVADO COM RESSALVAS" <?= (($_POST['status'] ?? '') == 'APROVADO COM RESSALVAS') ? 'selected' : '' ?>>APROVADO COM RESSALVAS</option>
                    </select>
                </label>
                <button type="submit">Filtrar</button>
                <a href="/sistema_certificados1/config/Router.php?rota=validacao" class="btn-outline" style="background:#fff; color:#000; border:1px solid #ccc; padding:6px 12px; border-radius:5px; text-decoration:none;">Remover filtros</a>
            </form>
        </div>
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
                <?php 
                $todos = array_merge($certificados, $avaliados);
                if (empty($todos)): ?>
                    <tr><td colspan="13">Nenhum certificado encontrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($todos as $certificado): ?>
                        <tr>
                            <form class="actions-form" method="POST" action="/sistema_certificados1/config/Router.php?rota=processarValidacao" id="form-<?= $certificado['id'] ?>">
                                <td><?= htmlspecialchars($certificado['id']) ?></td>
                                <td><?= htmlspecialchars($certificado['matricula']) ?></td>
                                <td><?= htmlspecialchars($certificado['nome_aluno']) ?></td>
                                <td><?= htmlspecialchars($certificado['fase']) ?></td>
                                <td><?= htmlspecialchars($certificado['curso']) ?></td>
                                <td>
                                    <select name="categoria">
                                        <option value="">Selecionar</option>
                                        <option value="ACADEMICO" <?= ($certificado['categoria'] == 'ACADEMICO') ? 'selected' : '' ?>>Acadêmico</option>
                                        <option value="SOCIOCULTURAL" <?= ($certificado['categoria'] == 'SOCIOCULTURAL') ? 'selected' : '' ?>>Sociocultural</option>
                                        <option value="PROFISSIONAL" <?= ($certificado['categoria'] == 'PROFISSIONAL') ? 'selected' : '' ?>>Profissional</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="tipo" value="<?= htmlspecialchars($certificado['tipo'] ?? '') ?>" style="width: 100px;" />
                                </td>
                                <td><?= htmlspecialchars($certificado['nome_certificado']) ?></td>
                                <td>
                                    <input type="number" name="carga_horaria" value="<?= htmlspecialchars($certificado['carga_horaria']) ?>" style="width: 80px;">
                                </td>
                                <td><?= !empty($certificado['data_criacao']) ? date('m/Y', strtotime($certificado['data_criacao'])) : '-' ?></td>
                                <td><input type="text" name="observacao" value="<?= htmlspecialchars($certificado['observacao'] ?? '') ?>" style="width:100px;"></td>
                                <td>
                                    <select name="status" onchange="this.form.submit();" class="status-select <?= strtolower(str_replace([' ', '_'], ['', ''], $certificado['status'])) ?>">
                                        <option value="ENTREGUE" <?= ($certificado['status'] == 'ENTREGUE') ? 'selected' : '' ?>>ENTREGUE</option>
                                        <option value="APROVADO" <?= ($certificado['status'] == 'APROVADO') ? 'selected' : '' ?>>APROVADO</option>
                                        <option value="REPROVADO" <?= ($certificado['status'] == 'REPROVADO') ? 'selected' : '' ?>>REPROVADO</option>
                                        <option value="APROVADO COM RESSALVAS" <?= ($certificado['status'] == 'APROVADO COM RESSALVAS') ? 'selected' : '' ?>>APROVADO COM RESSALVAS</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="/sistema_certificados1/uploads/<?= htmlspecialchars(basename($certificado['arquivo'])) ?>" download>
                                        <img src="/sistema_certificados1/public/download-icon.png" alt="Ícone download" />
                                    </a>
                                </td>
                                <input type="hidden" name="id_certificado" value="<?= $certificado['id'] ?>">
                            </form>
                        </tr>
                    <?php endforeach; ?>
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

        // Aplica cor nos selects de situação
        function updateStatusSelectColor(select) {
            select.classList.remove('entregue', 'aprovado', 'reprovado', 'ressalva');
            const val = select.value.replace(/_/g, '').replace(/ /g, '').toLowerCase();
            if (val === 'entregue') select.classList.add('entregue');
            else if (val === 'aprovado') select.classList.add('aprovado');
            else if (val === 'reprovado') select.classList.add('reprovado');
            else if (val === 'aprovadocomressalvas') select.classList.add('ressalva');
        }
        document.querySelectorAll('.status-select').forEach(sel => {
            updateStatusSelectColor(sel);
            sel.addEventListener('change', function() { updateStatusSelectColor(this); });
        });
    </script>
</body>
</html> 