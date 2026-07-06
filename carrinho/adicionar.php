<?php
session_start();
require '../includes/functions.php';
require '../config/conexao.php';

// Precisa estar logado para comprar. Se não estiver, manda pro login e
// guarda a URL atual para voltar aqui depois que a pessoa entrar.
if (empty($_SESSION['cliente_id'])) {
    $_SESSION['redirecionar_apos_login'] = $_SERVER['REQUEST_URI'];
    header('Location: ../clientes/login.php');
    exit;
}

// Valida o ID do produto recebido
if (!isset($_GET['idproduto']) || !ctype_digit($_GET['idproduto'])) {
    header('Location: ../index.php');
    exit;
}
$idproduto = (int) $_GET['idproduto'];

// Confirma que o produto realmente existe antes de colocar no carrinho
$stmt = $strcon->prepare('SELECT idproduto FROM Produtos WHERE idproduto = ?');
$stmt->bind_param('i', $idproduto);
$stmt->execute();

if (!$stmt->get_result()->fetch_assoc()) {
    header('Location: ../index.php');
    exit;
}

// O carrinho é um array simples: [idproduto => quantidade]
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$idproduto])) {
    $_SESSION['carrinho'][$idproduto]++;
} else {
    $_SESSION['carrinho'][$idproduto] = 1;
}

header('Location: carrinho.php');
exit;
