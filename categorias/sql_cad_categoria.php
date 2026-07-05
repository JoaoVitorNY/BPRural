<?php
session_start();
require '../includes/auth.php';
require '../includes/functions.php';
exigirLogin('../autenticacao/login.html');

require '../config/conexao.php';

$nome = trim($_POST['txtnome'] ?? '');

if ($nome === '') {
    $_SESSION['erro_categoria'] = 'Informe o nome da categoria.';
    header('Location: cad_categorias.php');
    exit;
}

$stmt = $strcon->prepare("INSERT INTO Categorias (nomecategoria) VALUES (?)");
$stmt->bind_param('s', $nome);

if ($stmt->execute()) {
    echo "<script>
            alert('Categoria cadastrada com sucesso!');
            window.location.href = '../paginas/menu.php';
          </script>";
} else {
    error_log('Erro ao cadastrar categoria: ' . $stmt->error);
    echo "<script>
            alert('Erro ao tentar cadastrar a categoria. Tente novamente.');
            window.location.href = 'cad_categorias.php';
          </script>";
}

$stmt->close();
$strcon->close();
