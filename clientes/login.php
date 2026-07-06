<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Entrar</h3>

                        <?php
                        session_start();
                        if (!empty($_SESSION['erro_login_cliente'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro_login_cliente']) . '</div>';
                            unset($_SESSION['erro_login_cliente']);
                        }
                        ?>

                        <form action="login_processa.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Entrar</button>
                        </form>

                        <p class="text-center mt-2 mb-0">
                            <a href="esqueci_senha.php">Esqueci minha senha</a>
                        </p>
                        <p class="text-center mt-2 mb-0">
                            Ainda não tem conta? <a href="cadastro.php">Criar conta</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
