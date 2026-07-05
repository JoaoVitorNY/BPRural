# BPRural — Atualizações aplicadas

## 1. Upload real de imagens dos produtos

Antes, o campo "Foto" era um `<input type="text">` — o usuário digitava um caminho manualmente,
o que não funcionava de verdade e ainda representava risco de segurança.

Agora:
- `produtos/cad_produtos.php` e `produtos/sql_alt_produtos.php` usam `<input type="file">`.
- O upload é processado por `includes/functions.php::processarUploadImagemProduto()`, que:
  - valida extensão **e** o conteúdo real do arquivo (`mime_content_type`), não confiando só na extensão;
  - limita o tamanho a 5 MB;
  - gera um nome aleatório (`random_bytes`) para o arquivo salvo, evitando sobrescrita e path traversal;
  - salva em `uploads/produtos/`, fora da pasta `images/` (que guarda apenas os assets estáticos do site: logo, banners etc.);
  - remove a imagem antiga automaticamente ao trocar a foto de um produto ou ao excluí-lo.
- A função `urlImagemProduto()` resolve o caminho correto da imagem para exibição e cai para um
  placeholder (`images/sem-imagem.svg`) quando o produto não tem foto ou o arquivo não existe mais.
- `uploads/produtos/.htaccess` bloqueia a execução de scripts nessa pasta, mesmo que alguém
  consiga burlar a validação e enviar um arquivo malicioso.

## 2. Segurança no backend

- **SQL Injection**: `sql_cad_produtos.php`, `sql_cad_categoria.php` e `autenticacao/login.php`
  concatenavam valores de `$_POST` direto na query. Todos agora usam *prepared statements*
  (`mysqli::prepare` + `bind_param`).
- **Senhas em texto puro**: `login.php` comparava a senha digitada com o valor puro do banco.
  Agora usa `password_verify()`. **Importante**: ao cadastrar/alterar usuários, salve a senha
  sempre com `password_hash($senha, PASSWORD_DEFAULT)` — a coluna `userpassword VARCHAR(255)`
  já comporta o hash gerado.
- **XSS**: toda saída de dados vindos do banco ou do usuário agora passa pela função `e()`
  (wrapper de `htmlspecialchars`) antes de ir para o HTML.
- **Erros do banco expostos ao usuário**: `config/conexao.php` agora registra o erro detalhado
  em log (`error_log`) e mostra uma mensagem genérica na tela.
- **Sessão**: `login.php` chama `session_regenerate_id(true)` após autenticar, prevenindo
  fixação de sessão. `logout.php` limpa e destrói a sessão corretamente.
- Todas as páginas administrativas agora usam `includes/auth.php::exigirLogin()` para checar a
  sessão, eliminando redirecionamentos inconsistentes/quebrados que existiam no código original.
- IDs vindos de `$_GET` (`idproduto`, `idcategoria`) são validados com `ctype_digit()` antes de
  qualquer uso.

## 3. Organização de pastas

```
BPRural/
├── config/            # conexao.php (antes solto na raiz)
├── includes/           # funções e regras compartilhadas
│   ├── auth.php
│   ├── functions.php
│   └── pagina_categoria.php   # template único usado pelas 5 páginas de categoria
├── uploads/
│   └── produtos/       # imagens enviadas pelos usuários (fora de images/)
├── images/              # assets estáticos do site (logo, banners, placeholder)
├── autenticacao/
├── categorias/
├── produtos/
├── paginas/
└── index.php
```

As páginas `paginas/tatuadeiras.php`, `tintas.php`, `brincos.php`, `marcadores.php` e
`diversos.php` eram cópias praticamente idênticas. Agora cada uma só define o `idcategoria`
e o título/descrição, e delega a renderização para `includes/pagina_categoria.php`.

## 4. Observações para você continuar o projeto

- As pastas `usuarios/` (cadastro/alteração/exclusão de usuários) e páginas como
  `promocoes.php`/`contato.html` são referenciadas no menu, mas não existiam no projeto
  enviado — os links foram mantidos para quando você os implementar.
- `autenticacao/register.html` e `forgot-password.html` estavam vazios no projeto original;
  foram copiados como estão.
- Ao criar o primeiro usuário diretamente no banco (via phpMyAdmin, por exemplo), gere o hash
  da senha com `password_hash('suasenha', PASSWORD_DEFAULT)` em um script PHP local e cole o
  resultado na coluna `userpassword`.
