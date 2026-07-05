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
