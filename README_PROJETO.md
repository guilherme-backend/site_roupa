# E-commerce de Roupas - Laravel

## 📋 Sobre o Projeto

E-commerce completo desenvolvido em Laravel para venda de roupas, com sistema de variações de produtos (tamanho e cor), controle de estoque, integração com Mercado Pago para pagamentos e cálculo de frete com Correios.

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Banco de Dados**: MySQL 8.0
- **Painel Admin**: Filament 3.0
- **Autenticação**: Laravel Breeze
- **Pagamentos**: Mercado Pago SDK
- **Frete**: Integração com API dos Correios

## ✨ Funcionalidades Implementadas

### Loja Virtual (Frontend)

- ✅ Página inicial com produtos em destaque e novidades
- ✅ Catálogo de produtos com filtros por categoria
- ✅ Busca de produtos por nome
- ✅ Página de detalhes do produto com:
  - Múltiplas imagens
  - Seleção de tamanho e cor
  - Verificação de estoque em tempo real
- ✅ Carrinho de compras com sessão
- ✅ Checkout em página única
- ✅ Cálculo automático de frete por CEP
- ✅ Integração com ViaCEP para preenchimento automático de endereço
- ✅ Múltiplos métodos de pagamento (PIX, Cartão, Boleto)

### Área do Cliente

- ✅ Dashboard com estatísticas de pedidos
- ✅ Histórico completo de pedidos
- ✅ Visualização detalhada de cada pedido
- ✅ Rastreamento de status do pedido
- ✅ Cancelamento de pedidos (quando permitido)

### Painel Administrativo (Filament)

- ✅ Gerenciamento de categorias
- ✅ Gerenciamento de produtos com:
  - Upload de múltiplas imagens
  - Definição de imagem principal
  - Criação de variações (tamanho e cor)
  - Controle de estoque por variação
  - Ajuste de preço por variação
  - SKU único para cada variação
- ✅ Gerenciamento de pedidos com:
  - Visualização completa dos dados
  - Alteração de status
  - Adição de código de rastreamento
  - Visualização de itens do pedido
- ✅ Filtros avançados em todas as listagens
- ✅ Interface responsiva e intuitiva

### Sistema de Pagamentos

- ✅ Integração completa com Mercado Pago
- ✅ Checkout transparente
- ✅ Suporte a PIX, Cartão de Crédito e Boleto
- ✅ Webhook para confirmação automática de pagamento
- ✅ Atualização automática de status do pedido
- ✅ Devolução automática de estoque em caso de cancelamento

### Sistema de Frete

- ✅ Cálculo automático baseado em CEP
- ✅ Simulação de PAC e SEDEX
- ✅ Cálculo por região e peso
- ✅ Exibição de prazo de entrega

### Controle de Estoque

- ✅ Estoque por variação de produto
- ✅ Decremento automático ao finalizar pedido
- ✅ Incremento automático ao cancelar pedido
- ✅ Validação de disponibilidade em tempo real
- ✅ Indicadores visuais de estoque no painel admin

## 📦 Estrutura do Banco de Dados

### Tabelas Principais

- **users**: Usuários do sistema (clientes e admin)
- **categories**: Categorias de produtos
- **products**: Produtos base
- **product_variants**: Variações de produtos (tamanho/cor/estoque)
- **product_images**: Imagens dos produtos
- **orders**: Pedidos realizados
- **order_items**: Itens de cada pedido

## 🔧 Configuração e Instalação

### Pré-requisitos

- PHP 8.1 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js e NPM

### Passo a Passo

1. **Clone o repositório**
```bash
cd /home/ubuntu/ecommerce-roupas
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o arquivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure as variáveis de ambiente no .env**
```env
# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_roupas
DB_USERNAME=ecommerce
DB_PASSWORD=ecommerce123

# Mercado Pago
MERCADOPAGO_PUBLIC_KEY=seu_public_key
MERCADOPAGO_ACCESS_TOKEN=seu_access_token

# Frete
SHIPPING_ORIGIN_ZIPCODE=01310100
SHIPPING_DEFAULT_WEIGHT=0.5

