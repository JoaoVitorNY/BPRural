<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Criar Conta</h3>

                        <?php
                        session_start();
                        // Se veio algum erro do processamento, mostra aqui
                        if (!empty($_SESSION['erro_cadastro_cliente'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro_cadastro_cliente']) . '</div>';
                            unset($_SESSION['erro_cadastro_cliente']);
                        }
                        ?>

                        <form action="cadastro_processa.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nome completo</label>
                                <input type="text" name="nome" class="form-control" required maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" required maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control" maxlength="20">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar senha</label>
                                <input type="password" name="confirmar_senha" class="form-control" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Criar conta</button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            Já tem uma conta? <a href="login.php">Entrar</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
