# OrgPortal REST API

[← 返回 README](../README.zh-CN.md)

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

*可选 — 需要 [API 和 Webhooks](https://freescout.net/module/api-webhooks/) 模块。*

身份验证 — `X-FreeScout-API-Key` 标头或 `api_key` 查询参数。

> **交互式文档**（ReDoc）可在**管理 → API 和 Webhooks** 页面（链接"OrgPortal API Docs"）或直接访问 `/orgportal/admin/api-docs`。

## 端点

| 方法 | 端点 | 描述 |
|--------|----------|-------------|
| `GET` | `/api/organizations` | 列出组织（分页、邮箱筛选） |
| `POST` | `/api/organizations` | 创建组织 |
| `GET` | `/api/organizations/{id}` | 获取组织及其成员和单位 |
| `PUT` | `/api/organizations/{id}` | 更新组织 |
| `DELETE` | `/api/organizations/{id}` | 删除组织 |
| `GET` | `/api/organizations/{id}/units` | 列出结构单位 |
| `POST` | `/api/organizations/{id}/units` | 创建结构单位 |
| `PUT` | `/api/units/{unitId}` | 重命名单位 |
| `DELETE` | `/api/units/{unitId}` | 删除单位（取消分配成员、降级单位管理员） |
| `GET` | `/api/customers/{id}/organization` | 客户的组织成员关系 |
| `PUT` | `/api/customers/{id}/organization` | 设置/更新客户成员关系 |
| `DELETE` | `/api/customers/{id}/organization` | 从组织中移除客户 |

## 响应代码

| 代码 | 含义 |
|------|---------|
| `200` | 成功或无操作（未更改） |
| `201` | 资源已创建；`Resource-ID` 标头包含 ID |
| `400` | 验证错误 — 详情在 `_embedded.errors` 中 |
| `401` | API 密钥无效或缺失 |
| `404` | 资源未找到 |
| `409` | 冲突 — 客户已在另一个组织中有活跃成员关系 |

---

## 组织

### GET /api/organizations

**查询参数**

| 参数 | 类型 | 默认 | 描述 |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | 页码 |
| `pageSize` | integer | `25` | 每页记录数（最多 100） |
| `mailboxId` | integer | — | 邮箱筛选：返回全局组织 + 绑定到此邮箱的组织 |

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

**请求体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | 组织名称（最多 255 个字符，唯一） |
| `mailboxId` | integer\|null | — | 邮箱 ID 或 `null` / 省略表示全局组织 |

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
|-------|------|-------------|
| `unitId` | integer\|null | 成员所属的结构单位，或 `null` 表示整个组织 |
| `role` | string | `member` 或 `manager` |
| `canManageOrg` | boolean | 此管理员是否可将他人提升为全局管理员 |
| `isActive` | boolean | 有效成员关系；无效成员不接收工单分配或通知 |
| `notifyOnNewTicket` | boolean | 旧版按成员新工单通知标记 |

---

### PUT /api/organizations/{id}

**请求体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | 新组织名称（最多 255 个字符，唯一） |
| `mailboxId` | integer\|null | — | 新邮箱；`null` — 转为全局；省略 — 保持不变 |

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

未做任何更改时，响应消息为 `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(所有成员级联删除)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## 结构单位

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

**请求体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | 单位名称（在组织内唯一） |

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

**请求体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | 新单位名称（在组织内唯一） |

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

删除单位。范围限于此单位的管理员被降级为 `member`；单位的所有成员被取消分配（其 `unitId` 变为 `null`）。

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## 客户成员关系

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

将客户分配到组织或更新其成员关系。**每个客户一个活跃成员关系**：如果客户已在*另一*组织中有*活跃*成员关系，请求将被拒绝，返回 `409 Conflict`。要转移 — 首先通过 `DELETE` 停用或移除当前成员关系。

**请求体**

| 字段 | 类型 | 必需 | 描述 |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | 组织 ID |
| `role` | string | — | `"member"`（默认）或 `"manager"` |
| `unitId` | integer\|null | — | 结构单位（必须属于目标组织），或 `null` 表示整个组织 |
| `canManageOrg` | boolean | — | 授予此管理员将他人提升为全局管理员的权限（默认 `false`） |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(新成员关系)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(成员关系已更新)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(客户已在另一组织中活跃)*
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
