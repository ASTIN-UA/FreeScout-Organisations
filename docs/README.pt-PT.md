# OrgPortal — Portal de Organização para FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Um módulo FreeScout que adiciona o conceito de **Organizações** (empresas/equipas) aos clientes, estende o Portal de Utilizador Final para gestores e exibe um distintivo de organização em bilhetes e cartões Kanban.

**Versão mínima do FreeScout:** 1.8.147  
**Dependências:** nenhuma obrigatória  
**Opcionais:** [Portal de Utilizador Final](https://freescout.net/module/end-user-portal/), [API e Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Idioma:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funcionalidades

### Gestão de organizações (admin)
- **Gerir → Organizações** — CRUD completo: criar, editar, eliminar organizações
- **Vinculação da caixa de correio** — uma organização pode ser **global** (visível em todas as caixas) ou **vinculada a uma caixa específica**; o rótulo correspondente é mostrado na lista de organizações
- Atribuir clientes a organizações com selecção de função: `membro` ou `gestor`
- **Alterar função do membro** directamente na tabela (sem remover e readicionar)
- Preenchimento automático de pesquisa de cliente por nome ou e-mail; clientes já em qualquer organização são excluídos dos resultados
- O e-mail do membro é exibido sob o nome na tabela de membros
- Um cliente — uma organização (aplicado ao nível da base de dados e da API)
- **Cor do distintivo** — paleta visual com 12 cores no formulário de edição de organização; padrão é cinzento

### Permissões de utilizador
- Nova permissão **"Permitir gestão de organizações"** — não-administradores com esta permissão obtêm acesso às páginas de lista, criar e editar organizações
- Eliminar organizações permanece exclusivo para administradores

### Cartão de cliente
- Campo **Organização** no formulário de edição de cliente — seleccione organização e função
- Botão **Bilhetes da Organização** — abre uma pesquisa para todos os bilhetes da organização

### Distintivo de organização em bilhetes
- Exibido abaixo do assunto na página do bilhete e antes do nome na lista de conversas
- Clicável — abre uma pesquisa para todos os bilhetes desta organização
- A cor do distintivo é determinada pela configuração da organização (padrão cinzento)
- Activar/desactivar **por caixa de correio** via **Configurações da Caixa → OrgPortal**; o valor global é usado como alternativa

### Distintivo de organização em cartões Kanban
- Exibido após o contador de mensagens em cada cartão
- Clicável — leva à pesquisa de organização
- A cor corresponde à configuração da organização
- Filtro de **Organização** integrado ao dropdown de filtro Kanban padrão: modal com caixas de selecção, similar ao filtro de etiquetas; o estado é preservado entre navegações
- Activar/desactivar **por caixa de correio** via **Configurações da Caixa → OrgPortal**

### Filtro de pesquisa de organização
- Estende a pesquisa padrão do FreeScout com um filtro de **Organização**
- Mostra todos os bilhetes de clientes que pertencem à organização seleccionada

### Portal de Utilizador Final — acesso do gestor *(opcional)*

Um gestor de organização obtém acesso estendido através do EUP:

- Item **Bilhetes da Empresa** na navegação do portal
- Tabela de bilhetes da empresa com colunas:
  - **#** e **Assunto** com truncagem de elipse e dica ao passar o rato
  - **Responsável** — agente atribuído
  - **Autor** — o cliente que abriu o bilhete; clique filtra bilhetes por autor dentro da organização
  - **Estado** — Activo / Pendente / Fechado / Spam com ícones
  - **Posição** — nome da coluna Kanban (com rótulo personalizado se configurado); mostrado apenas se o módulo Kanban está activo
  - **Actualizado** — data e hora da última resposta
- Pesquisa por assunto do bilhete
- Filtros por estado Kanban (configurável via **Configurações da Caixa → OrgPortal**)
- Responder ao bilhete com suporte a **anexo** (arrastar e soltar, múltiplos ficheiros)
- **Fechar bilhete** — gestor pode fechar um bilhete; uma nova resposta o reabre automaticamente
- Alterar autor do bilhete — reatribuir um bilhete a outro membro da organização
- Página **Configurações da Org** para configurar notificações por e-mail
- O acesso ao bilhete é **estritamente limitado à caixa de correio actual** (organização copiada para outra caixa — portal 403)

### Notificações por e-mail *(opcional)*
- Gestores com a opção activada recebem um e-mail quando um novo bilhete é criado por qualquer membro da organização
- Usa o controlador de e-mail da caixa de correio correspondente

### Configurações da caixa de correio

**Configurações da Caixa → OrgPortal** (por caixa de correio):

| Opção | Descrição |
|-------|-----------|
| Mostrar distintivo na página do bilhete | Activar/desactivar distintivo nesta caixa de correio |
| Mostrar distintivo em cartões Kanban | Activar/desactivar distintivo nesta caixa de correio |
| Filtros de estado de bilhetes da empresa | Seleccionar colunas Kanban exibidas como caixas de selecção na página de bilhetes; rótulo personalizado para cada filtro |

---

### REST API *(opcional, requer API e Webhooks)*

Autenticação — cabeçalho `X-FreeScout-API-Key` ou parâmetro de consulta `api_key`.

> **Documentação interactiva** (ReDoc) está disponível na página **Gerir → API & Webhooks** (link "OrgPortal API Docs") ou directamente em `/orgportal/admin/api-docs`.

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listar organizações (paginação, filtro de caixa) |
| `POST` | `/api/organizations` | Criar uma organização |
| `GET` | `/api/organizations/{id}` | Obter organização com membros |
| `PUT` | `/api/organizations/{id}` | Actualizar organização |
| `DELETE` | `/api/organizations/{id}` | Eliminar organização |
| `GET` | `/api/customers/{id}/organization` | Organização do cliente |
| `PUT` | `/api/customers/{id}/organization` | Definir/actualizar associação de cliente |
| `DELETE` | `/api/customers/{id}/organization` | Remover cliente da organização |

#### Códigos de resposta

| Código | Significado |
|--------|------------|
| `200` | Sucesso ou nenhuma alteração |
| `201` | Recurso criado; cabeçalho `Resource-ID` contém o ID |
| `400` | Erro de validação — detalhes em `_embedded.errors` |
| `401` | Chave API inválida ou ausente |
| `404` | Recurso não encontrado |
| `409` | Conflito — cliente já pertence a outra organização |

---

#### GET /api/organizations

**Parâmetros de consulta**

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|:------:|-----------|
| `page` | inteiro | `1` | Número da página |
| `pageSize` | inteiro | `25` | Registos por página (máximo 100) |
| `mailboxId` | inteiro | — | Filtro de caixa: devolve organizações globais + as vinculadas a essa caixa |

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

#### POST /api/organizations

**Corpo do pedido**

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

**201 Created** *(cabeçalho `Resource-ID: 1`)*
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

#### GET /api/organizations/{id}

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
        "customerId": 42,
        "role": "manager",
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

#### PUT /api/organizations/{id}

**Corpo do pedido**

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

---

#### DELETE /api/organizations/{id}

**200 OK** *(todos os membros são eliminados em cascata)*
```json
{"success": true, "message": "Organization deleted."}
```

---

#### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "role": "manager",
  "notifyOnNewTicket": true
}
```

---

#### PUT /api/customers/{id}/organization

Atribui um cliente a uma organização ou actualiza sua função. **Um cliente — uma organização**: se o cliente já é membro de *outra* organização, o pedido é rejeitado com `409 Conflict`. Para transferir — primeiro remova a associação actual via `DELETE`.

**Corpo do pedido**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|:----------:|-----------|
| `organizationId` | inteiro | ✅ | ID da organização |
| `role` | string | — | `"member"` (padrão) ou `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(nova associação)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(função actualizada ou nenhuma alteração)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente já em outra organização)*
```json
{
  "message": "Customer already belongs to another organization.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is already a member of organization #3. Remove the existing membership first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

#### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

---

## Instalação

1. Copie a pasta `OrgPortal` em `Modules/` do seu FreeScout
2. No painel de administração: **Gerir → Módulos → OrgPortal → Activar**
3. Execute as migrações:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Limpe a cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Compatibilidade de módulos

| Módulo | Estado |
|--------|--------|
| Portal de Utilizador Final ≥ 1.0.85 | Opcional — recursos do portal para gestores |
| API e Webhooks ≥ 1.0.80 | Opcional — endpoints da API REST |
| Kanban ≥ 1.0.23 | Opcional — distintivo, filtro, coluna "Posição" em bilhetes da empresa |
| Custom Fields | Compatível |
| Workflows | Compatível |
| Tags | Compatível |

---

## Configuração

### Global (**Gerir → Configurações do OrgPortal**)

| Opção | Padrão |
|-------|--------|
| Mostrar distintivo na página do bilhete | ✅ |
| Mostrar distintivo em cartões Kanban | ✅ |

### Por caixa de correio (**Configurações da Caixa → OrgPortal**)

Substitui os valores globais para a caixa específica.

| Opção | Descrição |
|-------|-----------|
| Mostrar distintivo na página do bilhete | Distintivo na lista de conversas e na página do bilhete |
| Mostrar distintivo em cartões Kanban | Distintivo em cartões Kanban |
| Filtros de estado de bilhetes da empresa | Colunas Kanban como caixas de selecção na página de bilhetes da empresa; cada filtro possui um rótulo personalizado visível aos utilizadores do portal |

---

## Traduções

Idiomas suportados: **Inglês** (`en`), **Ucraniano** (`uk`), **Romeno** (`ro`), **Georgiano** (`ka`), **Alemão** (`de`), **Francês** (`fr`), **Espanhol** (`es`), **Italiano** (`it`), **Tcheco** (`cs`), **Eslovaco** (`sk`), **Polonês** (`pl`), **Russo** (`ru`), **Holandês** (`nl`), **Norueguês** (`no`), **Dinamarquês** (`da`), **Sueco** (`sv`), **Finlandês** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **Chinês Simplificado** (`zh-CN`).

Ficheiros: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integração com EUPSWLANG

O módulo funciona correctamente com [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): o idioma seleccionado no portal também se aplica às strings do OrgPortal.

Para um idioma aparecer na lista EUPSWLANG, o ficheiro `Modules/EndUserPortal/Resources/lang/{locale}.json` correspondente deve existir. Ficheiros para **Romeno** (`ro`) estão inclusos no pacote; **Georgiano** (`ka`) é suportado apenas na secção de administração (sem suporte no sistema FreeScout core).

> **Detalhe técnico:** O middleware `ReapplyEupLocale` (registado por último no grupo de rotas do portal) restaura o locale após o middleware `Localize` do FreeScout, que de outra forma redefinir o idioma seleccionado do portal para o padrão do sistema.

---

## Licença

Proprietary — ASTIN UA.
