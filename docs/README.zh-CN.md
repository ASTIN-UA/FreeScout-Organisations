# OrgPortal — FreeScout B2B 组织管理模块

[← 返回 README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B module" width="140" align="right">

**OrgPortal** 是一个 FreeScout 模块，为您的帮助台添加完整的 **B2B 组织管理**功能：将客户分组到公司中，定义部门层级，为企业管理员提供自助服务门户，并自动化通知——全部在 FreeScout 内完成，无需任何外部工具。

> 正在寻找在 FreeScout 中管理企业账户的方法？想为企业客户提供专属支持门户？想根据角色和部门控制每个 B2B 联系人可以看到哪些工单？OrgPortal 解决了所有这些问题。

**兼容：** FreeScout 1.8.147+  
**可选集成：** [End-User Portal](https://freescout.net/module/end-user-portal/)、[API and Webhooks](https://freescout.net/module/api-webhooks/)、[Kanban](https://freescout.net/module/kanban/)

---

🌐 **其他语言版本：**
[Українська](docs/README.uk.md) ·
[Deutsch](docs/README.de.md) ·
[Français](docs/README.fr.md) ·
[Español](docs/README.es.md) ·
[Italiano](docs/README.it.md) ·
[Polski](docs/README.pl.md) ·
[Čeština](docs/README.cs.md) ·
[Slovenčina](docs/README.sk.md) ·
[Nederlands](docs/README.nl.md) ·
[Norsk](docs/README.no.md) ·
[Dansk](docs/README.da.md) ·
[Svenska](docs/README.sv.md) ·
[Suomi](docs/README.fi.md) ·
[Português (BR)](docs/README.pt-BR.md) ·
[Português (PT)](docs/README.pt-PT.md) ·
[Română](docs/README.ro.md) ·
[中文 (简体)](docs/README.zh-CN.md)

---

## OrgPortal 为 FreeScout 带来了什么

FreeScout 围绕单个客户构建——每封邮件都来自一个人，没有内置的"该人所属公司"概念。这对 B2C 帮助台完全够用，但对 B2B 来说则不足。

OrgPortal 填补了这一空缺：

- **企业账户** — 将客户分组到带有名称、颜色徽章、邮箱范围和启用/停用状态的组织中
- **部门层级** — 将组织划分为结构单元（部门、分支机构、团队）；每个成员的范围限定在其单元内
- **基于角色的访问** — `member` 只能查看自己的工单；`unit_manager` 可查看整个单元；`manager` 可查看整个组织
- **企业自助服务门户** — 管理员可查看所有公司工单、回复、关闭、重新分配作者并管理通知偏好，无需联系您的团队
- **永久工单归属** — 每张工单在创建时都会快照到其组织；历史报告不受客户名单变化影响
- **多语言通知** — 以每位管理员自己的语言发送自动邮件提醒，支持按语言区域设置模板和内置 WYSIWYG 编辑器
- **REST API** — 从您的 CRM 同步成员关系，自动化入职流程，以编程方式管理标签

---

## 组织

*企业账户所有信息的统一管理中心。*

**管理 → 组织** 打开一个包含三个部分的标签页界面：组织、模板和系统。

### 组织列表

- **创建、编辑、删除、启用/停用**组织
- **状态筛选** — 通过单选组在启用 / 停用 / 全部之间切换；即时在客户端筛选表格，无需刷新
- **实时搜索** — 输入 2 个及以上字符开始筛选，无需重新加载页面
- **颜色编码徽章** — 交互式颜色选择器，提供 12 种色板和选择器旁的实时徽章预览；徽章显示在每张工单和 Kanban 卡片上
- 点击徽章或工单数量可打开按该组织筛选的 FreeScout 搜索
- **邮箱绑定** — 组织可以是全局的（所有邮箱）或限定到特定邮箱
- **标签列** — 显示 ✓/✗ 表示是否有任何 FreeScout 标签绑定到该组织（需要 Tags 模块）；标签在编辑表单中通过基于标签片的小部件和自动完成搜索进行分配
- **工单数量列** — 每个组织的对话总数；可点击链接查看完整搜索结果
- **成员数量**列
- **启用 / 停用** — 暂停账户而不丢失任何历史记录；需要启用 Org Snapshot（未启用时按钮禁用并显示提示）
- **删除** — 仅当组织有 0 个成员和 0 张工单时可用（安全保护）
- 所有删除和停用操作都需要确认

![组织列表 — 状态筛选、实时搜索、颜色徽章、标签、工单数量](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### 组织编辑表单

- **名称**和**邮箱绑定**
- **颜色选择器** — 12 种色板，带实时徽章预览
- **标签** — 基于标签片的小部件：输入以搜索现有 FreeScout 标签，点击添加，× 删除
- **成员表格** — 每位成员：姓名、角色、结构单元、`can_manage_org` 复选框（授予组织管理访问权限而无需完整管理员权限）、启用/停用切换
- **结构单元面板** — 直接在编辑表单中创建和重命名单元；成员在同一视图中分配到单元
- **添加成员** — 自动为该客户现有的未归属对话进行回填

![组织编辑 — 颜色选择器、标签片、带角色和单元的成员表格](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### 客户档案集成

- **FreeScout 客户编辑表单中的组织字段** — 组织的实时自动完成搜索；选择组织后显示角色下拉菜单；× 按钮用于删除
- 客户表单中的**"查看组织工单"**快捷链接
- **管理员工单侧边栏中的组织信息块** — 组织名称（可点击链接到组织编辑页面）、结构单元和成员角色；可在设置中按邮箱切换可见性
- **每位客户只能有一个活跃成员关系** — 客户有活跃成员关系时无法被添加到第二个组织；允许非活跃/已归档的成员关系

![客户编辑 — 带自动完成和角色选择器的组织字段](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## 结构单元 — 部门级访问控制

*支持具有复杂内部层级的大型企业。*

组织可以划分为无限数量的**结构单元**（部门、分支机构、区域办事处、项目团队）：

- 在管理员组织编辑表单中或直接从门户（仅限全局管理员）创建、重命名和删除单元
- 将成员分配到单元——每个成员属于一个单元
- **删除单元**会自动将其 `unit_manager` 成员降级为 `member`

**三个角色级别：**

| 角色 | 访问范围 |
|------|----------|
| `member` | 仅自己的工单 |
| `unit_manager` | 其结构单元内的所有工单 |
| `manager`（全局） | 整个组织的所有工单 |

- 单元管理员拥有完整的门户功能——回复、附件、作者重新分配、关闭/重新打开、通知管理——严格限定在其单元范围内
- 工单访问和通知发送在单元边界处强制执行

![组织编辑 — 带角色和单元的成员、单元管理面板](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — 永久工单归属

*即使客户名单发生变化，历史报告依然可靠。*

创建工单时，OrgPortal 将组织上下文记录为永久快照：

- `org_id`、`org_unit_id` 和 `org_attributed_at` 在创建时写入对话
- **不可变** — 如果客户之后离开组织，其历史工单仍归属于该组织；报告永不中断
- **添加成员**会触发自动回填该客户现有的未归属对话

### 归属来源 — 三种模式

在**管理 → 组织 → 系统**标签页中配置：

| 模式 | 行为 |
|------|------|
| `member` | 将工单归属到工单作者所属的组织 |
| `tag` | 优先按绑定到组织的 FreeScout 标签归属；如果没有标签匹配则回退到成员关系 |
| `tag_only` | 仅按标签归属；不使用成员关系 |

当 Tags 模块未激活时，`tag` 和 `tag_only` 模式会被禁用。

### 回填工具

- **进度条** — 显示已归属工单数 X / Y（百分比），完成时显示"完成"指示器
- **预检统计** — 运行回填前，显示将按标签归属、按成员关系归属和未匹配的工单数量明细
- **运行回填**按钮 — 每次点击处理最多 2000 张工单；完成后显示结果摘要（by_tag / by_member / unmatched）
- **自动定时任务**（`attribution_cron_enabled`）— 每 5 分钟调度一次回填，每次运行 1000 张工单，不重叠
- **重置归属** — 清除所有组织快照（危险操作，需要确认）
- 命令行：`php artisan orgportal:backfill-attribution`

![系统标签页 — 归属来源、进度条、预检统计、回填控件](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Kanban 集成

*让您的可视化工作流与 B2B 账户保持一致。*

- 每张 Kanban 卡片上显示带账户指定颜色的组织徽章
- Kanban 筛选面板中的**组织筛选** — 带复选框的多选弹窗；筛选状态在导航过程中持续保留
- **多语言 Kanban 状态筛选标签** — 为每个 Kanban 列按门户语言设置自定义名称；在每邮箱设置中使用语言选择器切换语言区域；拖动重新排序筛选器
- 翻译后的标签同时出现在门户筛选栏和公司工单表格的**状态**列中；回退链：已保存的语言区域 → 已保存的英语 → 原始列名

![Kanban — 卡片上的组织徽章和组织筛选弹窗](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## 访问控制与权限

*在不授予管理员访问权限的情况下委托组织管理。*

- **"允许管理组织"**（`can_manage_org`）— 两个级别：
  - 作为客服设置中的**用户权限** — 允许支持团队负责人管理所有组织而无需管理员权限
  - 作为组织编辑表单中的**每成员标志** — 允许特定组织成员从管理面板管理该组织
- **"允许管理通知模板"** — 用于模板编辑的独立细粒度权限
- 删除组织仍然仅限管理员
- 门户访问严格按邮箱限定范围：组织 A 的管理员无法访问组织 B

![细粒度权限 — 允许管理组织和通知模板](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## 系统设置 — 管理 → 组织 → 系统标签页

*归属、回填和门户语言切换器的仅限管理员控件。*

**系统**标签页仅对 FreeScout 管理员可见。

### 面板 1：工单归属

有关归属模式、回填工具和自动定时任务的完整说明，请参阅上方的 [Org Snapshot](#org-snapshot--永久工单归属)。

### 面板 2：门户语言切换器

- **启用/禁用** End-User Portal 导航栏中的语言切换器
- **选择提供哪 19 种语言区域**（复选框网格）；默认全部启用
- 启用后，管理员可以切换门户语言；其选择会被保存并用于通知邮件
- 这是 OrgPortal 内置的语言切换器——它独立于任何第三方语言切换模块运行；两者可以同时使用

![系统标签页 — 带语言区域复选框的门户语言切换器面板](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — 企业管理员自助服务 *（可选）*

*为您的 B2B 客户提供一个门户，让他们可以管理公司的支持关系——无需为每次状态更新联系您的团队。*

需要 [End-User Portal](https://freescout.net/module/end-user-portal/) 模块。

### 公司工单仪表板

门户导航中专用的**公司工单**部分，带有功能完整的工单表格：

| 列 | 说明 |
|----|------|
| **#** | 工单 ID |
| **主题** | 截断显示，悬停时显示提示 |
| **负责人** | 分配的支持客服 |
| **作者** | 提交工单的客户；点击可按该作者筛选 |
| **状态** | 带图标的活跃 / 待处理 / 已关闭 / 垃圾邮件 |
| **阶段** | 当前门户语言中的 Kanban 列名（仅当 Kanban 模块激活时） |
| **更新时间** | 最后回复的日期和时间 |

**每行两个独立的已读状态指示器** — 这两个指示器跟踪两个不同的人，同时显示：

| 指示器 | 谁的已读状态 | 含义 |
|--------|-------------|------|
| **粗体行** | 正在查看门户的管理员 | 管理员对此对话有未读通知——发生了他们尚未看到的事情 |
| **👁 眼睛图标** | 工单作者（提交工单的客户） | 作者尚未打开最新的客服回复——用于了解客户是否实际看到了回复 |

这两种状态完全独立：一行可以是粗体（管理员未读）同时没有眼睛图标（作者已读），反之亦然。管理员同时看到两者，无需打开工单即可全面了解工单两侧发生的情况。

**作者筛选** — 点击作者名称激活筛选器；表格顶部显示一个横幅，显示活跃作者的姓名和用于清除筛选的 × 链接。

桌面表格和响应式**移动卡片布局**均已包含；根据屏幕宽度自动切换。

筛选栏模板支持通过 `enduserportal::partials.tickets_filters` 进行**覆盖** — 在该路径放置自定义视图以替换 OrgPortal 的默认筛选栏，同时保留所有其他功能。

![公司工单 — 带已读指示器、作者筛选横幅、状态筛选的完整表格](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### 门户中的工单操作

管理员可以直接采取行动——无需联系支持：

- **带附件回复** — 拖放，每次回复可附多个文件；每个话题中显示附件名称和文件大小
- **关闭工单** — 新回复会自动重新打开；工单关闭时会有横幅通知管理员
- **更改工单作者** — 将工单重新分配给另一个组织成员
- **按单元筛选** — 全局管理员按结构单元筛选工单列表
- **按 Kanban 状态筛选** — 可按邮箱配置，标签以当前门户语言显示

![门户工单视图 — 带拖放附件和已关闭工单横幅的回复表单](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### 管理员查看跟踪

- 当管理员在门户中打开工单时，管理员工单视图中的客服回复下方会显示**"已查看"**注记
- 显示管理员姓名、角色（组织管理员 / 单元管理员）和经过的时间
- 全局管理员和单元管理员的查看记录独立跟踪和显示——与 FreeScout 原生"客户已查看"相同的用户体验

![管理员查看跟踪 — 管理员工单视图中客服回复下方显示"已查看"注记](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## 实时通知铃铛 *（可选）*

*在公司工单发生任何事情的瞬间通知管理员。*

需要 [End-User Portal](https://freescout.net/module/end-user-portal/) 模块。

- 🔔 EUP 导航栏中带实时未读计数徽章的铃铛图标 — 在移动端自动重新定位（汉堡菜单旁边）
- 通知事件：**新工单**、**客服回复**、**客户回复** — 适用于所有管理员角色
- 下拉面板，通知按日期分组：操作者姓名、事件类型、工单编号、消息预览、时间戳
- 管理员打开工单时**自动标记为已读**
- 通过 × 标记单个通知已读；面板标题中的**全部标记为已读**
- 每 15 秒轮询一次；在浏览器前进/后退导航时刷新（支持 bfcache）

![实时通知铃铛 — 带分组未读通知的下拉面板](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## 通知订阅 *（可选）*

*让管理员决定他们想了解什么——不多也不少。*

- 门户组织设置"通知"标签页上的**可视化订阅矩阵**
- **三种事件类型：** 新工单 · 客服回复 · 客户回复
- **两个范围级别：** 整个组织（全局管理员） · 单个结构单元
- 没有单元的成员归入单独的**"无单元"**可展开行
- **每成员覆盖** — 展开任意单元行以显示单个成员并内联切换其订阅；具有限定角色的单元管理员会相应标记
- **双向级联逻辑：**
  - 启用"整个组织" → 启用所有单元和所有成员
  - 启用一个单元 → 启用其所有成员
  - 禁用一个成员 → 自动协调单元和组织复选框
- 全局管理员管理所有成员；单元管理员只管理自己的单元
- 通知使用相应邮箱的邮件驱动

![通知订阅矩阵 — 按单元和按成员的切换](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## 门户组织设置

*管理员无需管理员访问权限即可配置其组织结构。*

门户导航中的**组织设置**有三个标签页：

### 通知标签页

上述订阅矩阵。

### 单元标签页 *（仅限全局管理员）*

- **创建单元** — 带名称字段的内联表单
- **重命名单元** — 直接在表格行中内联编辑
- **删除单元** — 带确认的按钮；单元管理员自动降级为成员
- 每个单元显示成员数量

### 成员标签页

- 所有组织成员的表格：姓名、结构单元、角色、启用/停用状态徽章
- 适用时在成员姓名旁显示**"全局管理员"**标签
- **显示已停用**复选框 — 仅当存在非活跃成员时出现；默认隐藏
- **全局管理员**可以使用内联表单更新任何成员的单元和角色（单元选择 + 角色选择 + 应用）
- **全局管理员无法从门户将成员提升为全局管理员** — 这需要管理员访问权限
- 每位成员的**启用 / 停用**按钮，停用需要确认

![门户组织设置 — 单元和成员标签页](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## 多语言通知邮件模板 *（可选）*

*您的企业客户会自动收到用自己语言撰写的支持邮件——无需任何手动操作。*

在**管理 → 组织 → 模板**标签页中配置（对具有"管理模板"权限的用户可见）。

- **按语言区域的模板** — 每种门户语言有单独的主题和正文；使用语言区域下拉菜单切换；无需重新加载页面即可在内存中交换值
- 每种事件类型（新工单 / 客服回复 / 客户回复）的**可折叠面板** — 打开面板时 Summernote 编辑器延迟初始化
- 每个面板中的**加载默认值**按钮 — 恢复当前所选语言区域的内置模板（如果没有特定语言区域的默认值则回退到英语内置）
- 用于富文本 HTML 邮件撰写的 **Summernote WYSIWYG 编辑器**
- **宏变量选择器** — 一键将占位符插入主题或正文；在主题字段中保留光标位置
- **19 个内置默认模板** — 开箱即用；无需配置

**可用宏变量：**

| 变量 | 说明 |
|------|------|
| `{manager_name}` | 接收通知的管理员姓名 |
| `{author_name}` | 创建或回复工单的客户 |
| `{org_name}` | 组织名称 |
| `{unit_name}` | 结构单元名称 |
| `{subject}` | 工单主题 |
| `{ticket_number}` | 工单 ID |
| `{ticket_url}` | 门户中工单的直接链接 |
| `{ticket_text}` | 初始消息的完整文本（HTML） |
| `{reply_text}` | 最新回复的完整文本（HTML） |
| `{created_date}` | 工单创建日期 |
| `{created_time}` | 工单创建时间 |
| `{created_datetime}` | 工单创建日期和时间 |
| `{reply_date}` | 回复日期 |
| `{reply_time}` | 回复时间 |
| `{reply_datetime}` | 回复日期和时间 |

**回退链：** 已保存的语言区域模板 → 内置语言区域模板 → 已保存的英语模板 → 内置英语模板

通知语言由每位管理员的门户语言选择决定，使用语言切换器时自动保存。

![邮件模板 — 按语言区域的可折叠面板、加载默认值按钮、Summernote 编辑器](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *（可选）*

*将 OrgPortal 集成到您的 CRM、ERP 或客户入职工作流中。*

需要 [API and Webhooks](https://freescout.net/module/api-webhooks/) 模块。

- 组织、结构单元、客户成员关系和标签的完整 CRUD
- **组织字段：** `name`、`color`、`mailboxId`、`isActive` — 均可通过 API 读取和更新
- **成员子资源** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — 独立更新角色、单元、`canManageOrg` 和每成员 `isActive` 标志，而不影响其余成员关系
- **标签子资源** — `GET/PUT /api/organizations/{id}/tags` — 列出或完全替换标签绑定（需要 Tags 模块；如果未激活则返回 `503`）
- 通过 `X-FreeScout-API-Key` 标头或 `api_key` 查询参数进行身份验证
- 在**管理 → API & Webhooks → OrgPortal API 文档**（`/orgportal/admin/api-docs`）提供交互式 **ReDoc 文档**

📖 **完整 API 参考 → [docs/api/README.md](docs/api/README.md)**

![交互式 API 文档 — 包含所有 OrgPortal 端点的 ReDoc](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## 安装

1. 将 `OrgPortal` 文件夹复制到您的 FreeScout 安装目录的 `Modules/` 中
2. 前往 **管理 → 模块 → OrgPortal → 激活**
3. 运行迁移：
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. 清除缓存：
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **格鲁吉亚语支持**在首次启动时自动部署——无需手动复制文件。

---

## 自动更新

OrgPortal 通过 FreeScout 内置的模块更新机制支持**一键更新**。

> **需要 FreeScout 1.8.170 或更高版本。** 在旧版本上，通过将 `OrgPortal` 文件夹替换为最新版本 ZIP 来手动更新。

当新版本可用时，**管理 → 模块**上会显示横幅。点击**立即更新** — FreeScout 会自动下载并安装最新版本。

---

## 模块兼容性

| 模块 | 状态 | 说明 |
|------|------|------|
| End-User Portal ≥ 1.0.85 | 可选 | 管理员门户、通知铃铛、订阅 |
| API and Webhooks ≥ 1.0.80 | 可选 | REST API 端点 |
| Kanban ≥ 1.0.23 | 可选 | 卡片上的徽章、组织筛选、多语言状态列标签 |
| Custom Fields | ✅ 兼容 | — |
| Workflows | ✅ 兼容 | — |
| Tags | ✅ 兼容 | 组织编辑表单上的标签片；通过 API 绑定标签（`/organizations/{id}/tags`）；基于标签的工单归属 |

---

## 配置

### 全局设置 — **管理 → 组织 → 系统标签页**

| 选项 | 说明 |
|------|------|
| 在工单页面显示徽章 | 对话列表和工单视图中的组织徽章 |
| 在 Kanban 卡片上显示徽章 | Kanban 板卡片上的组织徽章 |
| 归属来源 | `member` / `tag` / `tag_only` — 工单如何归属到组织 |
| 自动定时任务回填 | 每 5 分钟自动运行一次回填 |
| 快照可见性 | 在工单侧边栏中显示/隐藏归属数据 |
| 门户语言切换器 | 在 EUP 导航栏中启用语言切换器；选择提供 19 种语言区域中的哪些 |

### 每邮箱设置 — **邮箱设置 → OrgPortal**

覆盖特定邮箱的全局值。

| 选项 | 说明 |
|------|------|
| 在工单页面显示徽章 | 为此邮箱启用/禁用徽章 |
| 在 Kanban 卡片上显示徽章 | 为此邮箱启用/禁用徽章 |
| 在客户档案中显示组织块 | 切换工单侧边栏中的组织信息块 |
| 公司工单状态筛选 | 将 Kanban 列映射到门户中的命名筛选器；带语言区域切换器的每语言标签；拖动重新排序 |

![每邮箱设置 — 徽章可见性和带多语言标签的 Kanban 状态筛选](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## 翻译

OrgPortal 完全本地化为 **19 种语言**：

| 语言 | 代码 | 语言 | 代码 |
|------|------|------|------|
| 英语 | `en` | 荷兰语 | `nl` |
| 乌克兰语 | `uk` | 挪威语 | `no` |
| 德语 | `de` | 丹麦语 | `da` |
| 法语 | `fr` | 瑞典语 | `sv` |
| 西班牙语 | `es` | 芬兰语 | `fi` |
| 意大利语 | `it` | 葡萄牙语（巴西） | `pt-BR` |
| 捷克语 | `cs` | 葡萄牙语（葡萄牙） | `pt-PT` |
| 斯洛伐克语 | `sk` | 罗马尼亚语 | `ro` |
| 波兰语 | `pl` | 简体中文 | `zh-CN` |
| 格鲁吉亚语 | `ka` | | |

翻译文件：`Modules/OrgPortal/Resources/lang/{locale}/messages.php`

通知邮件模板内置了所有 19 种语言的默认值。

### 语言切换器集成

OrgPortal 包含内置的门户语言切换器（在**系统标签页 → 门户语言切换器**中启用）。它还与 [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) 集成——两者可以同时激活。

管理员选择的语言适用于所有 OrgPortal UI 字符串，并保存为其通知语言——邮件会自动以其选择的语言发送。

> **技术说明：** `OrgPortalSetLocale` 中间件在 FreeScout 的 `Localize` 中间件之后重新应用门户语言区域，以防止其在每次请求时被重置为系统默认值。

---

## 截图

| | |
|---|---|
| ![组织列表](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![组织编辑](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *组织列表 — 状态筛选、实时搜索、颜色徽章* | *组织编辑 — 颜色选择器、标签片、成员表格* |
| ![系统标签页](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![客户编辑](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *系统标签页 — 归属模式、回填、语言切换器* | *客户编辑 — 带自动完成的组织字段* |
| ![公司工单门户](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![门户回复](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *公司工单 — 表格、作者筛选、已读指示器* | *门户工单 — 带附件的回复、已关闭横幅* |
| ![门户组织设置](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![通知铃铛](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *门户组织设置 — 单元和成员标签页* | *带下拉面板的实时通知铃铛* |
| ![订阅矩阵](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![邮件模板](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *通知订阅矩阵 — 按单元、按成员* | *邮件模板 — 语言区域切换器、加载默认值、Summernote* |
| ![Kanban 集成](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![邮箱设置](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — 组织徽章和组织筛选弹窗* | *每邮箱设置 — 带多语言标签的 Kanban 筛选* |
| ![API 文档](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *交互式 API 文档 — ReDoc* | |

---

## 许可证

[MIT](LICENSE) — © 2026 ASTIN-UA
