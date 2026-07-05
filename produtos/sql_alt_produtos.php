<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

// Verifica se foi passado o ID do produto para editar
if (!isset($_GET['idproduto']) || !ctype_digit($_GET['idproduto'])) {
    header('Location: alt_produtos.php');
    exit;
}
$id_produto = (int) $_GET['idproduto'];

$sql = "SELECT * FROM Produtos WHERE idproduto = ?";
$stmt = $strcon->prepare($sql);
$stmt->bind_param('i', $id_produto);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();

if (!$produto) {
    header('Location: alt_produtos.php');
    exit;
}

$categorias = mysqli_query($strcon, "SELECT idcategoria, nomecategoria FROM Categorias ORDER BY nomecategoria");

// Verifica se o formulário foi enviado para atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeproduto = trim($_POST['txtnome'] ?? '');
    $preco       = $_POST['txtpreco'] ?? '';
    $quantidade  = $_POST['txtquantidade'] ?? '';
    $descricao   = trim($_POST['txtdescricao'] ?? '');
    $idcategoria = $_POST['cbocategoria'] ?? '';

    $dadosValidos = $nomeproduto !== ''
        && $descricao !== ''
        && is_numeric($preco)
        && ctype_digit((string) $quantidade)
        && ctype_digit((string) $idcategoria);

    if (!$dadosValidos) {
        $erro = 'Preencha todos os campos corretamente.';
    } else {
        try {
            // Só troca o arquivo se um novo foi realmente enviado; senão mantém o atual
            $foto = processarUploadImagemProduto($_FILES['txtfoto'], $produto['foto'], false);

            $sql_update = "UPDATE Produtos SET nomeproduto = ?, preco = ?, quantidade = ?, descricao = ?, foto = ?, idcategoria = ? WHERE idproduto = ?";
            $stmt_update = $strcon->prepare($sql_update);
            $stmt_update->bind_param('sdissii', $nomeproduto, $preco, $quantidade, $descricao, $foto, $idcategoria, $id_produto);

            if ($stmt_update->execute()) {
                echo "<script>alert('Produto atualizado com sucesso!'); window.location.href='alt_produtos.php';</script>";
                exit;
            }
            $erro = 'Erro ao atualizar o produto. Tente novamente.';
        } catch (RuntimeException $ex) {
            $erro = $ex->getMessage();
        }
    }

    // Recarrega os dados atuais do produto para reexibir o formulário com o erro
    $produto = array_merge($produto, compact('nomeproduto', 'preco', 'quantidade', 'descricao', 'idcategoria'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Produto - BP Rural</title>
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
        <h2 class="text-center">Alterar Produto</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger" style="max-width:600px;margin:0 auto 1rem;"><?php echo e($erro); ?></div>
        <?php endif; ?>

        <form action="sql_alt_produtos.php?idproduto=<?php echo $id_produto; ?>" method="POST" enctype="multipart/form-data" style="max-width:600px;margin:0 auto;">
            <div class="mb-3 text-center">
                <img src="<?php echo e(urlImagemProduto($produto['foto'], '../')); ?>" alt="Foto atual" style="max-width:200px;border-radius:8px;">
            </div>
            <div class="mb-3">
                <label for="txtnome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="txtnome" name="txtnome" value="<?php echo e($produto['nomeproduto']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="txtpreco" class="form-label">Preço</label>
                <input type="number" step="0.01" min="0" class="form-control" id="txtpreco" name="txtpreco" value="<?php echo e((string) $produto['preco']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="txtquantidade" class="form-label">Quantidade</label>
                <input type="number" min="0" class="form-control" id="txtquantidade" name="txtquantidade" value="<?php echo e((string) $produto['quantidade']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="txtdescricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="txtdescricao" name="txtdescricao" rows="4" required><?php echo e($produto['descricao']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="txtfoto" class="form-label">Substituir Foto (opcional)</label>
                <input type="file" class="form-control" id="txtfoto" name="txtfoto" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Deixe em branco para manter a imagem atual.</div>
            </div>
            <div class="mb-3">
                <label for="cbocategoria" class="form-label">Categoria</label>
                <select class="form-select" id="cbocategoria" name="cbocategoria" required>
                    <?php while ($categoria = mysqli_fetch_assoc($categorias)): ?>
                        <option value="<?php echo (int) $categoria['idcategoria']; ?>"
                            <?php echo ((int) $produto['idcategoria'] === (int) $categoria['idcategoria']) ? 'selected' : ''; ?>>
                            <?php echo e($categoria['nomecategoria']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Atualizar Produto</button>
            </div>
        </form>

        <!-- Botão Voltar -->
        <div class="text-center mt-3">
            <a href="../paginas/menu.php" class="btn btn-secondary">Voltar à Lista de Produtos</a>
        </div>
    </div>

    <footer class="footer bg-success text-white text-center py-3">
        <p>&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
