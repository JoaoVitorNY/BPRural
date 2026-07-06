<?php
session_start();
require '../includes/auth.php';
exigirLoginCliente('../clientes/login.php');

// Aqui, futuramente, entraria a lógica de salvar o pedido no banco
// (uma tabela Pedidos e ItensPedido, por exemplo). Por enquanto,
// só limpamos o carrinho e confirmamos para o cliente.
unset($_SESSION['carrinho']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/site.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5 text-center">
        <h2 class="text-success">Pedido realizado com sucesso!</h2>
        <p>Obrigado pela compra. Em breve entraremos em contato para combinar o pagamento e a entrega.</p>
        <a href="../index.php" class="btn btn-success mt-3">Voltar para a Página Inicial</a>
    </div>
</body>
</html>
