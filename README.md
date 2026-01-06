# CRM - Customer Relationship Management API

API RESTful para gerenciamento de relacionamento com clientes, construída com Laravel 11 e Laravel Sanctum.

## 🚀 Tecnologias

- **PHP 8.2+**
- **Laravel 11.9**
- **Laravel Sanctum 4.0** (Autenticação API)
- **MySQL 8.0**
- **Redis** (Cache e sessões)
- **Docker** (via Laravel Sail)
- **PHPUnit** (Testes)
- **Laravel Pint** (Code Style)

## 📋 Funcionalidades

### ✅ Implementadas

- **Autenticação**
  - Registro de usuários
  - Login com token JWT
  - Logout
  - Consulta de usuário autenticado

- **Gestão de Clientes**
  - Listagem com paginação
  - Criação de clientes
  - Visualização individual
  - Atualização de dados
  - Exclusão (soft delete)
  - Validação de dados
  - Autorização via Policies

- **Dashboard**
  - Estatísticas de clientes
  - Novos clientes (mês/semana)
  - Últimos 5 clientes cadastrados

- **Segurança**
  - Autenticação via Laravel Sanctum
  - Rate limiting (60 requisições/minuto)
  - Validação robusta de dados
  - Políticas de autorização

## 📦 Instalação

### Pré-requisitos

- Docker e Docker Compose
- PHP 8.2+ (apenas para rodar localmente sem Docker)
- Composer

### Passo a Passo

1. **Clone o repositório**
```bash
git clone <repository-url>
cd crm
```

2. **Copie o arquivo de ambiente**
```bash
cp .env.example .env
```

3. **Instale as dependências**
```bash
composer install
```

4. **Gere a chave da aplicação**
```bash
php artisan key:generate
```

5. **Inicie o Docker (Laravel Sail)**
```bash
./vendor/bin/sail up -d
```

6. **Execute as migrations e seeders**
```bash
./vendor/bin/sail artisan migrate --seed
```

7. **Acesse a aplicação**
- API: `http://localhost/api/v1`

## 🧪 Testes

Execute os testes com PHPUnit:

```bash
./vendor/bin/sail artisan test
```

Ou com coverage:

```bash
./vendor/bin/sail artisan test --coverage
```

### Cobertura de Testes

- ✅ Testes Feature para CustomerController (15 testes)
- ✅ Testes Feature para AuthController (11 testes)
- ✅ Testes Unit para Customer Model (7 testes)
- ✅ Testes Unit para User Model (6 testes)

## 📚 Documentação da API

### Base URL

```
http://localhost/api/v1
```

### Autenticação

A API usa **Bearer Token** para autenticação. Adicione o header:

```
Authorization: Bearer {seu-token-aqui}
```

### Endpoints Públicos

#### Registrar Usuário

```http
POST /api/v1/register
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Resposta (201):**
```json
{
  "message": "Usuário registrado com sucesso.",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  },
  "access_token": "1|xxxxxxxxxxxxx",
  "token_type": "Bearer"
}
```

#### Login

```http
POST /api/v1/login
Content-Type: application/json

