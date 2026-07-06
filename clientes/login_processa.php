<?php
session_start();
require '../config/conexao.php';

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    $_SESSION['erro_login_cliente'] = 'Informe e-mail e senha.';
    header('Location: login.php');
    exit;
}

$stmt = $strcon->prepare('SELECT idcliente, nome, senha FROM Clientes WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

// password_verify compara a senha digitada com o hash salvo no cadastro
if (!$cliente || !password_verify($senha, $cliente['senha'])) {
    $_SESSION['erro_login_cliente'] = 'E-mail ou senha inválidos.';
    header('Location: login.php');
    exit;
}

session_regenerate_id(true); // evita fixação de sessão após autenticar
$_SESSION['cliente_id']   = $cliente['idcliente'];
$_SESSION['cliente_nome'] = $cliente['nome'];

// Se o cliente veio de uma ação que exigia login (ex: adicionar ao carrinho),
// volta para lá depois de logar. Senão, vai para a página inicial.
if (!empty($_SESSION['redirecionar_apos_login'])) {
    $destino = $_SESSION['redirecionar_apos_login'];
    unset($_SESSION['redirecionar_apos_login']);
    header('Location: ' . $destino);
    exit;
}

header('Location: ../index.php');
exit;