# App
APP_URL=http://localhost:8000
```

5. **Execute as migrations**
```bash
php artisan migrate
```

6. **Crie o link simbólico para storage**
```bash
php artisan storage:link
```

7. **Compile os assets**
```bash
npm run build
```

8. **Crie um usuário administrador**
```bash
php artisan make:filament-user
```

9. **Inicie o servidor**
```bash
php artisan serve
```

## 🌐 Acessos

- **Loja**: http://localhost:8000
- **Painel Admin**: http://localhost:8000/admin
- **Credenciais Admin**: 
  - Email: admin@ecommerce.com
  - Senha: admin123

## 📱 Rotas Principais

### Frontend
- `/` - Página inicial
- `/loja` - Catálogo de produtos
- `/categoria/{slug}` - Produtos por categoria
- `/produto/{slug}` - Detalhes do produto
- `/carrinho` - Carrinho de compras
- `/checkout` - Finalizar compra

### Área do Cliente (autenticado)
- `/minha-conta` - Dashboard
- `/meus-pedidos` - Lista de pedidos
- `/meus-pedidos/{id}` - Detalhes do pedido

### API/Webhooks
- `POST /webhook/mercadopago` - Webhook do Mercado Pago

## 🔐 Segurança

- ✅ Proteção CSRF em todos os formulários
- ✅ Validação server-side em todas as requisições
- ✅ Autenticação com Laravel Breeze
- ✅ Middleware de autorização para rotas protegidas
- ✅ Sanitização de inputs
- ✅ Proteção contra SQL Injection (Eloquent ORM)

## 📊 Services Implementados

### CartService
Gerencia o carrinho de compras em sessão:
- Adicionar produtos
- Atualizar quantidades
- Remover produtos
- Calcular totais
- Validar estoque

### ShippingService
Gerencia o cálculo de frete:
- Integração com API dos Correios (simulação)
- Validação de CEP com ViaCEP
- Cálculo por região e peso
- Retorno de opções PAC e SEDEX

### PaymentService
Gerencia pagamentos com Mercado Pago:
- Criação de preferências de pagamento
- Processamento de webhooks
- Consulta de status de pagamento
- Suporte a múltiplos métodos

## 🎨 Design e UX

- Design moderno e limpo
- Totalmente responsivo (mobile-first)
- Componentes reutilizáveis com Blade
- Tailwind CSS para estilização
- Alpine.js para interatividade
- Feedback visual para todas as ações

## 🚀 Deploy em Produção

### Checklist

1. **Configurar servidor**
   - PHP 8.1+
   - MySQL 8.0+
   - Composer
   - Node.js

2. **Configurar .env de produção**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

# Credenciais reais do Mercado Pago
MERCADOPAGO_PUBLIC_KEY=seu_public_key_producao
MERCADOPAGO_ACCESS_TOKEN=seu_access_token_producao
```

3. **Otimizar aplicação**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

4. **Configurar permissões**
```bash
chmod -R 755 storage bootstrap/cache
```

5. **Configurar HTTPS**
   - Certificado SSL obrigatório para pagamentos

6. **Configurar Webhook do Mercado Pago**
   - URL: https://seudominio.com.br/webhook/mercadopago

## 📝 Próximas Melhorias Sugeridas

- [ ] Sistema de cupons de desconto
- [ ] Avaliações e comentários de produtos
- [ ] Wishlist (lista de desejos)
- [ ] Recuperação de carrinho abandonado
- [ ] Newsletter
- [ ] Relatórios e dashboards no admin
- [ ] Notificações por email
- [ ] Integração com Melhor Envio para frete real
- [ ] Sistema de pontos/fidelidade
- [ ] Chat de atendimento

## 🐛 Troubleshooting

### Erro ao criar pedido
- Verifique se o estoque está disponível
- Confirme as credenciais do Mercado Pago
- Verifique os logs em `storage/logs/laravel.log`

### Erro no cálculo de frete
- Valide o CEP de origem no .env
- Verifique a conexão com a API ViaCEP

### Erro no webhook
- Confirme que a URL está acessível publicamente
- Verifique se o webhook está configurado no Mercado Pago
- Analise os logs para detalhes do erro

## 📞 Suporte

Para dúvidas ou problemas, consulte:
- Documentação do Laravel: https://laravel.com/docs
- Documentação do Filament: https://filamentphp.com/docs
- Documentação do Mercado Pago: https://www.mercadopago.com.br/developers

## 📄 Licença

Este projeto foi desenvolvido como um MVP funcional e vendável.

---

**Desenvolvido com Laravel + Filament + Mercado Pago**
