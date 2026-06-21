# OrgPortal REST API

[← 返回 README](../../README.md)

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

*可选项 — 需要 [API 和 Webhooks](https://freescout.net/module/api-webhooks/) 模块。*

身份验证 — `X-FreeScout-API-Key` 标头或 `api_key` 查询参数。

> **交互式文档** (ReDoc) 在 **管理 → API & Webhooks** 页面上可用（链接"OrgPortal API Docs"）或直接访问 `/orgportal/admin/api-docs`。

## 端点

| 方法 | 端点 | 描述 |
|--------|----------|-----------|
| `GET` | `/api/organizations` | 列出组织（分页、邮箱筛选器） |
| `POST` | `/api/organizations` | 创建组织 |
| `GET` | `/api/organizations/{id}` | 获取组织及其成员和单位 |
| `PUT` | `/api/organizations/{id}` | 更新组织（名称、颜色、邮箱、isActive） |
| `DELETE` | `/api/organizations/{id}` | 删除组织 |
| `GET` | `/api/organizations/{id}/members` | 列出组织成员 |
| `GET` | `/api/organizations/{id}/members/{memberId}` | 获取单个成员 |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | 更新成员（角色、单位、canManageOrg、isActive） |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | 移除成员 |
| `GET` | `/api/organizations/{id}/tags` | 列出标签绑定（需要 Tags 模块） |
| `PUT` | `/api/organizations/{id}/tags` | 替换所有标签绑定（需要 Tags 模块） |
| `GET` | `/api/organizations/{id}/units` | 列出结构化单位 |
| `POST` | `/api/organizations/{id}/units` | 创建结构化单位 |
| `PUT` | `/api/units/{unitId}` | 重命名单位 |
| `DELETE` | `/api/units/{unitId}` | 删除单位（成员取消分配、经理降级） |
| `GET` | `/api/customers/{id}/organization` | 客户组织会员资格 |
| `PUT` | `/api/customers/{id}/organization` | 设置/更新客户会员资格 |
| `DELETE` | `/api/customers/{id}/organization` | 从组织中移除客户 |

## 响应代码

| 代码 | 含义 |
|------|---------|
| `200` | 成功 |
| `201` | 资源已创建；`Resource-ID` 标头包含 ID |
| `400` | 验证错误 — 详情见 `_embedded.errors` |
| `401` | 无效或缺失 API 密钥 |
| `404` | 资源未找到 |
| `409` | 冲突 — 客户已在另一个组织中有活跃会员资格 |
| `422` | 业务规则违规 — 例如删除仍有成员或工单的组织 |
| `503` | 所需模块（例如 Tags）未激活 |

---

## 组织

### GET /api/organizations

**查询参数**

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|:-------:|-----------|
| `page` | 整数 | `1` | 页码 |
| `pageSize` | 整数 | `25` | 每页记录数（最大 100） |
| `mailboxId` | 整数 | — | 邮箱筛选器：返回全局组织 + 绑定到此邮箱的组织 |

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

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `name` | 字符串 | ✅ | 组织名称（最多 255 个字符，唯一） |
| `mailboxId` | 整数\|null | — | 邮箱 ID 或 `null` / 省略用于全局组织 |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(标头 `Resource-ID: 1`)*
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

返回组织及其嵌入的**成员**和**单位**。

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

**成员字段**

| 字段 | 类型 | 描述 |
|-------|------|-----------|
| `unitId` | 整数\|null | 成员所属的结构化单位，或 `null` 表示整个组织 |
| `role` | 字符串 | `member` 或 `manager` |
| `canManageOrg` | 布尔值 | 此经理是否可以在门户中将其他人提升为全局经理 |
| `isActive` | 布尔值 | 活跃会员资格；非活跃成员不接收工单分配或通知 |
| `notifyOnNewTicket` | 布尔值 | 每个成员的新工单通知标志 |

---

### PUT /api/organizations/{id}

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `name` | 字符串 | ✅ | 新组织名称（最多 255 个字符，唯一） |
| `color` | 字符串\|null | — | 徽章颜色（十六进制）(`"#ff0000"`)，`null` 重置为默认灰色；省略保持当前 |
| `mailboxId` | 整数\|null | — | 新邮箱；`null` — 设为全局；省略 — 保持不变 |
| `isActive` | 布尔值 | — | `false` 停用组织；省略保持当前 |

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

当组织仍有活跃成员或工单时被阻止。首先移除所有成员并重新分配/删除所有工单。

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

## 组织成员

### GET /api/organizations/{id}/members

返回组织所有成员记录的列表。

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

返回单个成员记录。

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

更新成员的角色、单位分配、canManageOrg 标志或活跃状态。仅更新主体中存在的字段（部分更新）。

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `role` | 字符串 | — | `"member"` 或 `"manager"` |
| `unitId` | 整数\|null | — | 结构化单位（必须属于此组织），或 `null` 移除 |
| `canManageOrg` | 布尔值 | — | 在门户中授予全局经理权限 |
| `isActive` | 布尔值 | — | `false` 停用但不移除 |

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

从组织中移除成员。

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## 组织标签

> 需要 [Tags](https://freescout.net/module/tags/) 模块处于活跃状态。如果未安装模块则返回 `503`。

### GET /api/organizations/{id}/tags

返回组织的所有标签绑定。每个绑定可选择地将标签限制在特定单位。

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

**完全替换** — 用提供的列表替换此组织的所有现有标签绑定。发送空数组 `[]` 移除所有绑定。

**请求主体** — 标签绑定对象的 JSON 数组：

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `tagId` | 整数 | ✅ | FreeScout 标签 ID |
| `unitId` | 整数\|null | — | 将标签限制在特定单位，或省略/`null` 表示组织范围 |

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

## 结构化单位

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

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `name` | 字符串 | ✅ | 单位名称（在组织内唯一） |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(标头 `Resource-ID: 2`)*
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

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `name` | 字符串 | ✅ | 新单位名称（在组织内唯一） |

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

删除单位。范围在此单位的经理降级为 `member`；单位的所有成员取消分配（其 `unitId` 变为 `null`）。

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## 客户会员资格

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

将客户分配给组织或更新其会员资格。**每个客户一个活跃会员资格**：如果客户在*另一个*组织中已有*活跃*会员资格，请求被拒绝，返回 `409 Conflict`。要转移 — 首先通过 `DELETE` 停用或移除当前会员资格。

**请求主体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-----------|
| `organizationId` | 整数 | ✅ | 组织 ID |
| `role` | 字符串 | — | `"member"`（默认）或 `"manager"` |
| `unitId` | 整数\|null | — | 结构化单位（必须属于目标组织），或 `null` 表示整个组织 |
| `canManageOrg` | 布尔值 | — | 授予此经理将其他人提升为全局经理的权限（默认 `false`） |
| `isActive` | 布尔值 | — | `false` 创建/更新为非活跃（默认 `true`） |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(新会员资格)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(会员资格已更新)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(客户已在另一个组织中处于活跃状态)*
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
