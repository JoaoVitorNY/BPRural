<?php
/**
 * Conexão com o banco de dados MySQL.
 *
 * Em produção, sugere-se mover estas credenciais para variáveis de ambiente
 * (ex.: getenv('DB_HOST')) em vez de deixá-las fixas no código-fonte.
 */

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "bprural";
$port       = 3306;

mysqli_report(MYSQLI_REPORT_OFF); // erros são tratados manualmente abaixo, sem expor detalhes ao usuário

$strcon = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$strcon) {
    // Detalhes completos do erro vão para o log do servidor, nunca para a tela
    error_log('Falha na conexão com o banco de dados: ' . mysqli_connect_error());
    http_response_code(500);
    die('Não foi possível conectar ao sistema no momento. Tente novamente mais tarde.');
}

mysqli_set_charset($strcon, 'utf8mb4');