{
  "email": "joao@example.com",
  "password": "password123"
}
```

**Resposta (200):**
```json
{
  "message": "Login realizado com sucesso.",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  },
  "access_token": "2|xxxxxxxxxxxxx",
  "token_type": "Bearer"
}
```

### Endpoints Protegidos (Requerem Autenticação)

#### Logout

```http
POST /api/v1/logout
Authorization: Bearer {token}
```

**Resposta (200):**
```json
{
  "message": "Logout realizado com sucesso."
}
```

#### Usuário Autenticado

```http
GET /api/v1/me
Authorization: Bearer {token}
```

**Resposta (200):**
```json
{
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  }
}
```

#### Dashboard

```http
GET /api/v1/dashboard
Authorization: Bearer {token}
```

**Resposta (200):**
```json
{
  "customers": {
    "total": 50,
    "new_this_month": 12,
    "new_this_week": 3
  },
  "users": {
    "total": 5
  },
  "recent_customers": [
    {
      "id": 50,
      "full_name": "Maria Santos",
      "email": "maria@example.com",
      "created_at": "2025-01-06 10:30:00"
    }
  ]
}
```

#### Listar Clientes

```http
GET /api/v1/customers?page=1
Authorization: Bearer {token}
```

**Resposta (200):**
```json
{
  "data": [
    {
      "id": 1,
      "first_name": "João",
      "last_name": "Silva",
      "full_name": "João Silva",
      "email": "joao@example.com",
      "phone": "11999999999",
      "created_at": "2025-01-06 10:00:00",
      "updated_at": "2025-01-06 10:00:00"
    }
  ],
  "links": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50
  }
}
```

#### Criar Cliente

```http
POST /api/v1/customers
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "Maria",
  "last_name": "Santos",
  "email": "maria@example.com",
  "phone": "11988888888"
}
```

**Resposta (201):**
```json
{
  "data": {
    "id": 2,
    "first_name": "Maria",
    "last_name": "Santos",
    "full_name": "Maria Santos",
    "email": "maria@example.com",
    "phone": "11988888888",
    "created_at": "2025-01-06 11:00:00",
    "updated_at": "2025-01-06 11:00:00"
  }
}
```

#### Visualizar Cliente

```http
GET /api/v1/customers/{id}
Authorization: Bearer {token}
```

#### Atualizar Cliente

```http
PUT /api/v1/customers/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "Maria",
  "last_name": "Santos Silva",
  "email": "maria@example.com",
  "phone": "11988888888"
}
```

#### Deletar Cliente

```http
DELETE /api/v1/customers/{id}
Authorization: Bearer {token}
```

**Resposta (200):**
```json
{
  "message": "Cliente removido com sucesso."
}
```

### Códigos de Status HTTP

- `200 OK` - Requisição bem-sucedida
- `201 Created` - Recurso criado com sucesso
- `204 No Content` - Requisição bem-sucedida sem conteúdo
- `400 Bad Request` - Dados inválidos
- `401 Unauthorized` - Não autenticado
- `403 Forbidden` - Não autorizado
- `404 Not Found` - Recurso não encontrado
- `422 Unprocessable Entity` - Erro de validação
- `429 Too Many Requests` - Rate limit excedido
- `500 Internal Server Error` - Erro do servidor

## 🔒 Segurança

- Autenticação via Laravel Sanctum (tokens)
- Rate limiting: 60 requisições por minuto
- Validação de dados em Form Requests
- Autorização via Policies
- Soft Deletes para clientes
- Senhas hasheadas com bcrypt
- Proteção contra SQL Injection (Eloquent ORM)
- CSRF protection

## 🏗️ Arquitetura

### Padrões Utilizados

- **MVC** (Model-View-Controller)
- **Repository Pattern** (via Eloquent)
- **Form Requests** (Validação)
- **API Resources** (Serialização)
- **Policies** (Autorização)
- **Soft Deletes** (Exclusão lógica)

### Estrutura de Diretórios

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CustomerController.php
│   │   └── DashboardController.php
│   ├── Requests/
│   │   ├── StoreCustomerRequest.php
│   │   └── UpdateCustomerRequest.php
│   └── Resources/
│       └── CustomerResource.php
├── Models/
│   ├── Customer.php
│   └── User.php
└── Policies/
    └── CustomerPolicy.php
```

## 🛠️ Desenvolvimento

### Code Style

Execute o Laravel Pint para formatar o código:

```bash
./vendor/bin/sail pint
```

### IDE Helper

Para melhor autocomplete no IDE:

```bash
./vendor/bin/sail artisan ide-helper:generate
./vendor/bin/sail artisan ide-helper:models
```

## 📝 Próximos Passos

### Funcionalidades Futuras

- [ ] Sistema de roles e permissões
- [ ] Gestão de negócios (deals)
- [ ] Pipeline de vendas
- [ ] Histórico de atividades
- [ ] Tags e categorias
- [ ] Anexos e documentos
- [ ] Notificações
- [ ] Relatórios avançados
- [ ] Exportação de dados (CSV, PDF)
- [ ] Busca e filtros avançados
- [ ] Integração com e-mail
- [ ] WebSockets para atualizações em tempo real

### Melhorias Técnicas

- [ ] Cache de queries
- [ ] Jobs e Queues para operações pesadas
- [ ] Events e Listeners
- [ ] Observadores (Observers)
- [ ] API Documentation (Swagger/OpenAPI)
- [ ] CI/CD Pipeline
- [ ] Monitoramento e logs

## 📄 Licença

Este projeto está sob a licença [MIT](https://opensource.org/licenses/MIT).

## 👥 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/NovaFuncionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/NovaFuncionalidade`)
5. Abra um Pull Request

## 📧 Contato

Para dúvidas ou sugestões, entre em contato através do email ou abra uma issue no repositório.

---

Desenvolvido com ❤️ usando Laravel 11
