# OrgPortal REST API

[← Voltar ao README](../README.pt-BR.md)

🌐 **Language:**
[English](README.md) ·
[Українська](README.uk.md) ·
[Deutsch](README.de.md) ·
[Français](README.fr.md) ·
[Español](README.es.md) ·
[Italiano](README.it.md) ·
[Polski](README.pl.md) ·
[Čeština](README.cs.md) ·
[Slovenčina](README.sk.md) ·
[Nederlands](README.nl.md) ·
[Norsk](README.no.md) ·
[Dansk](README.da.md) ·
[Svenska](README.sv.md) ·
[Suomi](README.fi.md) ·
[Português (BR)](README.pt-BR.md) ·
[Português (PT)](README.pt-PT.md) ·
[Română](README.ro.md) ·
[中文 (简体)](README.zh-CN.md)

---

*Opcional — requer o módulo [API e Webhooks](https://freescout.net/module/api-webhooks/).*

Autenticação — header `X-FreeScout-API-Key` ou parâmetro de query `api_key`.

> **Documentação interativa** (ReDoc) está disponível na página **Gerenciar → API & Webhooks** (link "OrgPortal API Docs") ou diretamente em `/orgportal/admin/api-docs`.

## Endpoints

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listar organizações (paginação, filtro de caixa) |
| `POST` | `/api/organizations` | Criar uma organização |
| `GET` | `/api/organizations/{id}` | Obter organização com membros e unidades |
| `PUT` | `/api/organizations/{id}` | Atualizar organização |
| `DELETE` | `/api/organizations/{id}` | Excluir organização |
| `GET` | `/api/organizations/{id}/units` | Listar unidades estruturais |
| `POST` | `/api/organizations/{id}/units` | Criar uma unidade estrutural |
| `PUT` | `/api/units/{unitId}` | Renomear uma unidade |
| `DELETE` | `/api/units/{unitId}` | Excluir uma unidade (membros removidos da atribuição, gerenciadores rebaixados) |
| `GET` | `/api/customers/{id}/organization` | Associação de organização do cliente |
| `PUT` | `/api/customers/{id}/organization` | Definir/atualizar associação de cliente |
| `DELETE` | `/api/customers/{id}/organization` | Remover cliente da organização |

## Códigos de resposta

| Código | Significado |
|--------|------------|
| `200` | Sucesso ou nenhuma mudança (nada foi alterado) |
| `201` | Recurso criado; header `Resource-ID` contém o ID |
| `400` | Erro de validação — detalhes em `_embedded.errors` |
| `401` | Chave API inválida ou ausente |
| `404` | Recurso não encontrado |
| `409` | Conflito — cliente já tem uma associação ativa em outra organização |

---

## Organizações

### GET /api/organizations

**Parâmetros de query**

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|:------:|-----------|
| `page` | inteiro | `1` | Número da página |
| `pageSize` | inteiro | `25` | Registros por página (máximo 100) |
| `mailboxId` | inteiro | — | Filtro de caixa: retorna organizações globais + as vinculadas a essa caixa |

```bash
curl -X GET "https://your-freescout.com/api/organizations?mailboxId=3" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "_embedded": {
    "organizations": [
      {
        "id": 1,
        "name": "Acme Corp",
        "mailboxId": null,
        "createdAt": "2026-06-01T10:00:00+00:00",
        "updatedAt": "2026-06-01T10:00:00+00:00"
      }
    ]
  },
  "page": { "size": 25, "totalElements": 1, "totalPages": 1, "number": 1 }
}
```

---

### POST /api/organizations

**Corpo da solicitação**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Nome da organização (máximo 255 caracteres, único) |
| `mailboxId` | inteiro\|null | — | ID da caixa ou `null` / omitir para organização global |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(header `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Retorna a organização com seus **membros** e **unidades** embutidos.

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": null,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00",
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "unitId": 2,
        "customerId": 42,
        "role": "manager",
        "canManageOrg": false,
        "isActive": true,
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ],
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

**Campos de membro**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `unitId` | inteiro\|null | Unidade estrutural à qual o membro pertence, ou `null` para toda a organização |
| `role` | string | `member` ou `manager` |
| `canManageOrg` | boolean | Se este gerenciador pode promover outros a gerenciador global no portal |
| `isActive` | boolean | Associação ativa; membros inativos não recebem atribuições de tíquetes ou notificações |
| `notifyOnNewTicket` | boolean | Sinalizador herdado de notificação por membro de novo tíquete |

---

### PUT /api/organizations/{id}

**Corpo da solicitação**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Novo nome da organização (máximo 255 caracteres, único) |
| `mailboxId` | inteiro\|null | — | Nova caixa; `null` — tornar global; omitir — deixar inalterado |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "mailboxId": null}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

Quando nada muda, a mensagem de resposta é `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(todos os membros são excluídos em cascata)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Unidades estruturais

### GET /api/organizations/{id}/units

**200 OK**
```json
{
  "_embedded": {
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

---

### POST /api/organizations/{id}/units

**Corpo da solicitação**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Nome da unidade (único dentro da organização) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(header `Resource-ID: 2`)*
```json
{
  "id": 2,
  "organizationId": 1,
  "name": "Sales department",
  "createdAt": "2026-06-01T10:02:00+00:00",
  "updatedAt": "2026-06-01T10:02:00+00:00"
}
```

---

### PUT /api/units/{unitId}

**Corpo da solicitação**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Novo nome da unidade (único dentro da organização) |

```bash
curl -X PUT "https://your-freescout.com/api/units/2" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales & Marketing"}'
```

**200 OK**
```json
{"success": true, "message": "Unit updated."}
```

---

### DELETE /api/units/{unitId}

Exclui a unidade. Gerenciadores com escopo nesta unidade são rebaixados para `member`; todos os membros da unidade são removidos da atribuição (seu `unitId` se torna `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Associação de cliente

### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "unitId": 2,
  "unitName": "Sales department",
  "role": "manager",
  "canManageOrg": false,
  "isActive": true,
  "notifyOnNewTicket": true
}
```

---

### PUT /api/customers/{id}/organization

Atribui um cliente a uma organização ou atualiza sua associação. **Uma associação ativa por cliente**: se o cliente já tem uma associação *ativa* em *outra* organização, a solicitação é rejeitada com `409 Conflict`. Para transferir — primeiro desative ou remova a associação atual via `DELETE`.

**Corpo da solicitação**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `organizationId` | inteiro | ✅ | ID da organização |
| `role` | string | — | `"member"` (padrão) ou `"manager"` |
| `unitId` | inteiro\|null | — | Unidade estrutural (deve pertencer à organização alvo), ou `null` para toda a organização |
| `canManageOrg` | boolean | — | Conceder a este gerenciador o direito de promover outros a gerenciador global (padrão `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nova associação)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(associação atualizada)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente já ativo em outra organização)*
```json
{
  "message": "Customer already has an active membership in another organization.",
  "errorCode": "CUSTOMER_ALREADY_HAS_AN_ACTIVE_MEMBERSHIP_IN_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is an active member of organization #3. Deactivate or remove it first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```
