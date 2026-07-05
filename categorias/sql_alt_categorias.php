<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

// Verifica se foi passado o ID da categoria para editar
if (!isset($_GET['idcategoria']) || !ctype_digit($_GET['idcategoria'])) {
    header('Location: ../paginas/menu.php');
    exit;
}
$idcategoria = (int) $_GET['idcategoria'];

$stmt = $strcon->prepare("SELECT * FROM Categorias WHERE idcategoria = ?");
$stmt->bind_param('i', $idcategoria);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();

if (!$categoria) {
    header('Location: ../paginas/menu.php');
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomecategoria = trim($_POST['txtnome'] ?? '');

    if ($nomecategoria === '') {
        $erro = 'Informe o nome da categoria.';
    } else {
        $sql_update = "UPDATE Categorias SET nomecategoria = ? WHERE idcategoria = ?";
        $stmt_update = $strcon->prepare($sql_update);
        $stmt_update->bind_param('si', $nomecategoria, $idcategoria);

        if ($stmt_update->execute()) {
            echo "<script>alert('Categoria atualizada com sucesso!'); window.location.href='alt_categorias.php';</script>";
            exit;
        }
        $erro = 'Erro ao atualizar a categoria. Tente novamente.';
    }
    $categoria['nomecategoria'] = $nomecategoria;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Categoria - BP Rural</title>
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
                    <li class="nav-item"><a class="nav-link" href="../paginas/tatuadeiras.php">Tatuadeiras</a></li>
                    <li class="nav-item"><a class="nav-link" href="../paginas/tintas.php">Tintas e Pastas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../paginas/brincos.php">Brincos e Aplicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../paginas/marcadores.php">Marcadores e Fogareiros</a></li>
                    <li class="nav-item"><a class="nav-link" href="../paginas/diversos.php">Diversos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../paginas/menu.php">Voltar ao Menu</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="text-center">Alterar Categoria</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger" style="max-width:500px;margin:0 auto 1rem;"><?php echo e($erro); ?></div>
        <?php endif; ?>

        <form action="sql_alt_categorias.php?idcategoria=<?php echo $idcategoria; ?>" method="POST" style="max-width:500px;margin:0 auto;">
            <div class="mb-3">
                <label for="txtnome" class="form-label">Nome da Categoria</label>
                <input type="text" class="form-control" id="txtnome" name="txtnome" value="<?php echo e($categoria['nomecategoria']); ?>" required maxlength="100">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Atualizar Categoria</button>
            </div>
        </form>

        <!-- Botão Voltar -->
        <div class="text-center mt-3">
            <a href="../paginas/menu.php" class="btn btn-secondary">Voltar à Lista de Categorias</a>
        </div>
    </div>

    <footer class="footer bg-success text-white text-center py-3">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
