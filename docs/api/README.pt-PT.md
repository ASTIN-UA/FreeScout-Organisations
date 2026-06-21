# OrgPortal REST API

[← Voltar ao README](../../README.md)

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

Autenticação — cabeçalho `X-FreeScout-API-Key` ou parâmetro de consulta `api_key`.

> **Documentação interativa** (ReDoc) está disponível na página **Gerir → API & Webhooks** (ligação "OrgPortal API Docs") ou diretamente em `/orgportal/admin/api-docs`.

## Pontos finais

| Método | Ponto final | Descrição |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listar organizações (paginação, filtro de caixa) |
| `POST` | `/api/organizations` | Criar uma organização |
| `GET` | `/api/organizations/{id}` | Obter organização com membros e unidades |
| `PUT` | `/api/organizations/{id}` | Atualizar organização (nome, cor, caixa, isActive) |
| `DELETE` | `/api/organizations/{id}` | Eliminar organização |
| `GET` | `/api/organizations/{id}/members` | Listar membros da organização |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Obter um único membro |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Atualizar membro (papel, unidade, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Remover um membro |
| `GET` | `/api/organizations/{id}/tags` | Listar ligações de etiquetas (requer módulo Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Substituir todas as ligações de etiquetas (requer módulo Tags) |
| `GET` | `/api/organizations/{id}/units` | Listar unidades estruturais |
| `POST` | `/api/organizations/{id}/units` | Criar uma unidade estrutural |
| `PUT` | `/api/units/{unitId}` | Renomear uma unidade |
| `DELETE` | `/api/units/{unitId}` | Eliminar uma unidade (membros removidos da atribuição, gestores rebaixados) |
| `GET` | `/api/customers/{id}/organization` | Associação de organização do cliente |
| `PUT` | `/api/customers/{id}/organization` | Definir/atualizar associação de cliente |
| `DELETE` | `/api/customers/{id}/organization` | Remover cliente da organização |

## Códigos de resposta

| Código | Significado |
|--------|------------|
| `200` | Sucesso |
| `201` | Recurso criado; cabeçalho `Resource-ID` contém o ID |
| `400` | Erro de validação — detalhes em `_embedded.errors` |
| `401` | Chave API inválida ou ausente |
| `404` | Recurso não encontrado |
| `409` | Conflito — cliente já tem uma associação ativa em outra organização |
| `422` | Violação de regra de negócio — ex. eliminar uma organização que ainda tem membros ou bilhetes |
| `503` | Módulo obrigatório (ex. Tags) não está ativo |

---

## Organizações

### GET /api/organizations

**Parâmetros de consulta**

| Parâmetro | Tipo | Predefinição | Descrição |
|-----------|------|:------:|-----------|
| `page` | número inteiro | `1` | Número da página |
| `pageSize` | número inteiro | `25` | Registos por página (máximo 100) |
| `mailboxId` | número inteiro | — | Filtro de caixa: devolve organizações globais + as vinculadas a esta caixa |

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
        "color": "#4a90d9",
        "isActive": true,
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

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Nome da organização (máximo 255 caracteres, único) |
| `mailboxId` | número inteiro\|null | — | ID da caixa ou `null` / omitir para organização global |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(cabeçalho `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Devolve a organização com os seus **membros** e **unidades** incorporados.

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "color": "#4a90d9",
  "isActive": true,
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
| `unitId` | número inteiro\|null | Unidade estrutural à qual o membro pertence, ou `null` para toda a organização |
| `role` | string | `member` ou `manager` |
| `canManageOrg` | boolean | Se este gestor pode promover outros a gestor global no portal |
| `isActive` | boolean | Associação ativa; membros inativos não recebem atribuições de bilhetes ou notificações |
| `notifyOnNewTicket` | boolean | Sinalizador de notificação por membro para novo bilhete |

---

### PUT /api/organizations/{id}

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Novo nome da organização (máximo 255 caracteres, único) |
| `color` | string\|null | — | Cor do emblema como hexadecimal (`"#ff0000"`), `null` para repor cinzento predefinido; omitir para manter atual |
| `mailboxId` | número inteiro\|null | — | Nova caixa; `null` — tornar global; omitir — deixar inalterado |
| `isActive` | boolean | — | `false` para desativar a organização; omitir para manter atual |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "color": "#4a90d9", "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

### DELETE /api/organizations/{id}

Bloqueado quando a organização tem membros ou bilhetes ativos. Remova todos os membros e reatribua/elimine todos os bilhetes primeiro.

**200 OK**
```json
{"success": true, "message": "Organization deleted."}
```

**422 Unprocessable Entity** *(organization has members)*
```json
{"message": "Cannot delete an organization that has members. Remove all members first.", "_embedded": {"errors": [{"members_count": 3}]}}
```

**422 Unprocessable Entity** *(organization has tickets)*
```json
{"message": "Cannot delete an organization that has tickets. Reassign or delete all tickets first.", "_embedded": {"errors": [{"conversations_count": 12}]}}
```

---

## Membros da organização

### GET /api/organizations/{id}/members

Devolve uma lista de todos os registos de membros da organização.

**200 OK**
```json
{
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
    ]
  }
}
```

---

### GET /api/organizations/{id}/members/{memberId}

Devolve um único registo de membro.

**200 OK**
```json
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
```

---

### PUT /api/organizations/{id}/members/{memberId}

Atualiza o papel, atribuição de unidade, sinalizador canManageOrg ou estado ativo de um membro. Apenas os campos presentes no corpo são atualizados (atualização parcial).

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `role` | string | — | `"member"` ou `"manager"` |
| `unitId` | número inteiro\|null | — | Unidade estrutural (deve pertencer a esta organização), ou `null` para remover |
| `canManageOrg` | boolean | — | Conceder direitos de gestor global no portal |
| `isActive` | boolean | — | `false` para desativar sem remover |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/members/5" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"role": "manager", "unitId": 2, "canManageOrg": true, "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Member updated."}
```

---

### DELETE /api/organizations/{id}/members/{memberId}

Remove um membro da organização.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Etiquetas de organização

> Requer que o módulo [Tags](https://freescout.net/module/tags/) esteja ativo. Devolve `503` se o módulo não estiver instalado.

### GET /api/organizations/{id}/tags

Devolve todas as ligações de etiquetas para a organização. Cada ligação pode opcionalmente limitar uma etiqueta a uma unidade específica.

**200 OK**
```json
{
  "_embedded": {
    "tags": [
      { "id": 1, "organizationId": 1, "tagId": 5, "unitId": null },
      { "id": 2, "organizationId": 1, "tagId": 8, "unitId": 2 }
    ]
  }
}
```

---

### PUT /api/organizations/{id}/tags

**Substituição completa** — substitui todas as ligações de etiquetas existentes para esta organização pela lista fornecida. Envie uma matriz vazia `[]` para remover todas as ligações.

**Corpo do pedido** — uma matriz JSON de objetos de ligação de etiquetas:

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `tagId` | número inteiro | ✅ | ID da etiqueta FreeScout |
| `unitId` | número inteiro\|null | — | Limitar a etiqueta a uma unidade específica, ou omitir/`null` para toda a organização |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/tags" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '[{"tagId": 5}, {"tagId": 8, "unitId": 2}]'
```

**200 OK**
```json
{"success": true, "message": "Tags updated."}
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

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `name` | string | ✅ | Nome da unidade (único dentro da organização) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(cabeçalho `Resource-ID: 2`)*
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

**Corpo do pedido**

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

Elimina a unidade. Gestores com escopo nesta unidade são rebaixados para `member`; todos os membros da unidade são removidos da atribuição (seu `unitId` passa a ser `null`).

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

Atribui um cliente a uma organização ou atualiza a sua associação. **Uma associação ativa por cliente**: se o cliente já tem uma associação *ativa* em *outra* organização, o pedido é rejeitado com `409 Conflict`. Para transferir — primeiro desative ou remova a associação atual via `DELETE`.

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `organizationId` | número inteiro | ✅ | ID da organização |
| `role` | string | — | `"member"` (predefinição) ou `"manager"` |
| `unitId` | número inteiro\|null | — | Unidade estrutural (deve pertencer à organização alvo), ou `null` para toda a organização |
| `canManageOrg` | boolean | — | Conceder a este gestor o direito de promover outros a gestor global (predefinição `false`) |
| `isActive` | boolean | — | `false` para criar/atualizar como inativo (predefinição `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
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
