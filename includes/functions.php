<?php
/**
 * Funções utilitárias compartilhadas por todo o site.
 */

/** Escapa texto para saída segura em HTML (proteção contra XSS). */
function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Recebe um arquivo enviado via <input type="file"> e, se válido, move para
 * a pasta uploads/produtos com um nome único e seguro.
 *
 * @param array       $arquivo       Um elemento de $_FILES (ex.: $_FILES['foto'])
 * @param string|null $imagemAtual   Nome do arquivo já salvo (usado em edições)
 * @param bool        $obrigatorio   Se true, exige envio de uma nova imagem
 * @return string Nome do arquivo salvo (novo ou o atual, se nenhum novo foi enviado)
 * @throws RuntimeException Em caso de arquivo inválido ou falha ao salvar
 */
function processarUploadImagemProduto(array $arquivo, ?string $imagemAtual, bool $obrigatorio = false): string
{
    $diretorioDestino = __DIR__ . '/../uploads/produtos/';
    $extensoesPermitidas = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $tamanhoMaximoBytes = 5 * 1024 * 1024; // 5 MB

    $nenhumArquivoEnviado = !isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE;

    if ($nenhumArquivoEnviado) {
        if ($obrigatorio) {
            throw new RuntimeException('É necessário selecionar uma imagem para o produto.');
        }
        // Mantém a imagem já cadastrada (fluxo de edição sem trocar a foto)
        return $imagemAtual ?? '';
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Falha no envio da imagem (código ' . $arquivo['error'] . ').');
    }

    if ($arquivo['size'] > $tamanhoMaximoBytes) {
        throw new RuntimeException('A imagem deve ter no máximo 5 MB.');
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!array_key_exists($extensao, $extensoesPermitidas)) {
        throw new RuntimeException('Formato não permitido. Envie uma imagem JPG, PNG ou WEBP.');
    }

    // Nunca confiar apenas na extensão: valida o conteúdo real do arquivo
    $mimeReal = mime_content_type($arquivo['tmp_name']);
    if ($mimeReal !== $extensoesPermitidas[$extensao]) {
        throw new RuntimeException('O conteúdo do arquivo não corresponde a uma imagem válida.');
    }

    if (!is_dir($diretorioDestino) && !mkdir($diretorioDestino, 0755, true)) {
        throw new RuntimeException('Não foi possível preparar o diretório de upload.');
    }

    // Nome aleatório: evita colisões, sobrescrita e path traversal
    $novoNome = bin2hex(random_bytes(16)) . '.' . $extensao;

    if (!move_uploaded_file($arquivo['tmp_name'], $diretorioDestino . $novoNome)) {
        throw new RuntimeException('Falha ao salvar a imagem no servidor.');
    }

    // Ao substituir a foto de um produto já existente, remove o arquivo antigo
    if ($imagemAtual && is_file($diretorioDestino . $imagemAtual)) {
        @unlink($diretorioDestino . $imagemAtual);
    }

    return $novoNome;
}

/** Remove o arquivo de imagem de um produto (usado ao excluir o produto). */
function removerImagemProduto(?string $nomeArquivo): void
{
    if (!$nomeArquivo) {
        return;
    }
    $caminho = __DIR__ . '/../uploads/produtos/' . $nomeArquivo;
    if (is_file($caminho)) {
        @unlink($caminho);
    }
}

/**
 * Resolve a URL de exibição da imagem de um produto, com fallback para um
 * placeholder quando o produto não tem foto ou o arquivo não existe mais.
 *
 * @param string|null $nomeArquivo Nome salvo no banco (coluna `foto`)
 * @param string      $prefixo     Caminho relativo até a raiz do site (ex.: '../' ou '')
 */
function urlImagemProduto(?string $nomeArquivo, string $prefixo = ''): string
{
    if ($nomeArquivo && is_file(__DIR__ . '/../uploads/produtos/' . $nomeArquivo)) {
        return $prefixo . 'uploads/produtos/' . rawurlencode($nomeArquivo);
    }
    return $prefixo . 'images/sem-imagem.svg';
}
