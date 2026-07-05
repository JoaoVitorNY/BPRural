<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

$categorias = mysqli_query($strcon, "SELECT idcategoria, nomecategoria FROM Categorias ORDER BY nomecategoria");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos - BP Rural</title>
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
        <h2 class="text-center">Cadastro de Produto</h2>
        <div class="form-container bg-white p-4 rounded shadow-sm" style="max-width:600px;margin:0 auto;">

            <?php if (!empty($_SESSION['erro_produto'])): ?>
                <div class="alert alert-danger"><?php echo e($_SESSION['erro_produto']); ?></div>
                <?php unset($_SESSION['erro_produto']); ?>
            <?php endif; ?>

            <form action="sql_cad_produtos.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="txtnome" class="form-label">Nome do Produto</label>
                    <input type="text" id="txtnome" name="txtnome" class="form-control" required maxlength="150">
                </div>
                <div class="mb-3">
                    <label for="txtpreco" class="form-label">Preço</label>
                    <input type="number" id="txtpreco" name="txtpreco" class="form-control" required step="0.01" min="0">
                </div>
                <div class="mb-3">
                    <label for="txtquantidade" class="form-label">Quantidade</label>
                    <input type="number" id="txtquantidade" name="txtquantidade" class="form-control" required min="0">
                </div>
                <div class="mb-3">
                    <label for="txtdescricao" class="form-label">Descrição do Produto</label>
                    <textarea id="txtdescricao" name="txtdescricao" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="txtfoto" class="form-label">Foto do Produto</label>
                    <input type="file" id="txtfoto" name="txtfoto" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                    <div class="form-text">Formatos aceitos: JPG, PNG ou WEBP (máx. 5 MB).</div>
                </div>
                <div class="mb-3">
                    <label for="cbocategoria" class="form-label">Categoria</label>
                    <select id="cbocategoria" name="cbocategoria" class="form-select" required>
                        <option value="">Selecione a categoria</option>
                        <?php while ($categoria = mysqli_fetch_assoc($categorias)): ?>
                            <option value="<?php echo (int) $categoria['idcategoria']; ?>">
                                <?php echo e($categoria['nomecategoria']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Cadastrar Produto</button>
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
