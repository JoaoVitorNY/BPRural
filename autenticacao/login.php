<?php
session_start();

require '../includes/functions.php';
require '../config/conexao.php';

$usuario = $_POST['user'] ?? '';
$senha   = $_POST['password'] ?? '';

if ($usuario === '' || $senha === '') {
    echo "<script>alert('Informe usuário e senha.'); window.location.href='login.html';</script>";
    exit;
}

// Busca o usuário administrador no banco de dados
$stmt = $strcon->prepare('SELECT iduser, username, userpassword FROM Usuarios WHERE username = ?');
$stmt->bind_param('s', $usuario);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();

// Comparação direta de texto limpo (sem password_verify)
if ($dados && $senha === $dados['userpassword']) {
    session_regenerate_id(true); // Mantém a segurança da sessão
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $dados['username'];
    header('Location: ../paginas/menu.php');
    exit;
}

// Se não encontrar ou a senha estiver errada
echo "<script>alert('Usuário ou senha inválidos.'); window.location.href='login.html';</script>";

$stmt->close();
$strcon->close();