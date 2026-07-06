<?php
/**
 * Exige que exista uma sessão de usuário autenticada.
 * Caso contrário, redireciona para a tela de login e encerra o script.
 *
 * @param string $urlLogin Caminho relativo (a partir do arquivo que chama) até login.html
 */
function exigirLogin(string $urlLogin): void
{
    if (empty($_SESSION['username'])) {
        header('Location: ' . $urlLogin);
        exit;
    }
}

/**
 * Exige que exista um cliente logado (área de compras do site).
 * Usa uma sessão separada da do painel administrativo (username),
 * então um cliente logado não vira administrador e vice-versa.
 *
 * @param string $urlLogin Caminho relativo até clientes/login.php
 */
function exigirLoginCliente(string $urlLogin): void
{
    if (empty($_SESSION['cliente_id'])) {
        header('Location: ' . $urlLogin);
        exit;
    }
}
