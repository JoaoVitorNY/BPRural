<?php
session_start();

require '../includes/functions.php';
require '../config/conexao.php';

$usuario = trim($_POST['user'] ?? '');
$senha   = $_POST['password'] ?? '';

if ($usuario === '' || $senha === '') {
    echo "<script>alert('Informe usuário e senha.'); window.location.href='login.html';</script>";
    exit;
}

$stmt = $strcon->prepare('SELECT iduser, username, userpassword FROM Usuarios WHERE username = ?');
$stmt->bind_param('s', $usuario);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();

// Comparação direta: a senha no banco está salva em texto puro (sem hash).
if ($dados && $senha === $dados['userpassword']) {
    session_regenerate_id(true); // previne fixação de sessão após autenticar
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $dados['username'];
    header('Location: ../paginas/menu.php');
    exit;
}

echo "<script>alert('Usuário ou senha inválidos.'); window.location.href='login.html';</script>";

$stmt->close();
$strcon->close();
