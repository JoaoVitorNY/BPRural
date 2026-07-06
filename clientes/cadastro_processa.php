<?php
session_start();
require '../includes/functions.php';
require '../config/conexao.php';

// Pega os dados enviados pelo formulário
$nome            = trim($_POST['nome'] ?? '');
$email           = trim($_POST['email'] ?? '');
$telefone        = trim($_POST['telefone'] ?? '');
$senha           = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Validações simples antes de mexer no banco
if ($nome === '' || $email === '' || $senha === '') {
    $_SESSION['erro_cadastro_cliente'] = 'Preencha nome, e-mail e senha.';
    header('Location: cadastro.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro_cadastro_cliente'] = 'Informe um e-mail válido.';
    header('Location: cadastro.php');
    exit;
}

if (strlen($senha) < 6) {
    $_SESSION['erro_cadastro_cliente'] = 'A senha deve ter pelo menos 6 caracteres.';
    header('Location: cadastro.php');
    exit;
}

if ($senha !== $confirmar_senha) {
    $_SESSION['erro_cadastro_cliente'] = 'As senhas não coincidem.';
    header('Location: cadastro.php');
    exit;
}

// Verifica se já existe um cliente com esse e-mail
$stmt = $strcon->prepare('SELECT idcliente FROM Clientes WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();

if ($stmt->get_result()->fetch_assoc()) {
    $_SESSION['erro_cadastro_cliente'] = 'Já existe uma conta cadastrada com esse e-mail.';
    header('Location: cadastro.php');
    exit;
}

// Nunca salvar a senha em texto puro: sempre gerar o hash
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $strcon->prepare('INSERT INTO Clientes (nome, email, senha, telefone) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $nome, $email, $senha_hash, $telefone);

if ($stmt->execute()) {
    // Já loga o cliente automaticamente após criar a conta
    $_SESSION['cliente_id']   = $stmt->insert_id;
    $_SESSION['cliente_nome'] = $nome;
    header('Location: ../index.php');
    exit;
}

$_SESSION['erro_cadastro_cliente'] = 'Erro ao criar a conta. Tente novamente.';
header('Location: cadastro.php');
exit;
