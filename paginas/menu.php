<?php
session_start();
require '../includes/auth.php';
exigirLogin('../autenticacao/login.html');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-custom {
            width: 100%;
            text-align: left;
            padding: 15px;
            border-radius: 5px;
            background-color: white;
            color: #495057;
            border: 2px solid #6c757d;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }
        .btn-cadastro:hover {
            background-color: #198754;
        }
        .btn-exclusao:hover {
            background-color: #dc3545;
        }
        .btn-relatorio:hover {
            background-color: #007bff;
        }
        .btn-alteracao:hover {
            background-color: #ffc107;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Painel de Administração - BP Rural</h2>

    <div class="row">
        <!-- Coluna 1: Cadastro e Alteração -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-success text-white">
                    <b>Cadastro</b>
                </div>
                <div class="card-body">
                    <a href="../usuarios/cad_usuarios.php" class="btn btn-custom btn-cadastro mb-2">Cadastrar Usuários</a><br>
                    <a href="../categorias/cad_categorias.php" class="btn btn-custom btn-cadastro mb-2">Cadastrar Categoria</a><br>
                    <a href="../produtos/cad_produtos.php" class="btn btn-custom btn-cadastro">Cadastrar Produtos</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-center bg-warning text-dark">
                    <b>Alteração</b>
                </div>
                <div class="card-body">
                    <a href="../usuarios/alt_usuarios.php" class="btn btn-custom btn-alteracao mb-2">Alterar Usuários</a><br>
                    <a href="../categorias/alt_categorias.php" class="btn btn-custom btn-alteracao mb-2">Alterar Categoria</a><br>
                    <a href="../produtos/alt_produtos.php" class="btn btn-custom btn-alteracao">Alterar Produtos</a>
                </div>
            </div>
        </div>

        <!-- Coluna 2: Relatório e Exclusão -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <b>Relatórios</b>
                </div>
                <div class="card-body">
                    <a href="../usuarios/rel_usuarios.php" class="btn btn-custom btn-relatorio mb-2">Relatório de Usuários</a><br>
                    <a href="../categorias/rel_categorias.php" class="btn btn-custom btn-relatorio mb-2">Relatório de Categoria</a><br>
                    <a href="../produtos/rel_produtos.php" class="btn btn-custom btn-relatorio">Relatório de Produtos</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-center bg-danger text-white">
                    <b>Exclusão</b>
                </div>
                <div class="card-body">
                    <a href="../usuarios/del_usuarios.php" class="btn btn-custom btn-exclusao mb-2">Excluir Usuários</a><br>
                    <a href="../categorias/del_categorias.php" class="btn btn-custom btn-exclusao mb-2">Excluir Categoria</a><br>
                    <a href="../produtos/del_produtos.php" class="btn btn-custom btn-exclusao">Excluir Produtos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../autenticacao/logout.php" class="btn btn-danger">Sair</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
