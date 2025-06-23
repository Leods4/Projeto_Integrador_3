<?php
/**
 * Ponto de entrada da aplicação.
 * 
 * Este arquivo é responsável por iniciar a sessão e redirecionar
 * o usuário para o controlador principal (roteador).
 */

session_start();

header('Location: /sistema_certificados1/config/Router.php?rota=inicio');
exit;
