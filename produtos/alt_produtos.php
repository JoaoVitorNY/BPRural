<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

$sql = "SELECT p.idproduto, p.nomeproduto, p.preco, p.quantidade, p.foto, c.nomecategoria
        FROM Produtos p
        JOIN Categorias c ON p.idcategoria = c.idcategoria
        ORDER BY p.nomeproduto";
$result = $strcon->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos - BP Rural</title>
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
        <h2 class="text-center">Lista de Produtos</h2>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produto = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo e(urlImagemProduto($produto['foto'], '../')); ?>"
                                 alt="<?php echo e($produto['nomeproduto']); ?>"
                                 style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                        </td>
                        <td><?php echo e($produto['nomeproduto']); ?></td>
                        <td>R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo (int) $produto['quantidade']; ?></td>
                        <td><?php echo e($produto['nomecategoria']); ?></td>
                        <td>
                            <a href="sql_alt_produtos.php?idproduto=<?php echo (int) $produto['idproduto']; ?>" class="btn btn-warning">Alterar</a>
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
