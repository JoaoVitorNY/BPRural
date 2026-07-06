<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLoginCliente('../clientes/login.php');

require '../config/conexao.php';

// Se o formulário de quantidades foi enviado, atualiza o carrinho antes de exibir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantidades'])) {
    foreach ($_POST['quantidades'] as $idproduto => $quantidade) {
        $idproduto  = (int) $idproduto;
        $quantidade = (int) $quantidade;

        if ($quantidade <= 0) {
            unset($_SESSION['carrinho'][$idproduto]);
        } else {
            $_SESSION['carrinho'][$idproduto] = $quantidade;
        }
    }
    header('Location: carrinho.php');
    exit;
}

$itensCarrinho = $_SESSION['carrinho'] ?? [];
$produtosCarrinho = [];
$total = 0;

// Busca no banco os dados atuais de cada produto que está no carrinho
foreach ($itensCarrinho as $idproduto => $quantidade) {
    $stmt = $strcon->prepare('SELECT idproduto, nomeproduto, preco, foto FROM Produtos WHERE idproduto = ?');
    $stmt->bind_param('i', $idproduto);
    $stmt->execute();
    $produto = $stmt->get_result()->fetch_assoc();

    // Se o produto foi excluído do catálogo depois de ter sido adicionado ao carrinho, remove ele daqui
    if (!$produto) {
        unset($_SESSION['carrinho'][$idproduto]);
        continue;
    }

    $produto['quantidade'] = $quantidade;
    $produto['subtotal']   = $produto['preco'] * $quantidade;
    $total += $produto['subtotal'];

    $produtosCarrinho[] = $produto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/site.css" rel="stylesheet">
    <style>
        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../images/New_Logo_BPRURAL.37.jpeg" alt="Logo" height="40" class="d-inline-block align-text-top">
                BP Rural
            </a>
            <div class="ms-auto">
                <span class="text-white me-3">Olá, <?php echo e($_SESSION['cliente_nome']); ?></span>
                <a href="../clientes/logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="text-center mb-4">Meu Carrinho</h2>

        <?php if (empty($produtosCarrinho)): ?>
            <p class="text-center">Seu carrinho está vazio.</p>
            <div class="text-center">
                <a href="../index.php" class="btn btn-success">Ver Produtos</a>
            </div>
        <?php else: ?>
            <form action="carrinho.php" method="POST">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Produto</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtosCarrinho as $produto): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo e(urlImagemProduto($produto['foto'], '../')); ?>"
                                         alt="<?php echo e($produto['nomeproduto']); ?>"
                                         style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                                </td>
                                <td><?php echo e($produto['nomeproduto']); ?></td>
                                <td>R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <input type="number" min="1" class="form-control" style="width:80px;"
                                           name="quantidades[<?php echo (int) $produto['idproduto']; ?>]"
                                           value="<?php echo (int) $produto['quantidade']; ?>">
                                </td>
                                <td>R$ <?php echo number_format((float) $produto['subtotal'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="remover.php?idproduto=<?php echo (int) $produto['idproduto']; ?>" class="btn btn-danger btn-sm">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="text-end mb-4">
                    <h4>Total: R$ <?php echo number_format((float) $total, 2, ',', '.'); ?></h4>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-secondary">Atualizar Carrinho</button>
                    <a href="../index.php" class="btn btn-outline-success">Continuar Comprando</a>
                    <a href="finalizar.php" class="btn btn-success">Finalizar Compra</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <footer class="footer bg-success text-white text-center py-3 mt-5">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

</body>
</html>
