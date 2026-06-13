# OrgPortal — FreeScout 组织门户

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

一个 FreeScout 模块，为客户添加**组织**（公司/团队）的概念，为管理员扩展最终用户门户，并在工单和看板卡上显示组织徽章。

**最低 FreeScout 版本:** 1.8.147  
**依赖:** 无必需依赖  
**可选:** [最终用户门户](https://freescout.net/module/end-user-portal/), [API 和 Webhooks](https://freescout.net/module/api-webhooks/), [看板](https://freescout.net/module/kanban/)

🌐 **语言:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## 功能

### 组织管理（管理员）
- **管理 → 组织** — 完整的 CRUD: 创建、编辑、删除组织
- **邮箱绑定** — 一个组织可以是**全局的**（在所有邮箱中可见）或**绑定到特定邮箱**；对应的标签显示在组织列表中
- 向组织分配客户并选择角色：`member` 或 `manager`
- **直接在表中更改成员角色**（无需删除并重新添加）
- 客户搜索自动补全，按名称或电子邮件搜索；已在任何组织中的客户从结果中排除
- 成员电子邮件在成员表中的名称下显示
- 一个客户 — 一个组织（在数据库和 API 级别强制实施）
- **徽章颜色** — 在组织编辑表单中有 12 种颜色的视觉调色板；默认为灰色

### 用户权限
- 新权限 **"允许管理组织"** — 拥有此权限的非管理员可以访问列表、创建和编辑组织页面
- 删除组织仅限管理员

### 客户卡片
- 客户编辑表单中的**组织**字段 — 选择组织和角色
- **组织工单**按钮 — 打开组织所有工单的搜索

### 工单上的组织徽章
- 在工单页面的主题下方和对话列表中的名称前显示
- 可点击 — 打开此组织所有工单的搜索
- 徽章颜色由组织设置决定（默认灰色）
- 通过**邮箱设置 → OrgPortal** **按邮箱**启用/禁用；全局值用作备选

### 看板卡上的组织徽章
- 在每张卡上的消息计数器之后显示
- 可点击 — 导向组织搜索
- 颜色与组织设置匹配
- **组织**过滤器内置在标准看板过滤器下拉菜单中：带复选框的模态框，类似于标签过滤器；导航间状态保留
- 通过**邮箱设置 → OrgPortal** **按邮箱**启用/禁用

### 组织搜索过滤器
- 使用**组织**过滤器扩展标准 FreeScout 搜索
- 显示属于所选组织的客户的所有工单

### 最终用户门户 — 管理员访问 *(可选)*

组织管理员通过 EUP 获得扩展访问权限：

- 门户导航中的**公司工单**项目
- 公司工单表，包含列：
  - **#** 和**主题**，带省略号截断和悬停提示
  - **负责人** — 分配的代理
  - **作者** — 打开工单的客户；点击按作者筛选组织内的工单
  - **状态** — 活跃 / 待处理 / 已关闭 / 垃圾，带图标
  - **阶段** — 看板列名称（带自定义标签（如已配置））；仅在看板模块活跃时显示
  - **更新于** — 最后回复的日期和时间
- 按工单主题搜索
- 按看板状态过滤（可通过**邮箱设置 → OrgPortal**配置）
- 回复工单并支持**附件**（拖放、多文件）
- **关闭工单** — 管理员可以关闭工单；新回复会自动重新打开它
- 更改工单作者 — 将工单重新分配给另一个组织成员
- **组织设置**页面用于配置电子邮件通知
- 工单访问**严格限制在当前邮箱**（组织复制到另一邮箱 — 门户 403）

### 通知订阅 *(可选)*

门户管理员可以自定义哪些事件和范围触发电子邮件通知：

- **订阅矩阵** — 门户组织设置中的"通知"选项卡
- **事件：** 新工单、代理回复、客户回复
- **范围：** 整个组织（仅全局管理员）或特定的结构单位
- **按成员订阅：** 每个单位行都可扩展 — 点击可查看该单位的每个成员并可内联切换他们的个人订阅。全局管理员管理所有单位的成员；单位管理员仅管理自己的单位。
- **完全传递级联：** "整个组织"驱动每个单位和每个成员；单位复选框驱动其所有成员；取消选中成员会自动协调其单位（和组织）—— 每个事件列都是双向的。
- 通知使用相应邮箱的邮件驱动程序

### 电子邮件通知 *(可选)*
- 启用该选项的管理员在组织任何成员创建新工单时接收电子邮件
- 使用相应邮箱的邮件驱动程序

### 邮箱设置

**邮箱设置 → OrgPortal**（按邮箱）：

| 选项 | 描述 |
|------|------|
| 在工单页面上显示徽章 | 在此邮箱中启用/禁用徽章 |
| 在看板卡上显示徽章 | 在此邮箱中启用/禁用徽章 |
| 公司工单状态过滤器 | 选择在工单页面上显示为复选框的看板列；为每个过滤器自定义标签 |

---

### REST API *(可选，需要 API 和 Webhooks)*

OrgPortal 提供完整的 REST API，用于管理组织、结构单位和客户成员关系 — 通过 `X-FreeScout-API-Key` 标头或 `api_key` 查询参数进行身份验证。

📖 **完整 API 参考 → [docs/api/README.zh-CN.md](api/README.zh-CN.md)**（所有端点、请求/响应示例、错误代码）

交互式 ReDoc 文档也可在 **Manage → API & Webhooks → OrgPortal API Docs**（`/orgportal/admin/api-docs`）中查看。

---

## 安装

1. 将 `OrgPortal` 文件夹复制到 FreeScout 的 `Modules/` 中
2. 在管理面板中：**管理 → 模块 → OrgPortal → 激活**
3. 运行迁移：
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. 清除缓存：
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## 更新

OrgPortal 通过 FreeScout 内置的模块更新机制支持**自动更新**。

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

当有新版本可用时，**管理 → 模块**页面上将出现一个横幅。单击**立即更新** — FreeScout 将自动下载并安装最新版本。

无需手动复制文件。

---

## 模块兼容性

| 模块 | 状态 |
|------|------|
| 最终用户门户 ≥ 1.0.85 | 可选 — 管理员的门户功能 |
| API 和 Webhooks ≥ 1.0.80 | 可选 — REST API 端点 |
| 看板 ≥ 1.0.23 | 可选 — 徽章、过滤器、公司工单中的"阶段"列 |
| 自定义字段 | 兼容 |
| 工作流 | 兼容 |
| 标签 | 兼容 |

---

## 配置

### 全局（**管理 → OrgPortal 设置**）

| 选项 | 默认 |
|------|------|
| 在工单页面上显示徽章 | ✅ |
| 在看板卡上显示徽章 | ✅ |

### 按邮箱（**邮箱设置 → OrgPortal**）

覆盖特定邮箱的全局值。

| 选项 | 描述 |
|------|------|
| 在工单页面上显示徽章 | 对话列表和工单页面上的徽章 |
| 在看板卡上显示徽章 | 看板卡上的徽章 |
| 公司工单状态过滤器 | 看板列作为公司工单页面上的复选框；每个过滤器都有对门户用户可见的自定义标签 |

---

## 翻译

支持的语言：**英语** (`en`)、**乌克兰语** (`uk`)、**罗马尼亚语** (`ro`)、**格鲁吉亚语** (`ka`)、**德语** (`de`)、**法语** (`fr`)、**西班牙语** (`es`)、**意大利语** (`it`)、**捷克语** (`cs`)、**斯洛伐克语** (`sk`)、**波兰语** (`pl`)、**俄语** (`ru`)、**荷兰语** (`nl`)、**挪威语** (`no`)、**丹麦语** (`da`)、**瑞典语** (`sv`)、**芬兰语** (`fi`)、**葡萄牙语（巴西）** (`pt-BR`)、**葡萄牙语（葡萄牙）** (`pt-PT`)、**中文（简体）** (`zh-CN`)。

文件：`Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG 集成

该模块与 [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) 配合正确工作：在门户中选择的语言也适用于 OrgPortal 字符串。

要使语言出现在 EUPSWLANG 列表中，相应的 `Modules/EndUserPortal/Resources/lang/{locale}.json` 文件必须存在。**罗马尼亚语** (`ro`) 的文件包含在包中；**格鲁吉亚语** (`ka`) 仅在管理部分支持（FreeScout 核心系统不支持）。

> **技术细节：** `ReapplyEupLocale` 中间件（在门户路由组中最后注册）在 FreeScout 的 `Localize` 中间件之后恢复区域设置，否则该中间件会将门户语言选择重置为系统默认值。

---

## 许可证

[MIT](../LICENSE) — © 2026 ASTIN-UA
