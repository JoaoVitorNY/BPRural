<?php
session_start();
require '../config/conexao.php';

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro_esqueci_senha'] = 'Informe um e-mail válido.';
    header('Location: esqueci_senha.php');
    exit;
}

$stmt = $strcon->prepare('SELECT idcliente FROM Clientes WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

// Por segurança, não informamos se o e-mail existe ou não na base.
// Se existir, geramos o token; se não existir, apenas mostramos a mesma mensagem de sucesso.
if ($cliente) {
    $token  = bin2hex(random_bytes(32)); // token aleatório e único
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt_update = $strcon->prepare('UPDATE Clientes SET token_recuperacao = ?, token_expira = ? WHERE idcliente = ?');
    $stmt_update->bind_param('ssi', $token, $expira, $cliente['idcliente']);
    $stmt_update->execute();

    $link = 'redefinir_senha.php?token=' . $token;

    // Tenta enviar por e-mail. Se o servidor não tiver um envio de e-mail configurado
    // (o mais comum em ambiente de desenvolvimento/local), o mail() simplesmente falha
    // e mostramos o link na tela mesmo, só para fins de teste.
    $enviado = @mail(
        $email,
        'Redefinição de senha - BP Rural',
        "Clique no link para redefinir sua senha:\n" . $link,
        'From: nao-responda@bprural.com.br'
    );

    if (!$enviado) {
        $_SESSION['link_recuperacao'] = $link;
    }
}

$_SESSION['aviso_esqueci_senha'] = 'Se este e-mail estiver cadastrado, você receberá um link de redefinição.';
header('Location: esqueci_senha.php');
exit;
