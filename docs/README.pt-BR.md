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

O OrgPortal oferece uma API REST completa para gerenciar organizações, unidades estruturais e associações de clientes — autenticação via cabeçalho `X-FreeScout-API-Key` ou parâmetro de consulta `api_key`.

📖 **Referência completa da API → [docs/api/README.pt-BR.md](api/README.pt-BR.md)** (todos os endpoints, exemplos de requisição/resposta, códigos de erro)

A documentação interativa ReDoc também está disponível em **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

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

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

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

[MIT](../LICENSE) — © 2026 ASTIN-UA
