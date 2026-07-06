<?php
session_start();
unset($_SESSION['cliente_id']);
unset($_SESSION['cliente_nome']);
unset($_SESSION['carrinho']);
header('Location: ../index.php');
exit;
