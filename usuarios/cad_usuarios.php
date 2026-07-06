<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
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
        <h2 class="text-center">Cadastro de Usuário</h2>
        <div class="form-container">
            <?php if (!empty($_SESSION['erro_usuario'])): ?>
                <div class="alert alert-danger"><?php echo e($_SESSION['erro_usuario']); ?></div>
                <?php unset($_SESSION['erro_usuario']); ?>
            <?php endif; ?>
            <form action="sql_cad_usuarios.php" method="post">
                <div class="mb-3">
                    <label for="txtusername" class="form-label">Usuário</label>
                    <input type="text" id="txtusername" name="txtusername" class="form-control" required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="txtpassword" class="form-label">Senha</label>
                    <input type="password" id="txtpassword" name="txtpassword" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label for="txtconfirmar" class="form-label">Confirmar Senha</label>
                    <input type="password" id="txtconfirmar" name="txtconfirmar" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn btn-success w-100">Cadastrar Usuário</button>
            </form>
            <br>
            <a href="../paginas/menu.php" class="btn btn-outline-secondary w-100">Voltar ao Menu</a>
        </div>
    </div>

    <footer class="footer bg-success text-white text-center py-3">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
