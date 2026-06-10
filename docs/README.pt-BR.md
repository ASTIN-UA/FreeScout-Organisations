# OrgPortal — Portal de Organização para FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Um módulo FreeScout que adiciona o conceito de **Organizações** (empresas/equipes) aos clientes, estende o Portal de Usuário Final para gerenciadores e exibe um distintivo de organização em tíquetes e cartões Kanban.

**Versão mínima do FreeScout:** 1.8.147  
**Dependências:** nenhuma obrigatória  
**Opcionais:** [Portal de Usuário Final](https://freescout.net/module/end-user-portal/), [API e Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Idioma:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Recursos

### Gerenciamento de organizações (admin)
- **Gerenciar → Organizações** — CRUD completo: criar, editar, excluir organizações
- **Vinculação da caixa de correio** — uma organização pode ser **global** (visível em todas as caixas) ou **vinculada a uma caixa específica**; o rótulo correspondente é mostrado na lista de organizações
- Atribuir clientes a organizações com seleção de função: `membro` ou `gerenciador`
- **Alterar função do membro** diretamente na tabela (sem remover e readicionar)
- Preenchimento automático de pesquisa de cliente por nome ou email; clientes já em qualquer organização são excluídos dos resultados
- O email do membro é exibido sob o nome na tabela de membros
- Um cliente — uma organização (aplicado no nível do banco de dados e da API)
- **Cor do distintivo** — paleta visual com 12 cores no formulário de edição de organização; padrão é cinza

### Permissões de usuário
- Nova permissão **"Permitir gerenciamento de organizações"** — não-administradores com esta permissão obtêm acesso aos páginas de lista, criar e editar organizações
- Excluir organizações permanece exclusivo para administradores

### Cartão de cliente
- Campo **Organização** no formulário de edição de cliente — selecione organização e função
- Botão **Tíquetes da Organização** — abre uma pesquisa para todos os tíquetes da organização

### Distintivo de organização em tíquetes
- Exibido abaixo do assunto na página do tíquete e antes do nome na lista de conversas
- Clicável — abre uma pesquisa para todos os tíquetes desta organização
- A cor do distintivo é determinada pela configuração da organização (padrão cinza)
- Ativar/desativar **por caixa de correio** via **Configurações da Caixa → OrgPortal**; o valor global é usado como fallback

### Distintivo de organização em cartões Kanban
- Exibido após o contador de mensagens em cada cartão
- Clicável — leva à pesquisa de organização
- A cor corresponde à configuração da organização
- Filtro de **Organização** integrado ao dropdown de filtro Kanban padrão: modal com caixas de seleção, similar ao filtro de tags; o estado é preservado entre navegações
- Ativar/desativar **por caixa de correio** via **Configurações da Caixa → OrgPortal**

### Filtro de pesquisa de organização
- Estende a pesquisa padrão do FreeScout com um filtro de **Organização**
- Mostra todos os tíquetes de clientes que pertencem à organização selecionada

### Portal de Usuário Final — acesso do gerenciador *(opcional)*

Um gerenciador de organização obtém acesso estendido através do EUP:

- Item **Tíquetes da Empresa** na navegação do portal
- Tabela de tíquetes da empresa com colunas:
  - **#** e **Assunto** com truncamento de elipse e tooltip ao passar o mouse
  - **Responsável** — agente atribuído
  - **Autor** — o cliente que abriu o tíquete; clique filtra tíquetes por autor dentro da organização
  - **Status** — Ativo / Pendente / Fechado / Spam com ícones
  - **Estado** — nome da coluna Kanban (com rótulo personalizado se configurado); mostrado apenas se o módulo Kanban está ativo
  - **Atualizado** — data e hora da última resposta
- Pesquisa por assunto do tíquete
- Filtros por status Kanban (configurável via **Configurações da Caixa → OrgPortal**)
- Responder ao tíquete com suporte a **anexo** (arrastar e soltar, múltiplos arquivos)
- **Fechar tíquete** — gerenciador pode fechar um tíquete; uma nova resposta o reabre automaticamente
- Alterar autor do tíquete — reatribuir um tíquete a outro membro da organização
- Página **Configurações da Org** para configurar notificações por email
- O acesso ao tíquete é **estritamente limitado à caixa de correio atual** (organização copiada para outra caixa — portal 403)

### Notificações por email *(opcional)*
- Gerenciadores com a opção ativada recebem um email quando um novo tíquete é criado por qualquer membro da organização
- Usa o driver de email da caixa de correio correspondente

### Configurações da caixa de correio

**Configurações da Caixa → OrgPortal** (por caixa de correio):

| Opção | Descrição |
|-------|-----------|
| Mostrar distintivo na página do tíquete | Ativar/desativar distintivo nesta caixa de correio |
| Mostrar distintivo em cartões Kanban | Ativar/desativar distintivo nesta caixa de correio |
| Filtros de status de tíquetes da empresa | Selecionar colunas Kanban exibidas como caixas de seleção na página de tíquetes; rótulo personalizado para cada filtro |

---

### REST API *(opcional, requer API e Webhooks)*

Autenticação — header `X-FreeScout-API-Key` ou parâmetro de query `api_key`.

> **Documentação interativa** (ReDoc) está disponível na página **Gerenciar → API & Webhooks** (link "OrgPortal API Docs") ou diretamente em `/orgportal/admin/api-docs`.

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listar organizações (paginação, filtro de caixa) |
| `POST` | `/api/organizations` | Criar uma organização |
| `GET` | `/api/organizations/{id}` | Obter organização com membros |
| `PUT` | `/api/organizations/{id}` | Atualizar organização |
| `DELETE` | `/api/organizations/{id}` | Excluir organização |
| `GET` | `/api/customers/{id}/organization` | Organização do cliente |
| `PUT` | `/api/customers/{id}/organization` | Definir/atualizar associação de cliente |
| `DELETE` | `/api/customers/{id}/organization` | Remover cliente da organização |

#### Códigos de resposta

| Código | Significado |
|--------|------------|
| `200` | Sucesso ou nenhuma mudança |
| `201` | Recurso criado; header `Resource-ID` contém o ID |
| `400` | Erro de validação — detalhes em `_embedded.errors` |
| `401` | Chave API inválida ou ausente |
| `404` | Recurso não encontrado |
| `409` | Conflito — cliente já pertence a outra organização |

---

#### GET /api/organizations

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

#### POST /api/organizations

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

---

#### DELETE /api/organizations/{id}

**200 OK** *(todos os membros são excluídos em cascata)*
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

Atribui um cliente a uma organização ou atualiza sua função. **Um cliente — uma organização**: se o cliente já é membro de *outra* organização, a solicitação é rejeitada com `409 Conflict`. Para transferir — primeiro remova a associação atual via `DELETE`.

**Corpo da solicitação**

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

**200 OK** *(função atualizada ou nenhuma mudança)*
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
2. No painel de administração: **Gerenciar → Módulos → OrgPortal → Ativar**
3. Execute as migrações:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Limpe o cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Atualizações

OrgPortal suporta **atualizações automáticas** através do mecanismo de atualização de módulo integrado do FreeScout.

Quando uma nova versão está disponível, um banner aparece na página **Gerenciar → Módulos**. Clique em **Atualizar agora** — FreeScout fará download e instalará a versão mais recente automaticamente.

Nenhuma cópia manual de arquivo necessária.

---

## Compatibilidade de módulos

| Módulo | Status |
|--------|--------|
| Portal de Usuário Final ≥ 1.0.85 | Opcional — recursos do portal para gerenciadores |
| API e Webhooks ≥ 1.0.80 | Opcional — endpoints da API REST |
| Kanban ≥ 1.0.23 | Opcional — distintivo, filtro, coluna "Estado" em tíquetes da empresa |
| Custom Fields | Compatível |
| Workflows | Compatível |
| Tags | Compatível |

---

## Configuração

### Global (**Gerenciar → Configurações do OrgPortal**)

| Opção | Padrão |
|-------|--------|
| Mostrar distintivo na página do tíquete | ✅ |
| Mostrar distintivo em cartões Kanban | ✅ |

### Por caixa de correio (**Configurações da Caixa → OrgPortal**)

Substitui os valores globais para a caixa específica.

| Opção | Descrição |
|-------|-----------|
| Mostrar distintivo na página do tíquete | Distintivo na lista de conversas e na página do tíquete |
| Mostrar distintivo em cartões Kanban | Distintivo em cartões Kanban |
| Filtros de status de tíquetes da empresa | Colunas Kanban como caixas de seleção na página de tíquetes da empresa; cada filtro possui um rótulo personalizado visível aos usuários do portal |

---

## Traduções

Idiomas suportados: **Inglês** (`en`), **Ucraniano** (`uk`), **Romeno** (`ro`), **Georgiano** (`ka`), **Alemão** (`de`), **Francês** (`fr`), **Espanhol** (`es`), **Italiano** (`it`), **Tcheco** (`cs`), **Eslovaco** (`sk`), **Polonês** (`pl`), **Russo** (`ru`), **Holandês** (`nl`), **Norueguês** (`no`), **Dinamarquês** (`da`), **Sueco** (`sv`), **Finlandês** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **Chinês Simplificado** (`zh-CN`).

Arquivos: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integração com EUPSWLANG

O módulo funciona corretamente com [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): o idioma selecionado no portal também se aplica às strings do OrgPortal.

Para um idioma aparecer na lista EUPSWLANG, o arquivo `Modules/EndUserPortal/Resources/lang/{locale}.json` correspondente deve existir. Arquivos para **Romeno** (`ro`) estão inclusos no pacote; **Georgiano** (`ka`) é suportado apenas na seção de administração (sem suporte no sistema FreeScout core).

> **Detalhe técnico:** O middleware `ReapplyEupLocale` (registrado por último no grupo de rotas do portal) restaura o locale após o middleware `Localize` do FreeScout, que de outra forma redefinir o idioma selecionado do portal para o padrão do sistema.

---

## Licença

Proprietary — ASTIN UA.
