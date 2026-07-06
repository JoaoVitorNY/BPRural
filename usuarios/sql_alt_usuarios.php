<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

if (!isset($_GET['iduser']) || !ctype_digit($_GET['iduser'])) {
    header('Location: alt_usuarios.php');
    exit;
}
$iduser = (int) $_GET['iduser'];

$stmt = $strcon->prepare('SELECT iduser, username FROM Usuarios WHERE iduser = ?');
$stmt->bind_param('i', $iduser);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    header('Location: alt_usuarios.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['txtusername'] ?? '');
    $password  = $_POST['txtpassword'] ?? '';
    $confirmar = $_POST['txtconfirmar'] ?? '';

    if ($username === '') {
        $erro = 'Informe o nome de usuário.';
    } elseif ($password !== '' && strlen($password) < 6) {
        $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } elseif ($password !== $confirmar) {
        $erro = 'As senhas informadas não coincidem.';
    } else {
        $stmt_check = $strcon->prepare('SELECT iduser FROM Usuarios WHERE username = ? AND iduser <> ?');
        $stmt_check->bind_param('si', $username, $iduser);
        $stmt_check->execute();

        if ($stmt_check->get_result()->fetch_assoc()) {
            $erro = 'Já existe outro usuário com esse nome.';
        } else {
            if ($password !== '') {
                // Senha informada: atualiza também o hash
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt_update = $strcon->prepare('UPDATE Usuarios SET username = ?, userpassword = ? WHERE iduser = ?');
                $stmt_update->bind_param('ssi', $username, $hash, $iduser);
            } else {
                // Senha em branco: mantém a senha atual
                $stmt_update = $strcon->prepare('UPDATE Usuarios SET username = ? WHERE iduser = ?');
                $stmt_update->bind_param('si', $username, $iduser);
            }

            if ($stmt_update->execute()) {
                echo "<script>alert('Usuário atualizado com sucesso!'); window.location.href='alt_usuarios.php';</script>";
                exit;
            }
            $erro = 'Erro ao atualizar o usuário. Tente novamente.';
        }
    }
    $usuario['username'] = $username;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../images/New_Logo_BPRURAL.37.jpeg" alt="Logo" height="40" class="d-inline-block align-text-top">
                BP Rural
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../paginas/menu.php">Voltar ao Menu</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="text-center">Alterar Usuário</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger" style="max-width:500px;margin:0 auto 1rem;"><?php echo e($erro); ?></div>
        <?php endif; ?>

        <form action="sql_alt_usuarios.php?iduser=<?php echo $iduser; ?>" method="POST" style="max-width:500px;margin:0 auto;">
            <div class="mb-3">
                <label for="txtusername" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="txtusername" name="txtusername" value="<?php echo e($usuario['username']); ?>" required maxlength="50">
            </div>
            <div class="mb-3">
                <label for="txtpassword" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="txtpassword" name="txtpassword" minlength="6">
                <div class="form-text">Deixe em branco para manter a senha atual.</div>
            </div>
            <div class="mb-3">
                <label for="txtconfirmar" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="txtconfirmar" name="txtconfirmar" minlength="6">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Atualizar Usuário</button>
            </div>
        </form>

        <!-- Botão Voltar -->
        <div class="text-center mt-3">
            <a href="../paginas/menu.php" class="btn btn-secondary">Voltar à Lista de Usuários</a>
        </div>
    </div>

    <footer class="footer bg-success text-white text-center py-3">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
