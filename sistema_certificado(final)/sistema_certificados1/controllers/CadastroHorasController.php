<?php

require_once __DIR__ . '/../config/Session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Certificado.php';
require_once __DIR__ . '/../models/Aluno.php';


class CadastroHorasController {

    public static function index() {
        Session::start();
        if (!Session::get('usuario_id')) {
            // Se não estiver logado, redireciona para login
            header('Location: /sistema_certificados1/views/login.php?erro=nao_logado');
            exit;
        }
        // Carrega a view de cadastro
        require_once __DIR__ . '/../views/cadastroHoras.php';
    }

    public static function cadastrar(){
        // Iniciar a sessão para obter o ID do aluno
        Session::start();
        // Protege a rota
        if (!Session::get('usuario_id')) {
            header('Location: /sistema_certificados1/views/login.php?erro=nao_logado');
            exit;
        }

        // Debug: Verificar se a sessão está funcionando
        error_log("DEBUG: Método da requisição: " . $_SERVER['REQUEST_METHOD']);
        error_log("DEBUG: Sessão usuario_id: " . ($_SESSION['usuario_id'] ?? 'NÃO DEFINIDO'));
        error_log("DEBUG: Sessão completa: " . print_r($_SESSION, true));

        // Verificar se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            // Se não estiver logado, redirecionar para a página de login com uma mensagem de erro
            header('Location: /sistema_certificados/views/login.php?erro=nao_logado');
            exit;
        }

        // Validar se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/cadastroHoras.html');
            exit;
        }

        // --- Receber e validar dados do formulário ---
        $requerente_id = $_SESSION['usuario_id'];
        $nome_evento = $_POST['evento'] ?? null;
        $instituicao = $_POST['instituicao'] ?? null;
        // O campo 'ano' do formulário será usado para 'data_emissao'
        $ano_emissao = $_POST['ano'] ?? null; 
        $comprovante = $_FILES['comprovante'] ?? null;

        if (empty($nome_evento) || empty($instituicao) || empty($ano_emissao) || !isset($comprovante) || $comprovante['error'] !== UPLOAD_ERR_OK) {
            header('Location: ../views/cadastroHoras.html?erro=campos_vazios');
            exit;
        }

        // --- Lógica de Upload ---
        $upload_dir = __DIR__ . '/../uploads/';
        $file_extension = pathinfo($comprovante['name'], PATHINFO_EXTENSION);
        // Validar extensão (ex: permitir apenas PDF)
        if (strtolower($file_extension) !== 'pdf') {
            header('Location: ../views/cadastroHoras.html?erro=formato_invalido');
            exit;
        }

        $nome_arquivo_unico = uniqid('cert_', true) . '.' . $file_extension;
        $caminho_arquivo = $upload_dir . $nome_arquivo_unico;

        if (!move_uploaded_file($comprovante['tmp_name'], $caminho_arquivo)) {
            header('Location: ../views/cadastroHoras.html?erro=falha_upload');
            exit;
        }

        // --- Inserção no Banco de Dados ---
        try {
            $db = Database::conectar();
            $certificado = new Certificado($db);

            // Preencher o objeto Certificado com os dados
            // Alguns campos serão preenchidos com valores padrão
            $certificado->setRequerenteId((int)$requerente_id);
            $certificado->setNomeCertificado($nome_evento);
            $certificado->setInstituicao($instituicao);
            $certificado->setArquivo($nome_arquivo_unico); // Salva apenas o nome do arquivo, não o caminho completo
            $certificado->setDataEmissao($ano_emissao . "-01-01"); // Usa o ano para criar uma data
            $certificado->setDataCriacao(date('Y-m-d H:i:s')); // Data atual
            $certificado->setStatus('ENTREGUE');
            
            // Buscar curso do perfil do aluno
            $aluno = new Aluno($db);
            $aluno->setId((int)$requerente_id);
            $detalhes = $aluno->buscarDetalhes();
            $curso_aluno = $detalhes['curso'] ?? '';
            $certificado->setCategoria('A SER DEFINIDO'); // Admin define depois
            $certificado->setCurso($curso_aluno); // Agora puxa do perfil do aluno
            $certificado->setCargaHoraria(0); // Admin define depois
            $certificado->setObservacao('');
            $certificado->setPrazoFinal(date('Y-m-d', strtotime('+1 year'))); // Ex: prazo de 1 ano
            $certificado->setIniciouAtividade('1'); // Assumindo que sim


            if ($certificado->salvar()) {
                header('Location: ../config/Router.php?rota=historico');
                exit;
            } else {
                // Se falhar, apagar o arquivo que foi salvo
                unlink($caminho_arquivo);
                header('Location: ../views/cadastroHoras.html?erro=db_erro');
                exit;
            }
        } catch (Exception $e) {
            // Em caso de erro de banco, também apagar o arquivo e logar o erro
            if (isset($caminho_arquivo) && file_exists($caminho_arquivo)) {
                unlink($caminho_arquivo);
            }
            // Logar o erro para depuração
            error_log("Erro no cadastro de certificado: " . $e->getMessage());
            header('Location: ../views/cadastroHoras.html?erro=db_exception');
            exit;
        } 
    }
}