<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

$username  = trim($_POST['txtusername'] ?? '');
$password  = $_POST['txtpassword'] ?? '';
$confirmar = $_POST['txtconfirmar'] ?? '';

if ($username === '' || strlen($password) < 6) {
    $_SESSION['erro_usuario'] = 'Informe um usuário e uma senha com pelo menos 6 caracteres.';
    header('Location: cad_usuarios.php');
    exit;
}

if ($password !== $confirmar) {
    $_SESSION['erro_usuario'] = 'As senhas informadas não coincidem.';
    header('Location: cad_usuarios.php');
    exit;
}

// Verifica duplicidade antes de tentar inserir (a coluna também tem UNIQUE no banco como segunda barreira)
$stmt_check = $strcon->prepare('SELECT iduser FROM Usuarios WHERE username = ?');
$stmt_check->bind_param('s', $username);
$stmt_check->execute();
if ($stmt_check->get_result()->fetch_assoc()) {
    $_SESSION['erro_usuario'] = 'Já existe um usuário com esse nome.';
    header('Location: cad_usuarios.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $strcon->prepare('INSERT INTO Usuarios (username, userpassword) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $hash);

if ($stmt->execute()) {
    echo "<script>
            alert('Usuário cadastrado com sucesso!');
            window.location.href = '../paginas/menu.php';
          </script>";
} else {
    error_log('Erro ao cadastrar usuário: ' . $stmt->error);
    echo "<script>
            alert('Erro ao tentar cadastrar o usuário. Tente novamente.');
            window.location.href = 'cad_usuarios.php';
          </script>";
}

$stmt->close();
$strcon->close();
