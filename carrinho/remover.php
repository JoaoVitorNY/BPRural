<?php
session_start();

if (isset($_GET['idproduto']) && ctype_digit($_GET['idproduto'])) {
    $idproduto = (int) $_GET['idproduto'];
    unset($_SESSION['carrinho'][$idproduto]);
}

header('Location: carrinho.php');
exit;
