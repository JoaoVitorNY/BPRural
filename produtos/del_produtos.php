<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

// Verifica se foi passado o ID do produto para excluir
if (isset($_GET['idproduto']) && ctype_digit($_GET['idproduto'])) {
    $id_produto = (int) $_GET['idproduto'];

    // Busca o nome do arquivo de foto antes de excluir o registro
    $stmt_busca = $strcon->prepare("SELECT foto FROM Produtos WHERE idproduto = ?");
    $stmt_busca->bind_param('i', $id_produto);
    $stmt_busca->execute();
    $produto = $stmt_busca->get_result()->fetch_assoc();

    $sql = "DELETE FROM Produtos WHERE idproduto = ?";
    $stmt = $strcon->prepare($sql);
    $stmt->bind_param('i', $id_produto);

    if ($stmt->execute()) {
        if ($produto) {
            removerImagemProduto($produto['foto']);
        }
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='del_produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o produto. Tente novamente.'); window.location.href='del_produtos.php';</script>";
    }
    exit;
}

// Listar produtos (caso não tenha sido enviado o ID para exclusão)
$result = $strcon->query("SELECT idproduto, nomeproduto, preco, quantidade, foto FROM Produtos ORDER BY nomeproduto");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produtos - BP Rural</title>
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo e(urlImagemProduto($row['foto'], '../')); ?>"
                                 alt="<?php echo e($row['nomeproduto']); ?>"
                                 style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                        </td>
                        <td><?php echo e($row['nomeproduto']); ?></td>
                        <td>R$ <?php echo number_format((float) $row['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo (int) $row['quantidade']; ?></td>
                        <td>
                            <a href="del_produtos.php?idproduto=<?php echo (int) $row['idproduto']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
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
