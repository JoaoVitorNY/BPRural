<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-3">Esqueci minha senha</h3>
                        <p class="text-muted text-center">Informe o e-mail cadastrado para receber o link de redefinição de senha.</p>

                        <?php
                        session_start();
                        if (!empty($_SESSION['erro_esqueci_senha'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro_esqueci_senha']) . '</div>';
                            unset($_SESSION['erro_esqueci_senha']);
                        }
                        if (!empty($_SESSION['aviso_esqueci_senha'])) {
                            echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['aviso_esqueci_senha']) . '</div>';
                            unset($_SESSION['aviso_esqueci_senha']);
                        }
                        if (!empty($_SESSION['link_recuperacao'])) {
                            // Sem servidor de e-mail configurado, exibimos o link aqui mesmo para
                            // fins de teste/demonstração. Em produção, isso seria enviado por e-mail.
                            echo '<div class="alert alert-success">Link de redefinição (modo de teste): <br>'
                               . '<a href="' . htmlspecialchars($_SESSION['link_recuperacao']) . '">'
                               . htmlspecialchars($_SESSION['link_recuperacao']) . '</a></div>';
                            unset($_SESSION['link_recuperacao']);
                        }
                        ?>

                        <form action="esqueci_senha_processa.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Enviar link de redefinição</button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            <a href="login.php">Voltar ao login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
