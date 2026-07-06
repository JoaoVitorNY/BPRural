<?php
session_start();
require 'includes/functions.php';
require 'config/conexao.php';

// Exibe os 3 produtos com o menor preço como destaque
$result = $strcon->query('SELECT idproduto, nomeproduto, preco, descricao, foto FROM Produtos ORDER BY preco ASC LIMIT 3');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre a BP Rural</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/site.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            background-color: #198754;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .product img {
            max-width: 100%;
            border-radius: 10px;
        }
        .footer {
            background-color: #198754;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <img src="images/New_Logo_BPRURAL.37.jpeg" alt="Logo" height="40" class="d-inline-block align-text-top me-2">
                BP Rural
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 flex-wrap">
                    <li class="nav-item"><a class="nav-link" href="paginas/tatuadeiras.php">Tatuadeiras</a></li>
                    <li class="nav-item"><a class="nav-link" href="paginas/tintas.php">Tintas e Pastas</a></li>
                    <li class="nav-item"><a class="nav-link" href="paginas/brincos.php">Brincos e Aplicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="paginas/marcadores.php">Marcadores e Fogareiros</a></li>
                    <li class="nav-item"><a class="nav-link" href="paginas/diversos.php">Diversos</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#destaques">Promoções</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Página Inicial</a></li>
                </ul>
                <?php if (!empty($_SESSION['cliente_id'])): ?>
                    <?php $primeiroNome = explode(' ', trim($_SESSION['cliente_nome']))[0]; ?>
                    <div class="dropdown ms-lg-2">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Olá, <?php echo e($primeiroNome); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="carrinho/carrinho.php">Meu Carrinho</a></li>
                            <li><a class="dropdown-item" href="clientes/logout.php">Sair</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="clientes/login.php" class="btn btn-outline-light me-2">Entrar</a>
                    <a href="clientes/cadastro.php" class="btn btn-light">Criar Conta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Seção Sobre -->
    <section id="sobre" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start fs-4">
                    <h1>Sobre a BP Rural</h1>
                    <p>Somos especializados em produtos agropecuários, oferecendo soluções de alta qualidade para o campo e seus animais.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="images/agropecuaria.jpg" alt="BP Rural" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Produtos em Promoção -->
    <div id="destaques" class="container py-5">
        <h2 class="text-center mb-4">Produtos em Promoção</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($produto = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo e(urlImagemProduto($produto['foto'])); ?>"
                                 alt="<?php echo e($produto['nomeproduto']); ?>"
                                 class="card-img-top" style="height: 400px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo e($produto['nomeproduto']); ?></h5>
                                <p class="card-text"><?php echo nl2br(e($produto['descricao'])); ?></p>
                                <p class="card-text"><strong>R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?></strong></p>
                                <a href="carrinho/adicionar.php?idproduto=<?php echo (int) $produto['idproduto']; ?>" class="btn btn-success mt-auto">Comprar</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer bg-success text-white text-center py-4">
        <div class="container">
            <hr class="my-2" style="border-color: #198754">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contato</h5>
                    <p>📞 (43) 3348-1122 | 📱 (43) 99944-0016</p>
                </div>
                <div class="col-md-4">
                    <h5>Localização</h5>
                    <p>📍 Londrina - Paraná</p>
                </div>
                <div class="col-md-4">
                    <h5>Email</h5>
                    <p>📧 <a href="mailto:bpruralparana@gmail.com" class="text-white text-decoration-none">bpruralparana@gmail.com</a></p>
                </div>
            </div>
            <p class="mb-1">&copy; 2025 BP Rural Produtos Agropecuários. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>