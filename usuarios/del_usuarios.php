<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

if (isset($_GET['iduser']) && ctype_digit($_GET['iduser'])) {
    $iduser = (int) $_GET['iduser'];

    $stmt_busca = $strcon->prepare('SELECT username FROM Usuarios WHERE iduser = ?');
    $stmt_busca->bind_param('i', $iduser);
    $stmt_busca->execute();
    $usuario = $stmt_busca->get_result()->fetch_assoc();

    // Impede que o usuário logado exclua a própria conta e fique sem acesso
    if ($usuario && $usuario['username'] === $_SESSION['username']) {
        echo "<script>alert('Você não pode excluir o usuário com o qual está logado.'); window.location.href='del_usuarios.php';</script>";
        exit;
    }

    $stmt = $strcon->prepare('DELETE FROM Usuarios WHERE iduser = ?');
    $stmt->bind_param('i', $iduser);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='del_usuarios.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o usuário. Tente novamente.'); window.location.href='del_usuarios.php';</script>";
    }
    exit;
}

$result = $strcon->query('SELECT iduser, username FROM Usuarios ORDER BY username');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuários - BP Rural</title>
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
        <h2 class="text-center">Lista de Usuários</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int) $row['iduser']; ?></td>
                        <td><?php echo e($row['username']); ?></td>
                        <td>
                            <a href="del_usuarios.php?iduser=<?php echo (int) $row['iduser']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Botão Voltar -->
        <div class="text-center mt-3">
            <a href="../paginas/menu.php" class="btn btn-secondary">Voltar ao Menu</a>
        </div>
    </div>

    <footer class="footer bg-success text-white text-center py-3">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
