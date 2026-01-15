# Estrutura do Projeto E-commerce

## Visão Geral da Arquitetura

O projeto segue a arquitetura **MVC (Model-View-Controller)** do Laravel, com camadas adicionais de **Services** para lógica de negócio complexa.

## Estrutura de Diretórios

```
ecommerce-roupas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CartController.php              # Gerenciamento do carrinho
│   │   │   ├── CheckoutController.php          # Processo de checkout e pagamento
│   │   │   ├── HomeController.php              # Página inicial
│   │   │   ├── ProductController.php           # Detalhes de produtos
│   │   │   ├── ShopController.php              # Catálogo e busca
│   │   │   └── Customer/
│   │   │       ├── DashboardController.php     # Dashboard do cliente
│   │   │       └── OrderController.php         # Pedidos do cliente
│   │   └── Middleware/
│   │
│   ├── Models/
│   │   ├── Category.php                        # Categorias de produtos
│   │   ├── Product.php                         # Produtos
│   │   ├── ProductVariant.php                  # Variações (tamanho/cor)
│   │   ├── ProductImage.php                    # Imagens dos produtos
│   │   ├── Order.php                           # Pedidos
│   │   ├── OrderItem.php                       # Itens dos pedidos
│   │   └── User.php                            # Usuários
│   │
│   ├── Services/
│   │   ├── CartService.php                     # Lógica do carrinho
│   │   ├── PaymentService.php                  # Integração Mercado Pago
│   │   └── ShippingService.php                 # Cálculo de frete
│   │
│   └── Filament/
│       └── Resources/
│           ├── CategoryResource.php            # Admin: Categorias
│           ├── ProductResource.php             # Admin: Produtos
│           │   └── RelationManagers/
│           │       ├── VariantsRelationManager.php   # Admin: Variações
│           │       └── ImagesRelationManager.php     # Admin: Imagens
│           └── OrderResource.php               # Admin: Pedidos
│               └── RelationManagers/
│                   └── ItemsRelationManager.php      # Admin: Itens do pedido
│
├── database/
│   └── migrations/
│       ├── 2026_01_14_230713_create_categories_table.php
│       ├── 2026_01_14_230726_create_products_table.php
│       ├── 2026_01_14_230727_create_product_variants_table.php
│       ├── 2026_01_14_230727_create_product_images_table.php
│       ├── 2026_01_14_230753_create_orders_table.php
│       └── 2026_01_14_230754_create_order_items_table.php
│
├── routes/
│   ├── web.php                                 # Rotas públicas e autenticadas
│   └── auth.php                                # Rotas de autenticação (Breeze)
│
├── config/
│   ├── services.php                            # Configuração Mercado Pago
│   └── shipping.php                            # Configuração de frete
│
├── resources/
│   └── views/
│       ├── home.blade.php                      # Página inicial (a criar)
│       ├── shop/
│       │   ├── index.blade.php                 # Catálogo (a criar)
│       │   └── category.blade.php              # Categoria (a criar)
│       ├── products/
│       │   └── show.blade.php                  # Detalhes produto (a criar)
│       ├── cart/
│       │   └── index.blade.php                 # Carrinho (a criar)
│       ├── checkout/
│       │   ├── index.blade.php                 # Checkout (a criar)
│       │   └── success.blade.php               # Sucesso (a criar)
│       └── customer/
│           ├── dashboard.blade.php             # Dashboard cliente (a criar)
│           └── orders/
│               ├── index.blade.php             # Lista pedidos (a criar)
│               └── show.blade.php              # Detalhes pedido (a criar)
│
└── public/
    └── storage/                                # Link simbólico para storage/app/public
        └── products/                           # Imagens de produtos
```

## Camadas da Aplicação

### 1. Models (Eloquent ORM)

Os modelos representam as tabelas do banco de dados e contêm a lógica de relacionamentos.

**Principais Relacionamentos:**

- `Category` → hasMany → `Product`
- `Product` → belongsTo → `Category`
- `Product` → hasMany → `ProductVariant`
- `Product` → hasMany → `ProductImage`
- `ProductVariant` → belongsTo → `Product`
- `Order` → belongsTo → `User`
- `Order` → hasMany → `OrderItem`
- `OrderItem` → belongsTo → `Product`
- `OrderItem` → belongsTo → `ProductVariant`

### 2. Controllers

Controladores gerenciam as requisições HTTP e coordenam a comunicação entre Models, Services e Views.

**Organização:**

- **Frontend Controllers**: HomeController, ShopController, ProductController
- **Cart & Checkout**: CartController, CheckoutController
- **Customer Area**: Customer/DashboardController, Customer/OrderController

### 3. Services

Camada de serviços contém lógica de negócio complexa e integrações externas.

**CartService:**
- Gerencia carrinho em sessão
- Validação de estoque
- Cálculos de totais

