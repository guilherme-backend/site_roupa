# Guia de Instalação Rápida

## Requisitos do Sistema

O sistema foi desenvolvido e testado com as seguintes versões:

- **PHP**: 8.1.2 ou superior
- **MySQL**: 8.0 ou superior
- **Composer**: 2.9.3 ou superior
- **Node.js**: 22.x ou superior
- **NPM/PNPM**: Última versão

## Instalação Passo a Passo

### 1. Preparar o Ambiente

Certifique-se de que o MySQL está rodando:

```bash
sudo service mysql start
```

### 2. Configurar Banco de Dados

O banco de dados já foi criado durante o desenvolvimento:

- **Nome**: ecommerce_roupas
- **Usuário**: ecommerce
- **Senha**: ecommerce123

Se precisar recriar:

```bash
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ecommerce_roupas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'ecommerce'@'localhost' IDENTIFIED BY 'ecommerce123';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ecommerce_roupas.* TO 'ecommerce'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

### 3. Configurar Variáveis de Ambiente

O arquivo `.env` já está configurado. Para produção, atualize:

```bash
nano .env
```

Altere as seguintes variáveis:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

# Adicione suas credenciais do Mercado Pago
MERCADOPAGO_PUBLIC_KEY=seu_public_key_aqui
MERCADOPAGO_ACCESS_TOKEN=seu_access_token_aqui

# Configure o CEP de origem para cálculo de frete
SHIPPING_ORIGIN_ZIPCODE=01310100
```

### 4. Executar Migrations

As migrations já foram executadas. Se precisar reexecutar:

```bash
php artisan migrate:fresh
```

**⚠️ ATENÇÃO**: O comando acima apaga todos os dados!

### 5. Criar Usuário Administrador

Um usuário admin já foi criado:

- **Email**: admin@ecommerce.com
- **Senha**: admin123

Para criar um novo administrador:

```bash
php artisan make:filament-user
```

### 6. Compilar Assets

Para desenvolvimento:

```bash
npm run dev
```

Para produção:

```bash
npm run build
```

### 7. Iniciar o Servidor

Para desenvolvimento:

```bash
php artisan serve
```

O sistema estará disponível em: http://localhost:8000

Para produção, configure o servidor web (Apache/Nginx) apontando para a pasta `public`.

## Acessos

### Painel Administrativo

- **URL**: http://localhost:8000/admin
- **Email**: admin@ecommerce.com
- **Senha**: admin123

### Loja Virtual

- **URL**: http://localhost:8000

## Configuração do Mercado Pago

### Obter Credenciais

1. Acesse: https://www.mercadopago.com.br/developers
2. Crie uma aplicação
3. Copie o **Public Key** e o **Access Token**
4. Cole no arquivo `.env`

### Configurar Webhook

1. No painel do Mercado Pago, vá em "Webhooks"
2. Adicione a URL: `https://seudominio.com.br/webhook/mercadopago`
3. Selecione o evento: "Pagamentos"

**⚠️ IMPORTANTE**: O webhook só funciona com HTTPS em produção!

## Testar o Sistema

### 1. Criar Categorias

Acesse o painel admin e crie algumas categorias:
- Camisetas
- Calças
- Vestidos
- Acessórios

### 2. Criar Produtos

Para cada produto:
1. Preencha nome, descrição e preço base
2. Selecione a categoria
3. Faça upload de imagens
4. Crie variações (tamanho e cor)
5. Defina o estoque para cada variação

### 3. Testar Compra

1. Acesse a loja
2. Adicione produtos ao carrinho
3. Faça o checkout
4. Teste o cálculo de frete
5. Finalize o pedido

### 4. Testar Pagamento (Sandbox)

Para testar sem cobranças reais:

1. Use as credenciais de **teste** do Mercado Pago
2. Use cartões de teste: https://www.mercadopago.com.br/developers/pt/docs/checkout-api/testing

## Solução de Problemas Comuns

### Erro: "Storage link not found"

```bash
php artisan storage:link
```

### Erro: "Permission denied" em storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Erro: "Class not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Erro ao enviar imagens

Verifique as permissões:

```bash
chmod -R 775 storage/app/public
```

### Erro no webhook do Mercado Pago

1. Verifique se a URL está acessível publicamente
2. Confirme que está usando HTTPS
3. Verifique os logs: `tail -f storage/logs/laravel.log`

## Comandos Úteis

### Limpar cache

```bash
php artisan optimize:clear
```

### Ver logs em tempo real

```bash
tail -f storage/logs/laravel.log
```

### Recriar banco de dados

```bash
php artisan migrate:fresh --seed
```

### Atualizar dependências

```bash
composer update
npm update
```

## Próximos Passos

Após a instalação:

1. ✅ Configure as credenciais do Mercado Pago
2. ✅ Cadastre categorias e produtos
3. ✅ Teste o fluxo completo de compra
4. ✅ Configure o webhook em produção
5. ✅ Personalize o design conforme necessário
6. ✅ Configure email para notificações
7. ✅ Faça backup regular do banco de dados

## Suporte

Para dúvidas técnicas, consulte:

- **README_PROJETO.md**: Documentação completa
- **Laravel Docs**: https://laravel.com/docs
- **Filament Docs**: https://filamentphp.com/docs
- **Mercado Pago Docs**: https://www.mercadopago.com.br/developers

---

**Sistema pronto para uso em produção!** 🚀
