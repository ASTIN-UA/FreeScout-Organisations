# OrgPortal — Módulo de Gestão de Organizações B2B para FreeScout

[← Voltar ao README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — módulo B2B para FreeScout" width="140" align="right">

**OrgPortal** é um módulo para FreeScout que adiciona uma completa **gestão de organizações B2B** ao seu helpdesk: agrupe clientes em empresas, defina hierarquias departamentais, dê aos gestores corporativos um portal de autoatendimento e automatize notificações — tudo dentro do FreeScout, sem ferramentas externas.

> Procura uma forma de gerir contas empresariais no FreeScout? De dar aos clientes corporativos o seu próprio portal de suporte? De controlar quais os pedidos que cada contacto B2B pode ver com base no seu papel e departamento? O OrgPortal resolve tudo isso.

**Compatível com:** FreeScout 1.8.147+  
**Integrações opcionais:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Também disponível em:**
[Українська](docs/README.uk.md) · [Deutsch](docs/README.de.md) · [Français](docs/README.fr.md) · [Español](docs/README.es.md) · [Italiano](docs/README.it.md) · [Polski](docs/README.pl.md) · [Čeština](docs/README.cs.md) · [Slovenčina](docs/README.sk.md) · [Nederlands](docs/README.nl.md) · [Norsk](docs/README.no.md) · [Dansk](docs/README.da.md) · [Svenska](docs/README.sv.md) · [Suomi](docs/README.fi.md) · [Português (BR)](docs/README.pt-BR.md) · [Português (PT)](docs/README.pt-PT.md) · [Română](docs/README.ro.md) · [中文 (简体)](docs/README.zh-CN.md)

---

## O que o OrgPortal acrescenta ao FreeScout

O FreeScout foi concebido em torno de clientes individuais — cada e-mail é de uma pessoa, e não existe nenhum conceito nativo de empresa à qual essa pessoa pertence. Isso funciona bem para helpdesks B2C. Para B2B, fica aquém.

O OrgPortal preenche essa lacuna:

- **Contas empresariais** — agrupe clientes em organizações com nome, emblema colorido, âmbito de caixa de correio e estado ativo/inativo
- **Hierarquias departamentais** — divida organizações em unidades estruturais (departamentos, filiais, equipas); cada membro está limitado à sua unidade
- **Controlo de acesso por função** — `member` vê apenas os seus próprios pedidos; `unit_manager` vê toda a unidade; `manager` vê toda a organização
- **Portal de autoatendimento corporativo** — os gestores visualizam todos os pedidos da empresa, respondem, fecham, reatribuem autores e gerem preferências de notificação sem contactar a sua equipa
- **Atribuição permanente de pedidos** — cada pedido é registado na sua organização no momento da criação; os relatórios históricos sobrevivem a alterações na lista de clientes
- **Notificações multilingues** — alertas de e-mail automatizados no idioma de cada gestor, com modelos por idioma e um editor WYSIWYG integrado
- **REST API** — sincronize membros a partir do seu CRM, automatize o onboarding, gira etiquetas programaticamente

---

## Organizações

*Um local para tudo o que diz respeito a uma conta empresarial.*

**Gerir → Organizações** abre uma interface com separadores com três secções: Organizações, Modelos e Sistema.

### Lista de organizações

- **Criar, editar, eliminar, ativar/desativar** organizações
- **Filtro de estado** — alterne entre Ativa / Inativa / Todas com um grupo de botões de opção; filtra a tabela do lado do cliente instantaneamente
- **Pesquisa em tempo real** — começa a filtrar a partir de 2+ caracteres, sem recarregamento da página
- **Emblemas com código de cores** — seletor de cores interativo com 12 amostras e uma pré-visualização do emblema ao lado; o emblema aparece em todos os pedidos e cartões Kanban
- Clicar no emblema ou na contagem de pedidos abre uma pesquisa FreeScout filtrada para essa organização
- **Associação a caixa de correio** — as organizações podem ser globais (todas as caixas de correio) ou limitadas a uma caixa de correio específica
- **Coluna de etiquetas** — mostra ✓/✗ se alguma etiqueta FreeScout está associada à organização (requer o módulo Tags); as etiquetas são atribuídas no formulário de edição com um widget de chips e pesquisa com sugestões automáticas
- **Coluna de contagem de pedidos** — total de conversas por organização; ligação clicável para os resultados completos da pesquisa
- Coluna de **contagem de membros**
- **Ativar / desativar** — suspenda uma conta sem perder o histórico; requer que o Org Snapshot esteja ativado (o botão está desativado com uma dica quando não está)
- **Eliminar** — disponível apenas quando a organização tem 0 membros e 0 pedidos (proteção de segurança)
- Todas as ações de eliminação e desativação requerem confirmação

![Lista de organizações — filtro de estado, pesquisa em tempo real, emblemas coloridos, etiquetas, contagens de pedidos](docs/screenshots/org-list.png)

### Formulário de edição de organização

- **Nome** e **associação a caixa de correio**
- **Seletor de cores** — 12 amostras com pré-visualização do emblema em tempo real
- **Etiquetas** — widget de chips: escreva para pesquisar etiquetas FreeScout existentes, clique para adicionar, × para remover
- **Tabela de membros** — por membro: nome, função, unidade estrutural, caixa de verificação `can_manage_org` (concede acesso administrativo às organizações sem direitos de administrador completos), botão de alternância ativo/inativo
- **Painel de unidades estruturais** — crie e renomeie unidades diretamente no formulário de edição; os membros são atribuídos às unidades na mesma vista
- **Adicionar um membro** — preenche automaticamente as conversas existentes sem atribuição para esse cliente

![Edição de organização — seletor de cores, chips de etiquetas, tabela de membros com funções e unidades](docs/screenshots/org-edit.png)

### Integração com o perfil do cliente

- **Campo de organização no formulário de edição de cliente do FreeScout** — pesquisa com sugestões automáticas de organizações em tempo real; o menu suspenso de função aparece após selecionar uma organização; botão × para remover
- Ligação de atalho **"Ver pedidos da organização"** no formulário de cliente
- **Bloco de informações da organização na barra lateral do pedido de administrador** — nome da organização (ligação clicável para a página de edição da organização), unidade estrutural e função do membro; alterne a visibilidade por caixa de correio nas definições
- **Uma adesão ativa por cliente** — um cliente não pode ser adicionado a uma segunda organização enquanto tiver uma adesão ativa; são permitidas adesões inativas/arquivadas

![Edição de cliente — campo de organização com sugestões automáticas e seletor de função](docs/screenshots/customer-org-field.png)

---

## Unidades Estruturais — Controlo de Acesso ao Nível Departamental

*Suporte a grandes empresas com hierarquias internas complexas.*

As organizações podem ser divididas em **unidades estruturais** ilimitadas (departamentos, filiais, escritórios regionais, equipas de projeto):

- Crie, renomeie e elimine unidades no formulário de edição de organização do administrador, ou diretamente a partir do portal (apenas gestores globais)
- Atribua membros a unidades — cada membro pertence a uma unidade
- **Eliminar uma unidade** rebaixa automaticamente os seus membros `unit_manager` para `member`

**Três níveis de função:**

| Função | Âmbito de acesso |
|--------|-----------------|
| `member` | Apenas os seus próprios pedidos |
| `unit_manager` | Todos os pedidos dentro da sua unidade estrutural |
| `manager` (global) | Todos os pedidos em toda a organização |

- Os gestores de unidade têm capacidades completas no portal — respostas, anexos, reatribuição de autor, fechar/reabrir, gestão de notificações — limitadas estritamente à sua unidade
- O acesso a pedidos e a entrega de notificações são aplicados nos limites da unidade

![Edição de organização — membros com funções e unidades, painel de gestão de unidades](docs/screenshots/org-edit.png)

---

## Org Snapshot — Atribuição Permanente de Pedidos

*Relatórios históricos fiáveis mesmo quando a sua lista de clientes muda.*

Quando um pedido é criado, o OrgPortal regista o contexto da organização como um snapshot permanente:

- `org_id`, `org_unit_id` e `org_attributed_at` são escritos na conversa no momento da criação
- **Imutável** — se um cliente sair posteriormente de uma organização, os seus pedidos históricos permanecem atribuídos a essa organização; os relatórios nunca ficam comprometidos
- **Adicionar um membro** desencadeia o preenchimento automático das conversas existentes sem atribuição desse cliente

### Fonte de atribuição — três modos

Configurado em **Gerir → Organizações → separador Sistema**:

| Modo | Comportamento |
|------|--------------|
| `member` | Atribui o pedido à organização da qual o autor do pedido é membro |
| `tag` | Atribui primeiro por etiqueta FreeScout associada a uma organização; recorre à adesão se nenhuma etiqueta corresponder |
| `tag_only` | Atribui exclusivamente por etiqueta; a adesão não é utilizada |

Os modos `tag` e `tag_only` são desativados quando o módulo Tags está inativo.

### Ferramentas de preenchimento retroativo

- **Barra de progresso** — mostra X / Y pedidos atribuídos (%) com um indicador "concluído" quando terminado
- **Estatísticas preliminares** — antes de executar o preenchimento, é apresentado um resumo de quantos pedidos serão atribuídos por etiqueta vs. por adesão vs. sem correspondência
- Botão **Executar preenchimento** — processa até 2000 pedidos por clique; o resumo do resultado (by_tag / by_member / unmatched) é mostrado depois
- **Auto-cron** (`attribution_cron_enabled`) — agenda o preenchimento de 5 em 5 minutos, 1000 pedidos por execução, sem sobreposição
- **Repor atribuição** — limpa todos os snapshots de organização (ação perigosa, requer confirmação)
- Linha de comando: `php artisan orgportal:backfill-attribution`

![Separador Sistema — fonte de atribuição, barra de progresso, estatísticas preliminares, controlos de preenchimento](docs/screenshots/attribution-settings.png)

---

## Integração com o Kanban

*Mantenha o seu fluxo de trabalho visual alinhado com as suas contas B2B.*

- Emblema da organização em cada cartão Kanban com a cor atribuída à conta
- **Filtro de organização** no painel de filtros do Kanban — modal de seleção múltipla com caixas de verificação; o estado do filtro persiste entre navegações
- **Etiquetas de filtro de estado do Kanban multilingues** — dê a cada coluna do Kanban um nome personalizado por idioma do portal; mude de idioma com o seletor de idioma nas definições por caixa de correio; arraste para reordenar os filtros
- As etiquetas traduzidas aparecem tanto na barra de filtros do portal como na coluna **Estado** da tabela de pedidos da empresa; cadeia de alternância: idioma guardado → inglês guardado → nome original da coluna

![Kanban — emblemas de organização nos cartões e modal de filtro de organização](docs/screenshots/kanban-org.png)

---

## Controlo de Acesso e Permissões

*Delegue a gestão de organizações sem conceder acesso de administrador.*

- **"Permitir gerir organizações"** (`can_manage_org`) — dois níveis:
  - Como **permissão de utilizador** nas definições do agente — permite que um líder de equipa de suporte gira todas as organizações sem direitos de administrador
  - Como **sinalizador por membro** no formulário de edição de organização — permite que um membro específico de uma organização gira essa organização a partir do painel de administração
- **"Permitir gerir modelos de notificação"** — permissão granular separada para edição de modelos
- A eliminação de organizações continua a ser exclusivamente reservada ao administrador
- O acesso ao portal é estritamente limitado por caixa de correio: um gestor da Organização A não pode aceder à Organização B

![Permissões granulares — permitir gerir organizações e modelos de notificação](docs/screenshots/user-permissions.png)

---

## Definições do Sistema — Gerir → Organizações → separador Sistema

*Controlos exclusivos do administrador para atribuição, preenchimento e o seletor de idioma do portal.*

O separador **Sistema** é visível apenas para os administradores do FreeScout.

### Painel 1: Atribuição de Pedidos

Veja [Org Snapshot](#org-snapshot--atribuição-permanente-de-pedidos) acima para a descrição completa dos modos de atribuição, ferramentas de preenchimento e auto-cron.

### Painel 2: Seletor de Idioma do Portal

- **Ativar/desativar** o seletor de idioma na barra de navegação do End-User Portal
- **Escolha quais dos 19 idiomas** a oferecer (grelha de caixas de verificação); todos estão ativados por predefinição
- Quando ativado, os gestores podem mudar o idioma do portal; a sua escolha é guardada e utilizada para os e-mails de notificação
- Este é o seletor de idioma integrado do OrgPortal — funciona independentemente de qualquer módulo de troca de idioma de terceiros; ambos podem coexistir

![Separador Sistema — painel do seletor de idioma do portal com caixas de verificação de idioma](docs/screenshots/system-settings.png)

---

## End-User Portal — Autoatendimento para Gestores Corporativos *(opcional)*

*Dê aos seus clientes B2B um portal onde gerem a relação de suporte da sua empresa — sem contactar a sua equipa para cada atualização de estado.*

Requer o módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

### Painel de Pedidos da Empresa

Uma secção dedicada a **Pedidos da Empresa** na navegação do portal com uma tabela de pedidos completa:

| Coluna | Descrição |
|--------|-----------|
| **#** | ID do pedido |
| **Assunto** | Truncado com dica ao passar o rato |
| **Responsável** | Agente de suporte atribuído |
| **Autor** | Cliente que abriu o pedido; clique para filtrar por este autor |
| **Estado** | Ativo / Pendente / Fechado / Spam com ícones |
| **Situação** | Nome da coluna do Kanban no idioma atual do portal (apenas quando o módulo Kanban está ativo) |
| **Atualizado** | Data e hora da última resposta |

**Dois indicadores de estado de leitura independentes por linha** — estes acompanham duas pessoas diferentes e são mostrados simultaneamente:

| Indicador | Estado de leitura de quem | O que significa |
|-----------|--------------------------|----------------|
| **Linha a negrito** | O gestor que visualiza o portal | O gestor tem notificações não lidas para esta conversa — aconteceu algo que ainda não viu |
| **Ícone 👁 Olho** | O autor do pedido (o cliente que o submeteu) | O autor ainda não abriu a resposta mais recente do agente — útil para saber se o cliente viu realmente a resposta |

Estes dois estados são completamente independentes: uma linha pode estar a negrito (gestor não leu) enquanto o olho está ausente (autor já leu), ou vice-versa. O gestor vê ambos ao mesmo tempo, dando uma imagem completa do que está a acontecer em ambos os lados do pedido sem o abrir.

**Filtro por autor** — clicar no nome de um autor ativa um filtro; aparece um banner no topo da tabela com o nome do autor ativo e uma ligação × para limpar o filtro.

A tabela de ambiente de trabalho e um **esquema de cartão móvel** responsivo estão incluídos; alternam automaticamente com base na largura do ecrã.

O modelo da barra de filtros suporta **substituição** via `enduserportal::partials.tickets_filters` — coloque uma vista personalizada nesse caminho para substituir a barra de filtros predefinida do OrgPortal mantendo toda a restante funcionalidade.

![Pedidos da Empresa — tabela completa com indicadores de leitura, banner de filtro por autor, filtros de estado](docs/screenshots/portal-tickets.png)

### Ações em Pedidos no Portal

Os gestores podem agir diretamente — sem necessidade de contactar o suporte:

- **Responder com anexos** — arrastar e largar, múltiplos ficheiros por resposta; nomes dos anexos e tamanhos de ficheiro mostrados em cada thread
- **Fechar pedido** — uma nova resposta reabre-o automaticamente; um banner informa o gestor disso quando o pedido está fechado
- **Alterar o autor do pedido** — reatribua um pedido a outro membro da organização
- **Filtrar por unidade** — os gestores globais filtram a lista de pedidos por unidade estrutural
- **Filtrar por estado do Kanban** — configurável por caixa de correio, etiquetas mostradas no idioma atual do portal

![Vista de pedido no portal — formulário de resposta com anexos por arrastar e largar e banner de pedido fechado](docs/screenshots/portal-reply.png)

### Acompanhamento de Visualização pelo Gestor

- Uma nota de **"visualizado"** aparece sob as respostas do agente na vista de pedido de administrador quando um gestor abre o pedido no portal
- Mostra o nome do gestor, função (Gestor de organização / Gestor de unidade) e tempo decorrido
- As visualizações do gestor global e do gestor de unidade são acompanhadas e apresentadas de forma independente — mesmo UX que o "Cliente visualizou" nativo do FreeScout

![Acompanhamento de visualização pelo gestor — nota 'visualizado' aparece sob a resposta do agente na vista de pedido de administrador](docs/screenshots/manager-viewed.png)

---

## Sino de Notificação em Tempo Real *(opcional)*

*Mantenha os gestores informados no momento em que algo acontece com os pedidos da sua empresa.*

Requer o módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Ícone de sino com emblema de contagem de não lidos em tempo real na barra de navegação do EUP — reposiciona-se automaticamente em dispositivos móveis (junto ao menu hamburger)
- Notificações para: **novo pedido**, **resposta do agente**, **resposta do cliente** — para todas as funções de gestor
- Painel suspenso com notificações agrupadas por data: nome do ator, tipo de evento, número do pedido, pré-visualização da mensagem, carimbo de data/hora
- **Marcar como lido automaticamente** quando o gestor abre o pedido
- Marque notificações individuais como lidas via ×; **Marcar todas como lidas** no cabeçalho do painel
- Atualiza de 15 em 15 segundos; atualiza na navegação para trás/frente no browser (com suporte a bfcache)

![Sino de notificação em tempo real — painel suspenso com notificações não lidas agrupadas](docs/screenshots/portal-bell.png)

---

## Subscrições de Notificação *(opcional)*

*Deixe os gestores decidir sobre o que querem ser notificados — nada mais, nada menos.*

- **Matriz de subscrição visual** no separador "Notificações" nas Definições de Organização do portal
- **Três tipos de evento:** Novo pedido · Resposta do agente · Resposta do cliente
- **Dois níveis de âmbito:** Toda a organização (gestores globais) · Unidades estruturais individuais
- Os membros sem unidade são agrupados numa linha expansível separada **"Sem unidade"**
- **Substituições por membro** — expanda qualquer linha de unidade para revelar membros individuais e alternar as suas subscrições inline; os gestores de unidade com função limitada são indicados em conformidade
- **Lógica em cascata em ambas as direções:**
  - Ativar "Toda a organização" → ativa todas as unidades e todos os membros
  - Ativar uma unidade → ativa todos os seus membros
  - Desativar um membro → reconcilia automaticamente as caixas de verificação da unidade e da organização
- Os gestores globais gerem todos os membros; os gestores de unidade gerem apenas a sua própria unidade
- As notificações utilizam o controlador de correio da caixa de correio correspondente

![Matriz de subscrição de notificação — alternâncias por unidade e por membro](docs/screenshots/portal-subscriptions.png)

---

## Definições de Organização do Portal

*Os gestores configuram a estrutura da sua organização sem acesso de administrador.*

**Definições de Organização** na navegação do portal tem três separadores:

### Separador Notificações

A matriz de subscrição descrita acima.

### Separador Unidades *(apenas gestores globais)*

- **Criar unidade** — formulário inline com campo de nome
- **Renomear unidade** — edição inline diretamente na linha da tabela
- **Eliminar unidade** — botão com confirmação; os gestores de unidade são automaticamente rebaixados para membro
- Contagem de membros mostrada por unidade

### Separador Membros

- Tabela de todos os membros da organização: nome, unidade estrutural, função, emblema de estado ativo/inativo
- Etiqueta **"Gestor global"** mostrada junto ao nome do membro quando aplicável
- Caixa de verificação **Mostrar desativados** — aparece apenas quando existem membros inativos; oculta por predefinição
- **Os gestores globais** podem atualizar a unidade e a função de qualquer membro com um formulário inline (selecionar unidade + selecionar função + Aplicar)
- **Os gestores globais não podem promover um membro a gestor global** a partir do portal — isso requer acesso de administrador
- Botão **Ativar / desativar** por membro com confirmação para desativação

![Definições de Organização do Portal — separadores Unidades e Membros](docs/screenshots/portal-settings.png)

---

## Modelos de E-mail de Notificação Multilingues *(opcional)*

*Os seus clientes corporativos recebem e-mails de suporte no seu próprio idioma — automaticamente, sem qualquer esforço manual.*

Configurado em **Gerir → Organizações → separador Modelos** (visível para utilizadores com a permissão "gerir modelos").

- **Modelos por idioma** — assunto e corpo separados para cada idioma do portal; alterne entre eles com o menu suspenso de idioma; os valores são trocados em memória sem recarregar a página
- **Painéis expansíveis** por tipo de evento (Novo pedido / Resposta do agente / Resposta do cliente) — o editor Summernote inicializa de forma preguiçosa quando um painel é aberto
- Botão **Carregar Predefinição** em cada painel — restaura o modelo integrado para o idioma atualmente selecionado (recorre ao modelo predefinido em inglês se não existir um predefinido específico para o idioma)
- **Editor WYSIWYG Summernote** para composição de e-mail HTML rico
- **Seletor de variáveis macro** — insira marcadores de posição no assunto ou corpo com um clique; a posição do cursor é preservada no campo de assunto
- **19 modelos predefinidos integrados** — prontos a usar sem necessidade de configuração

**Variáveis macro disponíveis:**

| Variável | Descrição |
|----------|-----------|
| `{manager_name}` | Nome do gestor que recebe a notificação |
| `{author_name}` | Cliente que criou ou respondeu ao pedido |
| `{org_name}` | Nome da organização |
| `{unit_name}` | Nome da unidade estrutural |
| `{subject}` | Assunto do pedido |
| `{ticket_number}` | ID do pedido |
| `{ticket_url}` | Ligação direta para o pedido no portal |
| `{ticket_text}` | Texto completo da mensagem inicial (HTML) |
| `{reply_text}` | Texto completo da resposta mais recente (HTML) |
| `{created_date}` | Data de criação do pedido |
| `{created_time}` | Hora de criação do pedido |
| `{created_datetime}` | Data e hora de criação do pedido |
| `{reply_date}` | Data da resposta |
| `{reply_time}` | Hora da resposta |
| `{reply_datetime}` | Data e hora da resposta |

**Cadeia de alternância:** modelo de idioma guardado → modelo de idioma integrado → modelo inglês guardado → modelo inglês integrado

O idioma das notificações é determinado pela seleção do idioma do portal de cada gestor, guardada automaticamente quando utilizam o seletor de idioma.

![Modelos de e-mail — painéis expansíveis por idioma, botão Carregar Predefinição, editor Summernote](docs/screenshots/admin-templates.png)

---

## REST API *(opcional)*

*Integre o OrgPortal no seu CRM, ERP ou fluxo de trabalho de integração de clientes.*

Requer o módulo [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD completo para organizações, unidades estruturais, adesões de clientes e etiquetas
- **Campos de organização:** `name`, `color`, `mailboxId`, `isActive` — todos legíveis e atualizáveis via API
- **Sub-recurso de membros** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — atualize função, unidade, `canManageOrg` e o sinalizador `isActive` por membro de forma independente sem alterar o resto da adesão
- **Sub-recurso de etiquetas** — `GET/PUT /api/organizations/{id}/tags` — liste ou substitua completamente as associações de etiquetas (requer o módulo Tags; devolve `503` se inativo)
- Autenticação via cabeçalho `X-FreeScout-API-Key` ou parâmetro de consulta `api_key`
- **Documentação ReDoc** interativa em **Gerir → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Referência completa da API → [docs/api/README.md](docs/api/README.md)**

![Documentação interativa da API — ReDoc com todos os endpoints do OrgPortal](docs/screenshots/api-docs.png)

---

## Instalação

1. Copie a pasta `OrgPortal` para `Modules/` da sua instalação FreeScout
2. Vá a **Gerir → Módulos → OrgPortal → Ativar**
3. Execute as migrações:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Limpe a cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **O suporte ao idioma georgiano** é implementado automaticamente no primeiro arranque — não é necessário copiar ficheiros manualmente.

---

## Atualizações Automáticas

O OrgPortal suporta **atualizações com um clique** através do mecanismo de atualização de módulos integrado do FreeScout.

> **Requer FreeScout 1.8.170 ou posterior.** Em versões mais antigas, atualize manualmente substituindo a pasta `OrgPortal` pelo ZIP da versão mais recente.

Quando uma nova versão está disponível, aparece um banner em **Gerir → Módulos**. Clique em **Atualizar agora** — o FreeScout transfere e instala automaticamente a versão mais recente.

---

## Compatibilidade de Módulos

| Módulo | Estado | Notas |
|--------|--------|-------|
| End-User Portal ≥ 1.0.85 | Opcional | Portal do gestor, sino de notificação, subscrições |
| API and Webhooks ≥ 1.0.80 | Opcional | Endpoints REST API |
| Kanban ≥ 1.0.23 | Opcional | Emblema nos cartões, filtro de organização, etiquetas de coluna Estado multilingues |
| Custom Fields | ✅ Compatível | — |
| Workflows | ✅ Compatível | — |
| Tags | ✅ Compatível | Chips de etiquetas no formulário de edição de organização; associações de etiquetas via API (`/organizations/{id}/tags`); atribuição de pedidos baseada em etiquetas |

---

## Configuração

### Definições Globais — **Gerir → Organizações → separador Sistema**

| Opção | Descrição |
|-------|-----------|
| Mostrar emblema na página de pedido | Emblema de organização na lista de conversas e na vista de pedido |
| Mostrar emblema nos cartões Kanban | Emblema de organização nos cartões do quadro Kanban |
| Fonte de atribuição | `member` / `tag` / `tag_only` — como os pedidos são atribuídos às organizações |
| Preenchimento automático por cron | Executar o preenchimento de 5 em 5 minutos automaticamente |
| Visibilidade do snapshot | Mostrar/ocultar dados de atribuição na barra lateral do pedido |
| Seletor de Idioma do Portal | Ativar o seletor de idioma na barra de navegação do EUP; escolha quais dos 19 idiomas a oferecer |

### Definições por Caixa de Correio — **Definições da Caixa de Correio → OrgPortal**

Substitui os valores globais para a caixa de correio específica.

| Opção | Descrição |
|-------|-----------|
| Mostrar emblema na página de pedido | Ativar/desativar emblema para esta caixa de correio |
| Mostrar emblema nos cartões Kanban | Ativar/desativar emblema para esta caixa de correio |
| Mostrar bloco de organização no perfil do cliente | Alternar o bloco de informações de organização na barra lateral do pedido |
| Filtros de estado de pedidos da empresa | Mapear colunas do Kanban para filtros com nome no portal; etiquetas por idioma com seletor de idioma; arrastar para reordenar |

![Definições por caixa de correio — visibilidade do emblema e filtros de estado do Kanban com etiquetas multilingues](docs/screenshots/mailbox-settings.png)

---

## Traduções

O OrgPortal está totalmente localizado em **19 idiomas**:

| Idioma | Código | Idioma | Código |
|--------|--------|--------|--------|
| Inglês | `en` | Neerlandês | `nl` |
| Ucraniano | `uk` | Norueguês | `no` |
| Alemão | `de` | Dinamarquês | `da` |
| Francês | `fr` | Sueco | `sv` |
| Espanhol | `es` | Finlandês | `fi` |
| Italiano | `it` | Português (BR) | `pt-BR` |
| Checo | `cs` | Português (PT) | `pt-PT` |
| Eslovaco | `sk` | Romeno | `ro` |
| Polaco | `pl` | Chinês Simplificado | `zh-CN` |
| Georgiano | `ka` | | |

Ficheiros de tradução: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Os modelos de e-mail de notificação têm predefinições integradas para todos os 19 idiomas.

### Integração com o Seletor de Idioma

O OrgPortal inclui um seletor de idioma do portal integrado (ative em **separador Sistema → Seletor de Idioma do Portal**). Também se integra com o [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — ambos podem estar ativos simultaneamente.

O idioma que um gestor seleciona aplica-se a todas as cadeias de interface do OrgPortal e é guardado como idioma de notificação — os e-mails são enviados no idioma escolhido automaticamente.

> **Nota técnica:** O middleware `OrgPortalSetLocale` reaaplica o idioma do portal após o middleware `Localize` do FreeScout para evitar que seja reposto para o idioma predefinido do sistema em cada pedido.

---

## Capturas de Ecrã

| | |
|---|---|
| ![Lista de organizações](docs/screenshots/org-list.png) | ![Edição de organização](docs/screenshots/org-edit.png) |
| *Lista de organizações — filtro de estado, pesquisa em tempo real, emblemas coloridos* | *Edição de organização — seletor de cores, chips de etiquetas, tabela de membros* |
| ![Separador Sistema](docs/screenshots/system-settings.png) | ![Edição de cliente](docs/screenshots/customer-org-field.png) |
| *Separador Sistema — modos de atribuição, preenchimento, seletor de idioma* | *Edição de cliente — campo de organização com sugestões automáticas* |
| ![Portal de Pedidos da Empresa](docs/screenshots/portal-tickets.png) | ![Resposta no portal](docs/screenshots/portal-reply.png) |
| *Pedidos da Empresa — tabela, filtro por autor, indicadores de leitura* | *Pedido no portal — resposta com anexos, banner de fechado* |
| ![Definições de Organização do Portal](docs/screenshots/portal-settings.png) | ![Sino de notificação](docs/screenshots/portal-bell.png) |
| *Definições de Organização do Portal — separadores Unidades e Membros* | *Sino de notificação em tempo real com painel suspenso* |
| ![Matriz de subscrição](docs/screenshots/portal-subscriptions.png) | ![Modelos de e-mail](docs/screenshots/admin-templates.png) |
| *Matriz de subscrição de notificação — por unidade, por membro* | *Modelos de e-mail — seletor de idioma, Carregar Predefinição, Summernote* |
| ![Integração com o Kanban](docs/screenshots/kanban-org.png) | ![Definições por caixa de correio](docs/screenshots/mailbox-settings.png) |
| *Kanban — emblemas de organização e modal de filtro de organização* | *Definições por caixa de correio — filtros Kanban com etiquetas multilingues* |
| ![Documentação da API](docs/screenshots/api-docs.png) | |
| *Documentação interativa da API — ReDoc* | |

---

## Licença

[MIT](LICENSE) — © 2026 ASTIN-UA