**PaymentService:**
- Integração com Mercado Pago SDK
- Criação de preferências de pagamento
- Processamento de webhooks

**ShippingService:**
- Cálculo de frete por CEP
- Integração com ViaCEP
- Simulação de PAC/SEDEX

### 4. Filament Resources

Recursos do Filament para o painel administrativo com CRUD completo.

**Recursos Implementados:**
- CategoryResource: Gerenciamento de categorias
- ProductResource: Gerenciamento de produtos com relation managers
- OrderResource: Visualização e gerenciamento de pedidos

### 5. Routes

**Rotas Públicas:**
- `/` - Home
- `/loja` - Catálogo
- `/categoria/{slug}` - Categoria
- `/produto/{slug}` - Produto
- `/carrinho` - Carrinho

**Rotas Autenticadas:**
- `/checkout` - Checkout
- `/minha-conta` - Dashboard
- `/meus-pedidos` - Pedidos

**Rotas Admin:**
- `/admin` - Painel Filament

**Webhooks:**
- `POST /webhook/mercadopago` - Notificações de pagamento

## Fluxo de Dados

### Fluxo de Compra

```
1. Cliente navega na loja
   ↓
2. Adiciona produto ao carrinho (CartService)
   ↓
3. Vai para checkout (autenticação obrigatória)
   ↓
4. Preenche dados de entrega
   ↓
5. Calcula frete (ShippingService → ViaCEP)
   ↓
6. Escolhe método de pagamento
   ↓
7. Cria pedido (CheckoutController)
   ↓
8. Decrementa estoque (ProductVariant)
   ↓
9. Cria preferência no Mercado Pago (PaymentService)
   ↓
10. Redireciona para pagamento
    ↓
11. Mercado Pago processa pagamento
    ↓
12. Webhook atualiza status (CheckoutController)
    ↓
13. Cliente visualiza pedido confirmado
```

### Fluxo de Gerenciamento (Admin)

```
1. Admin acessa /admin
   ↓
2. Cria categoria
   ↓
3. Cria produto base
   ↓
4. Adiciona imagens ao produto
   ↓
5. Cria variações (tamanho/cor/estoque)
   ↓
6. Produto fica disponível na loja
   ↓
7. Gerencia pedidos recebidos
   ↓
8. Atualiza status e código de rastreamento
```

## Banco de Dados

### Schema Principal

**categories**
- id, name, slug, description, is_active, order

**products**
- id, category_id, name, slug, description, base_price, is_active, is_featured

**product_variants**
- id, product_id, size, color, color_hex, sku, price_adjustment, stock_quantity, is_available

**product_images**
- id, product_id, image_path, is_primary, order

**orders**
- id, user_id, order_number, status, shipping_*, subtotal, shipping_cost, total, payment_*

**order_items**
- id, order_id, product_id, product_variant_id, product_name, variant_size, variant_color, price, quantity, subtotal

## Integrações Externas

### Mercado Pago

**SDK**: mercadopago/dx-php v2.6

**Funcionalidades:**
- Criação de preferências de pagamento
- Checkout transparente
- Webhooks para confirmação
- Suporte a PIX, Cartão e Boleto

**Configuração:**
- Public Key: `MERCADOPAGO_PUBLIC_KEY`
- Access Token: `MERCADOPAGO_ACCESS_TOKEN`

### ViaCEP

**API**: https://viacep.com.br/

**Funcionalidades:**
- Validação de CEP
- Preenchimento automático de endereço

### Correios (Simulação)

**Implementação:** Simulação baseada em região e peso

**Métodos:**
- PAC: Econômico (7-12 dias)
- SEDEX: Expresso (2-5 dias)

## Segurança

### Implementações

1. **CSRF Protection**: Tokens em todos os formulários
2. **Validação Server-Side**: Todas as requisições validadas
3. **Autenticação**: Laravel Breeze com hash bcrypt
4. **Autorização**: Middleware auth para rotas protegidas
5. **SQL Injection**: Proteção via Eloquent ORM
6. **XSS**: Escape automático no Blade

### Boas Práticas

- Senhas hasheadas com bcrypt
- Tokens de sessão seguros
- Validação de propriedade de recursos (Order → User)
- Logs de erros sem expor dados sensíveis

## Performance

### Otimizações Implementadas

1. **Eager Loading**: `with()` para evitar N+1 queries
2. **Índices**: Unique em slug, SKU
3. **Cache de Sessão**: Carrinho em sessão
4. **Paginação**: 12 produtos por página

### Comandos de Otimização

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## Próximas Expansões

A arquitetura está preparada para:

- **Queue Jobs**: Processamento assíncrono de emails
- **Cache Redis**: Cache de produtos e categorias
- **API REST**: Endpoints para mobile app
- **Elasticsearch**: Busca avançada de produtos
- **CDN**: Distribuição de imagens

---

**Arquitetura escalável e preparada para produção** 🏗️
