# OrgPortal — Módulo de Gerenciamento de Organizações B2B para FreeScout

[← Voltar ao README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B module" width="140" align="right">

**OrgPortal** é um módulo para FreeScout que adiciona **gerenciamento completo de organizações B2B** ao seu helpdesk: agrupe clientes em empresas, defina hierarquias departamentais, ofereça um portal de autoatendimento para gestores corporativos e automatize notificações — tudo dentro do FreeScout, sem ferramentas externas.

> Procurando uma forma de gerenciar contas empresariais no FreeScout? De oferecer aos clientes corporativos um portal de suporte próprio? De controlar quais tickets cada contato B2B pode ver com base no seu cargo e departamento? OrgPortal resolve tudo isso.

**Compatível com:** FreeScout 1.8.147+  
**Integrações opcionais:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Também disponível em:**
[Українська](docs/README.uk.md) · [Deutsch](docs/README.de.md) · [Français](docs/README.fr.md) · [Español](docs/README.es.md) · [Italiano](docs/README.it.md) · [Polski](docs/README.pl.md) · [Čeština](docs/README.cs.md) · [Slovenčina](docs/README.sk.md) · [Nederlands](docs/README.nl.md) · [Norsk](docs/README.no.md) · [Dansk](docs/README.da.md) · [Svenska](docs/README.sv.md) · [Suomi](docs/README.fi.md) · [Português (BR)](docs/README.pt-BR.md) · [Português (PT)](docs/README.pt-PT.md) · [Română](docs/README.ro.md) · [中文 (简体)](docs/README.zh-CN.md)

---

## O que o OrgPortal adiciona ao FreeScout

O FreeScout foi construído em torno de clientes individuais — cada e-mail vem de uma pessoa, e não há conceito nativo de empresa à qual essa pessoa pertence. Isso funciona bem para helpdesks B2C. Para B2B, fica aquém.

OrgPortal preenche essa lacuna:

- **Contas empresariais** — agrupe clientes em organizações com nome, emblema colorido, escopo de caixa postal e status ativo/inativo
- **Hierarquias departamentais** — divida organizações em unidades estruturais (departamentos, filiais, equipes); cada membro é vinculado à sua unidade
- **Acesso baseado em função** — `member` vê apenas seus próprios tickets; `unit_manager` vê toda a unidade; `manager` vê toda a organização
- **Portal corporativo de autoatendimento** — gestores visualizam todos os tickets da empresa, respondem, fecham, reatribuem autores e gerenciam preferências de notificação sem contatar sua equipe
- **Atribuição permanente de tickets** — cada ticket é registrado com um snapshot da organização na criação; relatórios históricos sobrevivem a mudanças na carteira de clientes
- **Notificações multilíngues** — alertas por e-mail automatizados no idioma de cada gestor, com templates por localidade e um editor WYSIWYG integrado
- **REST API** — sincronize memberships do seu CRM, automatize onboarding, gerencie tags programaticamente

---

## Organizações

*Um lugar para tudo sobre uma conta corporativa.*

**Gerenciar → Organizações** abre uma interface com abas e três seções: Organizações, Templates e Sistema.

### Lista de organizações

- **Criar, editar, excluir, ativar/desativar** organizações
- **Filtro de status** — alterne entre Ativo / Inativo / Todos com um grupo de botões de rádio; filtra a tabela instantaneamente no lado do cliente
- **Busca ao vivo** — começa a filtrar a partir de 2+ caracteres, sem recarregar a página
- **Emblemas coloridos** — seletor de cores interativo com 12 amostras e uma prévia do emblema ao vivo; o emblema aparece em cada ticket e cartão do Kanban
- Clicar no emblema ou na contagem de tickets abre uma busca no FreeScout filtrada para aquela organização
- **Vínculo com caixa postal** — organizações podem ser globais (todas as caixas postais) ou vinculadas a uma caixa postal específica
- **Coluna de tags** — mostra ✓/✗ se alguma tag do FreeScout está vinculada à organização (módulo Tags obrigatório); as tags são atribuídas no formulário de edição com um widget de chips e busca com autocomplete
- **Coluna de contagem de tickets** — total de conversas por organização; link clicável para os resultados completos da busca
- Coluna de **contagem de membros**
- **Ativar / desativar** — suspenda uma conta sem perder histórico; exige que o Org Snapshot esteja habilitado (o botão fica desabilitado com tooltip quando não está)
- **Excluir** — disponível apenas quando a organização tem 0 membros e 0 tickets (proteção de segurança)
- Todas as ações de exclusão e desativação exigem confirmação

![Lista de organizações — filtro de status, busca ao vivo, emblemas coloridos, tags, contagem de tickets](docs/screenshots/org-list.png)

### Formulário de edição de organização

- **Nome** e **vínculo com caixa postal**
- **Seletor de cores** — 12 amostras com prévia ao vivo do emblema
- **Tags** — widget de chips: digite para buscar tags existentes do FreeScout, clique para adicionar, × para remover
- **Tabela de membros** — por membro: nome, função, unidade estrutural, checkbox `can_manage_org` (concede acesso administrativo às organizações sem direitos de administrador completos), alternância ativo/inativo
- **Painel de unidades estruturais** — crie e renomeie unidades diretamente no formulário de edição; membros são atribuídos às unidades na mesma tela
- **Adicionar um membro** — preenche automaticamente conversas existentes não atribuídas para esse cliente

![Edição de organização — seletor de cores, chips de tags, tabela de membros com funções e unidades](docs/screenshots/org-edit.png)

### Integração com perfil do cliente

- **Campo de organização no formulário de edição de cliente do FreeScout** — busca com autocomplete ao vivo para organizações; dropdown de função aparece após selecionar uma org; botão × para remover
- Link de atalho **"Ver tickets da org"** no formulário do cliente
- **Bloco de informações da org na barra lateral de tickets do administrador** — nome da organização (link clicável para a página de edição da org), unidade estrutural e função do membro; visibilidade configurável por caixa postal nas configurações
- **Uma membership ativa por cliente** — um cliente não pode ser adicionado a uma segunda organização enquanto tiver uma membership ativa; memberships inativas/arquivadas são permitidas

![Edição de cliente — campo de organização com autocomplete e seletor de função](docs/screenshots/customer-org-field.png)

---

## Unidades Estruturais — Controle de Acesso por Departamento

*Suporte a grandes empresas com hierarquias internas complexas.*

As organizações podem ser divididas em **unidades estruturais** ilimitadas (departamentos, filiais, escritórios regionais, equipes de projeto):

- Crie, renomeie e exclua unidades no formulário de edição da org no administrador, ou diretamente no portal (somente gestores globais)
- Atribua membros a unidades — cada membro pertence a uma unidade
- **Excluir uma unidade** rebaixa automaticamente seus membros `unit_manager` para `member`

**Três níveis de função:**

| Função | Escopo de acesso |
|--------|-----------------|
| `member` | Apenas seus próprios tickets |
| `unit_manager` | Todos os tickets dentro de sua unidade estrutural |
| `manager` (global) | Todos os tickets em toda a organização |

- Gestores de unidade têm todas as capacidades do portal — respostas, anexos, reatribuição de autor, fechar/reabrir, gerenciamento de notificações — limitadas estritamente à sua unidade
- O acesso a tickets e a entrega de notificações são aplicados nos limites das unidades

![Edição de organização — membros com funções e unidades, painel de gerenciamento de unidades](docs/screenshots/org-edit.png)

---

## Org Snapshot — Atribuição Permanente de Tickets

*Relatórios históricos confiáveis mesmo com mudanças na carteira de clientes.*

Quando um ticket é criado, o OrgPortal registra o contexto da organização como um snapshot permanente:

- `org_id`, `org_unit_id` e `org_attributed_at` são gravados na conversa no momento da criação
- **Imutável** — se um cliente sair de uma organização posteriormente, seus tickets históricos continuam atribuídos àquela org; os relatórios nunca são corrompidos
- **Adicionar um membro** aciona o preenchimento automático das conversas existentes não atribuídas desse cliente

### Fonte de atribuição — três modos

Configurado em **Gerenciar → Organizações → aba Sistema**:

| Modo | Comportamento |
|------|--------------|
| `member` | Atribui o ticket à organização da qual o autor do ticket é membro |
| `tag` | Atribui pela tag do FreeScout vinculada a uma org primeiro; recorre à membership se nenhuma tag corresponder |
| `tag_only` | Atribui exclusivamente por tag; a membership não é usada |

Os modos `tag` e `tag_only` são desabilitados quando o módulo Tags está inativo.

### Ferramentas de preenchimento retroativo

- **Barra de progresso** — mostra X / Y tickets atribuídos (%) com um indicador "concluído" quando terminar
- **Estatísticas preliminares** — antes de executar o preenchimento, um resumo mostra quantos tickets serão atribuídos por tag vs. por membership vs. sem correspondência
- Botão **Executar preenchimento** — processa até 2.000 tickets por clique; o resumo do resultado (by_tag / by_member / unmatched) é exibido após
- **Auto-cron** (`attribution_cron_enabled`) — agenda o preenchimento a cada 5 minutos, 1.000 tickets por execução, sem sobreposição
- **Redefinir atribuição** — limpa todos os snapshots da org (ação perigosa, requer confirmação)
- Linha de comando: `php artisan orgportal:backfill-attribution`

![Aba Sistema — fonte de atribuição, barra de progresso, estatísticas preliminares, controles de preenchimento](docs/screenshots/attribution-settings.png)

---

## Integração com Kanban

*Mantenha seu fluxo de trabalho visual alinhado com suas contas B2B.*

- Emblema da organização em cada cartão do Kanban com a cor atribuída à conta
- **Filtro de organização** no painel de filtros do Kanban — modal de seleção múltipla com checkboxes; o estado do filtro persiste durante a navegação
- **Rótulos multilíngues de filtro de status do Kanban** — dê a cada coluna do Kanban um nome personalizado por idioma do portal; mude de localidade com o seletor de idioma nas configurações por caixa postal; arraste para reordenar os filtros
- Os rótulos traduzidos aparecem tanto na barra de filtros do portal quanto na coluna **Estado** da tabela de tickets da empresa; cadeia de fallback: localidade salva → inglês salvo → nome original da coluna

![Kanban — emblemas de organização nos cartões e modal de filtro de org](docs/screenshots/kanban-org.png)

---

## Controle de Acesso e Permissões

*Delegue o gerenciamento de organizações sem conceder acesso de administrador.*

- **"Permitir gerenciar organizações"** (`can_manage_org`) — dois níveis:
  - Como uma **permissão de usuário** nas configurações do agente — permite que um líder de equipe de suporte gerencie todas as organizações sem direitos de administrador
  - Como um **flag por membro** no formulário de edição da organização — permite que um membro específico da org gerencie aquela organização pelo painel administrativo
- **"Permitir gerenciar templates de notificação"** — permissão granular separada para edição de templates
- A exclusão de organizações permanece exclusivamente para administradores
- O acesso ao portal é estritamente limitado por caixa postal: um gestor da Organização A não pode acessar a Organização B

![Permissões granulares — permitir gerenciar organizações e templates de notificação](docs/screenshots/user-permissions.png)

---

## Configurações do Sistema — Gerenciar → Organizações → aba Sistema

*Controles exclusivos para administradores: atribuição, preenchimento retroativo e alternador de idioma do portal.*

A aba **Sistema** é visível apenas para administradores do FreeScout.

### Painel 1: Atribuição de Tickets

Consulte [Org Snapshot](#org-snapshot--atribuição-permanente-de-tickets) acima para a descrição completa dos modos de atribuição, ferramentas de preenchimento e auto-cron.

### Painel 2: Alternador de Idioma do Portal

- **Habilitar/desabilitar** o alternador de idioma na barra de navegação do End-User Portal
- **Escolher quais das 19 localidades** oferecer (grade de checkboxes); todas habilitadas por padrão
- Quando habilitado, os gestores podem trocar o idioma do portal; a escolha é salva e usada para e-mails de notificação
- Este é o alternador de idioma integrado do OrgPortal — funciona independentemente de qualquer módulo de troca de idioma de terceiros; ambos podem coexistir

![Aba Sistema — painel do alternador de idioma do portal com checkboxes de localidade](docs/screenshots/system-settings.png)

---

## End-User Portal — Autoatendimento para Gestores Corporativos *(opcional)*

*Ofereça aos seus clientes B2B um portal onde gerenciam o relacionamento de suporte da empresa — sem precisar contatar sua equipe para cada atualização de status.*

Requer o módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

### Painel de Tickets da Empresa

Uma seção dedicada de **Tickets da Empresa** na navegação do portal com uma tabela de tickets completa:

| Coluna | Descrição |
|--------|-----------|
| **#** | ID do ticket |
| **Assunto** | Truncado com tooltip ao passar o mouse |
| **Responsável** | Agente de suporte atribuído |
| **Autor** | Cliente que abriu o ticket; clique para filtrar por este autor |
| **Status** | Ativo / Pendente / Fechado / Spam com ícones |
| **Estado** | Nome da coluna do Kanban no idioma atual do portal (somente quando o módulo Kanban está ativo) |
| **Atualizado** | Data e hora da última resposta |

**Dois indicadores de status de leitura independentes por linha** — rastreiam duas pessoas diferentes e são exibidos simultaneamente:

| Indicador | Status de leitura de quem | O que significa |
|-----------|--------------------------|----------------|
| **Linha em negrito** | O gestor visualizando o portal | O gestor tem notificações não lidas para esta conversa — algo aconteceu que ele ainda não viu |
| **Ícone 👁 Olho** | O autor do ticket (o cliente que o enviou) | O autor ainda não abriu a última resposta do agente — útil para saber se o cliente realmente viu a resposta |

Esses dois estados são completamente independentes: uma linha pode estar em negrito (gestor não leu) enquanto o olho está ausente (autor já leu), ou vice-versa. O gestor vê os dois ao mesmo tempo, tendo um quadro completo do que está acontecendo nos dois lados do ticket sem precisar abri-lo.

**Filtro por autor** — clicar no nome de um autor ativa um filtro; um banner aparece no topo da tabela com o nome do autor ativo e um link × para limpar o filtro.

Tanto a tabela desktop quanto um **layout de cards responsivo para mobile** estão incluídos; eles alternam automaticamente com base na largura da tela.

O template da barra de filtros suporta **substituição** via `enduserportal::partials.tickets_filters` — coloque uma view personalizada nesse caminho para substituir a barra de filtros padrão do OrgPortal mantendo todas as outras funcionalidades.

![Tickets da Empresa — tabela completa com indicadores de leitura, banner de filtro de autor, filtros de status](docs/screenshots/portal-tickets.png)

### Ações de Ticket no Portal

Os gestores podem agir diretamente — sem precisar contatar o suporte:

- **Responder com anexos** — arrastar e soltar, vários arquivos por resposta; nomes e tamanhos dos anexos exibidos em cada thread
- **Fechar ticket** — uma nova resposta o reabre automaticamente; um banner informa o gestor disso quando o ticket está fechado
- **Alterar autor do ticket** — reatribua um ticket a outro membro da organização
- **Filtrar por unidade** — gestores globais filtram a lista de tickets por unidade estrutural
- **Filtrar por status do Kanban** — configurável por caixa postal, rótulos exibidos no idioma atual do portal

![Visualização de ticket no portal — formulário de resposta com drag & drop de anexos e banner de ticket fechado](docs/screenshots/portal-reply.png)

### Rastreamento de Visualização pelo Gestor

- Uma nota **"visualizado"** aparece abaixo das respostas do agente na visualização de ticket do administrador quando um gestor abre o ticket no portal
- Mostra nome do gestor, função (Gestor da organização / Gestor de unidade) e tempo decorrido
- Visualizações do gestor global e do gestor de unidade rastreadas e exibidas independentemente — mesma UX do "Cliente visualizou" nativo do FreeScout

![Rastreamento de visualização pelo gestor — nota 'visualizado' aparece abaixo da resposta do agente na visualização de ticket do administrador](docs/screenshots/manager-viewed.png)

---

## Sino de Notificações em Tempo Real *(opcional)*

*Mantenha os gestores informados no momento em que algo acontece com os tickets da empresa.*

Requer o módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Ícone de sino com emblema de contagem de não lidos ao vivo na barra de navegação do EUP — reposicionado automaticamente no mobile (ao lado do menu hambúrguer)
- Notificações para: **novo ticket**, **resposta do agente**, **resposta do cliente** — para todas as funções de gestores
- Painel dropdown com notificações agrupadas por data: nome do ator, tipo de evento, número do ticket, prévia da mensagem, timestamp
- **Marcar como lido automaticamente** quando o gestor abre o ticket
- Marcar notificações individuais como lidas via ×; **Marcar todas como lidas** no cabeçalho do painel
- Consulta a cada 15 segundos; atualiza na navegação de voltar/avançar do browser (compatível com bfcache)

![Sino de notificações em tempo real — dropdown com notificações não lidas agrupadas](docs/screenshots/portal-bell.png)

---

## Assinaturas de Notificação *(opcional)*

*Deixe os gestores decidirem sobre o que querem ser notificados — nada mais, nada menos.*

- **Matriz de assinaturas visual** na aba "Notificações" nas Configurações da Organização do portal
- **Três tipos de evento:** Novo ticket · Resposta do agente · Resposta do cliente
- **Dois níveis de escopo:** Organização inteira (gestores globais) · Unidades estruturais individuais
- Membros sem unidade são agrupados em uma linha expansível separada **"Sem unidade"**
- **Substituições por membro** — expanda qualquer linha de unidade para revelar membros individuais e alternar suas assinaturas inline; gestores de unidade com função limitada são rotulados adequadamente
- **Lógica em cascata em ambas as direções:**
  - Habilitar "Organização inteira" → habilita todas as unidades e todos os membros
  - Habilitar uma unidade → habilita todos os seus membros
  - Desabilitar um membro → reconcilia automaticamente os checkboxes da unidade e da organização
- Gestores globais gerenciam todos os membros; gestores de unidade gerenciam apenas sua própria unidade
- As notificações usam o driver de e-mail da caixa postal correspondente

![Matriz de assinaturas de notificação — alternâncias por unidade e por membro](docs/screenshots/portal-subscriptions.png)

---

## Configurações da Organização no Portal

*Os gestores configuram a estrutura da organização sem acesso de administrador.*

**Configurações da Organização** na navegação do portal possui três abas:

### Aba Notificações

A matriz de assinaturas descrita acima.

### Aba Unidades *(somente gestores globais)*

- **Criar unidade** — formulário inline com campo de nome
- **Renomear unidade** — edição inline diretamente na linha da tabela
- **Excluir unidade** — botão com confirmação; gestores de unidade são automaticamente rebaixados para membro
- Contagem de membros exibida por unidade

### Aba Membros

- Tabela de todos os membros da organização: nome, unidade estrutural, função, emblema de status ativo/inativo
- Rótulo **"Gestor global"** exibido ao lado do nome do membro quando aplicável
- Checkbox **Mostrar desativados** — aparece somente quando existem membros inativos; oculto por padrão
- **Gestores globais** podem atualizar a unidade e a função de qualquer membro com um formulário inline (seleção de unidade + seleção de função + Aplicar)
- **Gestores globais não podem promover um membro a gestor global** no portal — isso requer acesso de administrador
- Botão **Ativar / desativar** por membro com confirmação para desativação

![Configurações da Organização no Portal — abas Unidades e Membros](docs/screenshots/portal-settings.png)

---

## Templates de E-mail de Notificação Multilíngues *(opcional)*

*Seus clientes corporativos recebem e-mails de suporte no próprio idioma — automaticamente, sem esforço manual.*

Configurado em **Gerenciar → Organizações → aba Templates** (visível para usuários com a permissão "gerenciar templates").

- **Templates por localidade** — assunto e corpo separados para cada idioma do portal; alterne entre eles com o dropdown de localidade; os valores são trocados na memória sem recarregar a página
- **Painéis recolhíveis** por tipo de evento (Novo ticket / Resposta do agente / Resposta do cliente) — o editor Summernote inicializa preguiçosamente quando um painel é aberto
- Botão **Carregar Padrão** em cada painel — restaura o template integrado para a localidade atualmente selecionada (recorre ao padrão em inglês integrado se não existir padrão específico para a localidade)
- **Editor WYSIWYG Summernote** para composição de e-mail HTML rico
- **Seletor de variáveis de macro** — insira marcadores de posição no assunto ou corpo com um clique; a posição do cursor é preservada no campo de assunto
- **19 templates padrão integrados** — prontos para uso imediato; nenhuma configuração necessária

**Variáveis de macro disponíveis:**

| Variável | Descrição |
|----------|-----------|
| `{manager_name}` | Nome do gestor que recebe a notificação |
| `{author_name}` | Cliente que criou ou respondeu ao ticket |
| `{org_name}` | Nome da organização |
| `{unit_name}` | Nome da unidade estrutural |
| `{subject}` | Assunto do ticket |
| `{ticket_number}` | ID do ticket |
| `{ticket_url}` | Link direto para o ticket no portal |
| `{ticket_text}` | Texto completo da mensagem inicial (HTML) |
| `{reply_text}` | Texto completo da última resposta (HTML) |
| `{created_date}` | Data de criação do ticket |
| `{created_time}` | Hora de criação do ticket |
| `{created_datetime}` | Data e hora de criação do ticket |
| `{reply_date}` | Data da resposta |
| `{reply_time}` | Hora da resposta |
| `{reply_datetime}` | Data e hora da resposta |

**Cadeia de fallback:** template de localidade salvo → template de localidade integrado → template em inglês salvo → template em inglês integrado

O idioma da notificação é determinado pela seleção de idioma do portal de cada gestor, salva automaticamente quando ele usa o alternador de idioma.

![Templates de e-mail — painéis recolhíveis por localidade, botão Carregar Padrão, editor Summernote](docs/screenshots/admin-templates.png)

---

## REST API *(opcional)*

*Integre o OrgPortal ao seu CRM, ERP ou fluxo de trabalho de onboarding de clientes.*

Requer o módulo [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD completo para organizações, unidades estruturais, memberships de clientes e tags
- **Campos de organização:** `name`, `color`, `mailboxId`, `isActive` — todos legíveis e atualizáveis via API
- **Sub-recurso de membros** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — atualize função, unidade, `canManageOrg` e flag `isActive` por membro independentemente sem alterar o restante da membership
- **Sub-recurso de tags** — `GET/PUT /api/organizations/{id}/tags` — liste ou substitua completamente os vínculos de tags (requer módulo Tags; retorna `503` se inativo)
- Autenticação via cabeçalho `X-FreeScout-API-Key` ou parâmetro de consulta `api_key`
- **Documentação ReDoc interativa** em **Gerenciar → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Referência completa da API → [docs/api/README.md](docs/api/README.md)**

![Documentação interativa da API — ReDoc com todos os endpoints do OrgPortal](docs/screenshots/api-docs.png)

---

## Instalação

1. Copie a pasta `OrgPortal` para `Modules/` da sua instalação do FreeScout
2. Vá para **Gerenciar → Módulos → OrgPortal → Ativar**
3. Execute as migrations:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Limpe o cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **O suporte ao idioma georgiano** é implantado automaticamente na primeira inicialização — não é necessário copiar arquivos manualmente.

---

## Atualizações Automáticas

O OrgPortal suporta **atualizações com um clique** através do mecanismo de atualização de módulos integrado do FreeScout.

> **Requer FreeScout 1.8.170 ou superior.** Em versões mais antigas, atualize manualmente substituindo a pasta `OrgPortal` pelo ZIP da versão mais recente.

Quando uma nova versão estiver disponível, um banner aparece em **Gerenciar → Módulos**. Clique em **Atualizar agora** — o FreeScout baixa e instala a versão mais recente automaticamente.

---

## Compatibilidade de Módulos

| Módulo | Status | Observações |
|--------|--------|-------------|
| End-User Portal ≥ 1.0.85 | Opcional | Portal do gestor, sino de notificações, assinaturas |
| API and Webhooks ≥ 1.0.80 | Opcional | Endpoints REST API |
| Kanban ≥ 1.0.23 | Opcional | Emblema nos cartões, filtro de org, rótulos multilíngues da coluna Estado |
| Custom Fields | ✅ Compatível | — |
| Workflows | ✅ Compatível | — |
| Tags | ✅ Compatível | Chips de tags no formulário de edição da org; vínculos de tags via API (`/organizations/{id}/tags`); atribuição de tickets por tag |

---

## Configuração

### Configurações Globais — **Gerenciar → Organizações → aba Sistema**

| Opção | Descrição |
|-------|-----------|
| Exibir emblema na página do ticket | Emblema da org na lista de conversas e na visualização do ticket |
| Exibir emblema nos cartões do Kanban | Emblema da org nos cartões do quadro Kanban |
| Fonte de atribuição | `member` / `tag` / `tag_only` — como os tickets são atribuídos às organizações |
| Auto-cron de preenchimento retroativo | Executar o preenchimento a cada 5 minutos automaticamente |
| Visibilidade do snapshot | Mostrar/ocultar dados de atribuição na barra lateral do ticket |
| Alternador de Idioma do Portal | Habilitar alternador de idioma na barra de navegação do EUP; escolher quais das 19 localidades oferecer |

### Configurações por Caixa Postal — **Configurações da Caixa Postal → OrgPortal**

Substitui os valores globais para a caixa postal específica.

| Opção | Descrição |
|-------|-----------|
| Exibir emblema na página do ticket | Habilitar/desabilitar emblema para esta caixa postal |
| Exibir emblema nos cartões do Kanban | Habilitar/desabilitar emblema para esta caixa postal |
| Exibir bloco de organização no perfil do cliente | Alternar bloco de informações da org na barra lateral do ticket |
| Filtros de status dos tickets da empresa | Mapeie colunas do Kanban para filtros nomeados no portal; rótulos por idioma com alternador de localidade; arraste para reordenar |

![Configurações por caixa postal — visibilidade do emblema e filtros de status do Kanban com rótulos multilíngues](docs/screenshots/mailbox-settings.png)

---

## Traduções

O OrgPortal está totalmente localizado em **19 idiomas**:

| Idioma | Código | Idioma | Código |
|--------|--------|--------|--------|
| Inglês | `en` | Holandês | `nl` |
| Ucraniano | `uk` | Norueguês | `no` |
| Alemão | `de` | Dinamarquês | `da` |
| Francês | `fr` | Sueco | `sv` |
| Espanhol | `es` | Finlandês | `fi` |
| Italiano | `it` | Português (BR) | `pt-BR` |
| Tcheco | `cs` | Português (PT) | `pt-PT` |
| Eslovaco | `sk` | Romeno | `ro` |
| Polonês | `pl` | Chinês Simplificado | `zh-CN` |
| Georgiano | `ka` | | |

Arquivos de tradução: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Os templates de e-mail de notificação têm padrões integrados para todos os 19 idiomas.

### Integração com o Alternador de Idioma

O OrgPortal inclui um alternador de idioma integrado para o portal (habilite em **aba Sistema → Alternador de Idioma do Portal**). Também se integra com o [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — ambos podem estar ativos simultaneamente.

O idioma que um gestor seleciona se aplica a todas as strings de interface do OrgPortal e é salvo como seu idioma de notificação — os e-mails são enviados automaticamente no idioma escolhido.

> **Nota técnica:** O middleware `OrgPortalSetLocale` reaplica a localidade do portal após o middleware `Localize` do FreeScout para evitar que seja redefinida para o padrão do sistema a cada requisição.

---

## Capturas de Tela

| | |
|---|---|
| ![Lista de organizações](docs/screenshots/org-list.png) | ![Edição de organização](docs/screenshots/org-edit.png) |
| *Lista de organizações — filtro de status, busca ao vivo, emblemas coloridos* | *Edição de organização — seletor de cores, chips de tags, tabela de membros* |
| ![Aba Sistema](docs/screenshots/system-settings.png) | ![Edição de cliente](docs/screenshots/customer-org-field.png) |
| *Aba Sistema — modos de atribuição, preenchimento, alternador de idioma* | *Edição de cliente — campo de org com autocomplete* |
| ![Portal de Tickets da Empresa](docs/screenshots/portal-tickets.png) | ![Resposta no portal](docs/screenshots/portal-reply.png) |
| *Tickets da Empresa — tabela, filtro de autor, indicadores de leitura* | *Ticket no portal — resposta com anexos, banner de ticket fechado* |
| ![Configurações da Organização no Portal](docs/screenshots/portal-settings.png) | ![Sino de notificações](docs/screenshots/portal-bell.png) |
| *Config. da Org. no Portal — abas Unidades e Membros* | *Sino de notificações em tempo real com dropdown* |
| ![Matriz de assinaturas](docs/screenshots/portal-subscriptions.png) | ![Templates de e-mail](docs/screenshots/admin-templates.png) |
| *Matriz de assinaturas de notificação — por unidade, por membro* | *Templates de e-mail — alternador de localidade, Carregar Padrão, Summernote* |
| ![Integração com Kanban](docs/screenshots/kanban-org.png) | ![Configurações por caixa postal](docs/screenshots/mailbox-settings.png) |
| *Kanban — emblemas de org e modal de filtro de org* | *Config. por caixa postal — filtros do Kanban com rótulos multilíngues* |
| ![Documentação da API](docs/screenshots/api-docs.png) | |
| *Documentação interativa da API — ReDoc* | |

---

## Licença

[MIT](LICENSE) — © 2026 ASTIN-UA
