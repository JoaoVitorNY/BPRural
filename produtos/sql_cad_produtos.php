<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

$nome        = trim($_POST['txtnome'] ?? '');
$preco       = $_POST['txtpreco'] ?? '';
$quantidade  = $_POST['txtquantidade'] ?? '';
$descricao   = trim($_POST['txtdescricao'] ?? '');
$idcategoria = $_POST['cbocategoria'] ?? '';

// Validação básica de tipos/obrigatoriedade antes de tocar no banco ou no upload
$dadosValidos = $nome !== ''
    && $descricao !== ''
    && is_numeric($preco)
    && ctype_digit((string) $quantidade)
    && ctype_digit((string) $idcategoria);

if (!$dadosValidos) {
    $_SESSION['erro_produto'] = 'Preencha todos os campos corretamente.';
    header('Location: cad_produtos.php');
    exit;
}

try {
    $nomeArquivoFoto = processarUploadImagemProduto($_FILES['txtfoto'], null, true);
} catch (RuntimeException $ex) {
    $_SESSION['erro_produto'] = $ex->getMessage();
    header('Location: cad_produtos.php');
    exit;
}

$sql = "INSERT INTO Produtos (nomeproduto, preco, quantidade, descricao, foto, idcategoria)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $strcon->prepare($sql);
$stmt->bind_param('sdissi', $nome, $preco, $quantidade, $descricao, $nomeArquivoFoto, $idcategoria);

if ($stmt->execute()) {
    echo "<script>
            alert('Produto cadastrado com sucesso!');
            window.location.href = '../paginas/menu.php';
          </script>";
} else {
    // Se a query falhar, remove a imagem já salva para não deixar arquivo órfão
    removerImagemProduto($nomeArquivoFoto);
    error_log('Erro ao cadastrar produto: ' . $stmt->error);
    echo "<script>
            alert('Erro ao tentar cadastrar o produto. Tente novamente.');
            window.location.href = '../produtos/cad_produtos.php';
          </script>";
}

$stmt->close();
$strcon->close();
