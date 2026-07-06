<?php
session_start();
require '../config/conexao.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($token === '') {
    header('Location: esqueci_senha.php');
    exit;
}

// Busca um cliente cujo token seja esse E que ainda não tenha expirado
$stmt = $strcon->prepare('SELECT idcliente FROM Clientes WHERE token_recuperacao = ? AND token_expira > NOW()');
$stmt->bind_param('s', $token);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

if (!$cliente) {
    $erro_token = 'Este link de redefinição é inválido ou já expirou. Solicite um novo.';
}

if ($cliente && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha     = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    if (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Ao trocar a senha, o token é apagado para não poder ser usado de novo
        $stmt_update = $strcon->prepare('UPDATE Clientes SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE idcliente = ?');
        $stmt_update->bind_param('si', $senha_hash, $cliente['idcliente']);
        $stmt_update->execute();

        echo "<script>alert('Senha redefinida com sucesso! Faça login com a nova senha.'); window.location.href='login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Redefinir Senha</h3>

                        <?php if (!empty($erro_token)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($erro_token); ?></div>
                            <p class="text-center"><a href="esqueci_senha.php">Solicitar novo link</a></p>
                        <?php else: ?>
                            <?php if (!empty($erro)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                            <?php endif; ?>
                            <form action="redefinir_senha.php" method="POST">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                <div class="mb-3">
                                    <label class="form-label">Nova senha</label>
                                    <input type="password" name="senha" class="form-control" required minlength="6">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirmar nova senha</label>
                                    <input type="password" name="confirmar_senha" class="form-control" required minlength="6">
                                </div>
                                <button type="submit" class="btn btn-success w-100">Redefinir Senha</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
