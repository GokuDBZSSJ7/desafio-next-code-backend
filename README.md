# HomeCare API — Laravel

API RESTful para gerenciamento de **clínicas de Home Care**, construída em **Laravel**. Este backend expõe recursos para **Pacientes, Profissionais e Serviços (agendamentos)** e foi pensado para integrar com um painel **Angular**.

---

## Sumário
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração do ambiente (.env)](#configuração-do-ambiente-env)
- [Migrações](#migrações)
- [Executando o servidor](#executando-o-servidor)
- [Estrutura relevante](#estrutura-relevante)
- [Modelos & Relacionamentos](#modelos--relacionamentos)
- [Validação (Form Requests)](#validação-form-requests)
- [Endpoints](#endpoints)
  - [Patients](#patients)
  - [Professionals](#professionals)
  - [Services](#services)
- [Paginação](#paginação)
- [CORS](#cors)
- [Erros comuns](#erros-comuns)
- [Roadmap](#roadmap)

---

## Tecnologias
- **PHP 8.2+**
- **Laravel 11** (estrutura base)
- Banco: **MySQL**
- Respostas com **API Resources**

## Requisitos
- PHP 8.2+, Composer
- MySQL rodando localmente
- Porta livre **8000** (ou ajuste o `APP_URL`)

---

## Instalação
```bash
# 1) Criar projeto (se ainda não criou)
composer create-project laravel/laravel homecare_api
cd homecare_api

# 2) Instalar dependências
composer install

# 3) (opcional) Sanctum para auth futura
composer require laravel/sanctum
```

---

## Configuração do ambiente (.env)
Crie ou edite o arquivo `.env` na raiz do projeto:

```env
APP_NAME="HomeCare API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=America/Sao_Paulo

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homecare_api
DB_USERNAME=root
DB_PASSWORD=

# Alternativa: SQLite (descomente para usar)
# DB_CONNECTION=sqlite
# Crie o arquivo: database/database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
FILESYSTEM_DISK=local

# Mail (dev)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@homecare.local
MAIL_FROM_NAME="${APP_NAME}"

# Frontend & CORS
FRONTEND_URL=http://localhost:4200
SANCTUM_STATEFUL_DOMAINS=localhost:4200
SESSION_DOMAIN=localhost
CORS_ALLOWED_ORIGINS=http://localhost:4200
```

Gere a chave da aplicação:
```bash
php artisan key:generate
```

---

## Migrações
As migrations criam as tabelas **patients**, **professionals** e **services**.

```bash
# Executar migrações
php artisan migrate
```

> As chaves estrangeiras usam `cascadeOnDelete()` para manter consistência.

---

## Executando o servidor
```bash
php artisan serve
# http://localhost:8000
```

---

## Estrutura relevante
```
app/
  Http/
    Controllers/
      PatientController.php
      ProfessionalController.php
      ServiceController.php
    Requests/
      StorePatientRequest.php
      UpdatePatientRequest.php
      StoreProfessionalRequest.php
      UpdateProfessionalRequest.php
      StoreServiceRequest.php
      UpdateServiceRequest.php
    Resources/
      PatientResource.php
      ProfessionalResource.php
      ServiceResource.php
  Models/
    Patient.php
    Professional.php
    Service.php

database/
  migrations/

routes/
  api.php
```

---

## Modelos & Relacionamentos
- **Patient** `hasMany(Service)`
- **Professional** `hasMany(Service)`
- **Service** `belongsTo(Patient)` e `belongsTo(Professional)`

Campos principais:
- Patient: `name`, `birth_date`, `address`, `contact_info`, `diagnosis?`
- Professional: `name`, `specialty`, `contact_info`
- Service: `patient_id`, `professional_id`, `service_type`, `scheduled_date`, `status (Agendado|Concluído|Cancelado)`

---

## Validação (Form Requests)
Todos os `store/update` usam **Form Requests** com regras de validação. Exemplos:
- `StoreServiceRequest`: exige `patient_id` e `professional_id` existentes, `service_type`, `scheduled_date` (data/hora) e `status` em `Agendado|Concluído|Cancelado`.

---

## Endpoints

Base URL: `{{APP_URL}}/api` (ex.: `http://localhost:8000/api`)

### Patients
- `GET /patients` — lista paginada
- `GET /patients/{id}` — detalhe
- `POST /patients` — cria
- `PUT /patients/{id}` — atualiza
- `DELETE /patients/{id}` — remove

**Exemplo (criar):**
```bash
curl -X POST http://localhost:8000/api/patients \
 -H "Content-Type: application/json" \
 -d '{
   "name":"Maria Silva",
   "birth_date":"1980-05-10",
   "address":"Rua X, 123",
   "contact_info":"(17) 99999-9999",
   "diagnosis":"Diabetes"
 }'
```

### Professionals
- `GET /professionals`
- `GET /professionals/{id}`
- `POST /professionals`
- `PUT /professionals/{id}`
- `DELETE /professionals/{id}`

**Exemplo (criar):**
```bash
curl -X POST http://localhost:8000/api/professionals \
 -H "Content-Type: application/json" \
 -d '{
   "name":"João Pereira",
   "specialty":"Enfermeiro",
   "contact_info":"(17) 98888-8888"
 }'
```

### Services
- `GET /services` — lista paginada **com** `patient` e `professional` incluídos
- `GET /services/{id}` — detalhe (também inclui relações)
- `POST /services` — cria um agendamento
- `PUT /services/{id}` — atualiza (status/data/tipo)
- `DELETE /services/{id}` — remove

**Exemplo (criar):**
```bash
curl -X POST http://localhost:8000/api/services \
 -H "Content-Type: application/json" \
 -d '{
   "patient_id": 1,
   "professional_id": 1,
   "service_type": "Curativo",
   "scheduled_date": "2025-08-14T14:30:00",
   "status": "Agendado"
 }'
```

**Resposta (trecho) `GET /services`:**
```json
{
  "data": [
    {
      "id": 10,
      "service_type": "Curativo",
      "scheduled_date": "2025-08-14 14:30:00",
      "status": "Agendado",
      "patient": { "id": 1, "name": "Maria Silva", "...": "..." },
      "professional": { "id": 1, "name": "João Pereira", "specialty": "Enfermeiro", "...": "..." },
      "created_at": "2025-08-14T12:00:00.000000Z",
      "updated_at": "2025-08-14T12:00:00.000000Z"
    }
  ],
  "meta": { "current_page": 1, "last_page": 1, "total": 1 }
}
```

---

## Paginação
Os `index` retornam **coleções paginadas** no padrão do Laravel (`data`, `meta`, `links`). Use `?page=2&per_page=20` conforme necessário (o `per_page` pode ser lido pelo controller se você desejar; por padrão está em 20).

---

## CORS
Para o Angular (`http://localhost:4200`), libere CORS em `config/cors.php`:
- `paths` → inclua `"api/*"`
- `allowed_origins` → inclua `http://localhost:4200` (ou use a env `CORS_ALLOWED_ORIGINS`)

> Em produção, ajuste para o domínio real do frontend.

---

## Erros comuns
- **`Method Illuminate\Http\Request::validated does not exist`**: use **Form Requests** (tipifique o método com `Store*/Update*Request`) ou chame `$request->validate([...])` quando usar `Illuminate\Http\Request` puro.
- **422 Unprocessable Entity**: erro de validação; verifique o payload.
- **404 Not Found**: id inexistente para `show/update/destroy`.

---

## Roadmap
- Filtros nos `index` (por status, data, paciente/profissional)
- Autenticação com Sanctum
- Documentação **OpenAPI/Swagger**
- Testes (Feature e Unit)
- Observabilidade (HTTP logging por middleware)

---

**Licença**: Uso livre para fins educacionais e projetos internos.
