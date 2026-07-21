<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrgPortal API Documentation</title>
    <style>
        body { margin: 0; padding: 0; }
        .api-docs-topbar {
            background: #1b1b1b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 14px;
        }
        .api-docs-topbar a { color: #aaa; text-decoration: none; }
        .api-docs-topbar a:hover { color: #fff; }
        .api-docs-topbar .title { font-weight: 600; font-size: 15px; color: #fff; }
    </style>
</head>
<body>
    <div class="api-docs-topbar">
        <span class="title">OrgPortal — API Documentation</span>
        <a href="{{ route('orgportal.admin.index') }}">← Back to Organizations</a>
    </div>

    <div id="redoc-container"></div>

    <script>
    var spec = {
      "openapi": "3.0.3",
      "info": {
        "title": "OrgPortal API",
        "version": "1.3.0",
        "description": "REST API for managing organizations in FreeScout via the **OrgPortal** module.\n\n## Authentication\n\nPass your API key using **one** of these methods:\n\n| Method | Example |\n|--------|---------|\n| Header | `X-FreeScout-API-Key: YOUR_KEY` |\n| Query param | `?api_key=YOUR_KEY` |\n\nYour API key is available at **Manage → API & Webhooks**.\n\n## Response conventions\n\n| Code | Meaning |\n|------|---------|\n| `200` | Success — response body always present |\n| `201` | Resource created — body contains the new object, `Resource-ID` header contains its ID |\n| `400` | Validation error — see `_embedded.errors` for field-level details |\n| `401` | Invalid or missing API key |\n| `404` | Resource not found |\n| `409` | Conflict — e.g. customer already belongs to another organization |\n| `422` | Refused for a state reason rather than a malformed body — e.g. deleting an organization that still has members |\n| `503` | A FreeScout module this endpoint depends on is not active (tag endpoints require the **Tags** module) |\n\n### Update semantics\n\nEvery `PUT` in this API is a **partial** update: only the fields actually present in the request body are changed, and omitted fields keep their current value. The single exception is `name` on `PUT /api/organizations/{id}`, which is required on every call.\n\n### Name length\n\n`name` on organizations and units is stored in a `varchar(191)` column, so **191 characters** is the real maximum — not 255."
      },
      "servers": [
        { "url": "{{ rtrim(config('app.url'), '/') }}", "description": "This server" }
      ],
      "security": [{ "ApiKey": [] }],
      "components": {
        "securitySchemes": {
          "ApiKey": {
            "type": "apiKey",
            "in": "header",
            "name": "X-FreeScout-API-Key",
            "description": "API key from Manage → API & Webhooks"
          }
        },
        "schemas": {
          "Organization": {
            "type": "object",
            "properties": {
              "id":        { "type": "integer", "example": 1 },
              "name":      { "type": "string",  "maxLength": 191, "example": "Acme Corp" },
              "color":     { "type": "string",  "nullable": true, "example": "#2e6da4", "description": "Badge color as a hex string. `null` means the default gray (`#9eaab5`) is used." },
              "isActive":  { "type": "boolean", "example": true, "description": "Deactivated (`false`) organizations keep all their data but are hidden from day-to-day use. New organizations are active by default." },
              "mailboxId": { "type": "integer", "nullable": true, "example": null, "description": "Mailbox this organization is scoped to. `null` means visible in all mailboxes (global)." },
              "createdAt": { "type": "string", "format": "date-time" },
              "updatedAt": { "type": "string", "format": "date-time" }
            }
          },
          "OrganizationWithMembers": {
            "allOf": [
              { "$ref": "#/components/schemas/Organization" },
              {
                "type": "object",
                "properties": {
                  "_embedded": {
                    "type": "object",
                    "properties": {
                      "members": { "type": "array", "items": { "$ref": "#/components/schemas/Member" } },
                      "units":   { "type": "array", "items": { "$ref": "#/components/schemas/Unit" } }
                    }
                  }
                }
              }
            ]
          },
          "Unit": {
            "type": "object",
            "description": "A structural subdivision within an organization (level-2 grouping).",
            "properties": {
              "id":             { "type": "integer", "example": 7 },
              "organizationId": { "type": "integer", "example": 1 },
              "name":           { "type": "string",  "maxLength": 191, "example": "Sales department" },
              "createdAt":      { "type": "string", "format": "date-time" },
              "updatedAt":      { "type": "string", "format": "date-time" }
            }
          },
          "Member": {
            "type": "object",
            "properties": {
              "id":                { "type": "integer", "example": 5 },
              "organizationId":    { "type": "integer", "example": 1 },
              "unitId":            { "type": "integer", "nullable": true, "example": 7, "description": "Structural unit this member belongs to. `null` = no unit. A manager with `unitId = null` is a global manager (sees the whole organization); a manager with a `unitId` only sees that unit's tickets." },
              "customerId":        { "type": "integer", "example": 42 },
              "role":              { "type": "string", "enum": ["member", "manager"], "example": "manager", "description": "Two values only: `member` or `manager`. A **unit manager** is `role: manager` with a non-null `unitId`; a **global manager** is `role: manager` with `unitId: null`. The value `unit_manager` does not exist — passing it returns 400." },
              "canManageOrg":      { "type": "boolean", "example": false, "description": "Whether this (global) manager may promote others to global manager from the portal. Admin-granted only." },
              "isActive":          { "type": "boolean", "example": true, "description": "Deactivated (`false`) members keep their ticket history but can no longer be assigned as a ticket author." },
              "notifyOnNewTicket": { "type": "boolean", "readOnly": true, "example": true, "description": "Whether this member is notified about new organization tickets. Read-only over the API — it is managed by the member from the portal's notification settings." },
              "createdAt":         { "type": "string", "format": "date-time" },
              "updatedAt":         { "type": "string", "format": "date-time" }
            }
          },
          "CustomerMembership": {
            "type": "object",
            "properties": {
              "customerId":        { "type": "integer", "example": 42 },
              "organizationId":    { "type": "integer", "example": 1 },
              "organizationName":  { "type": "string",  "example": "Acme Corp" },
              "unitId":            { "type": "integer", "nullable": true, "example": 7 },
              "unitName":          { "type": "string",  "nullable": true, "example": "Sales department" },
              "role":              { "type": "string", "enum": ["member", "manager"], "example": "manager", "description": "`member` or `manager`. Unit manager = `manager` + non-null `unitId`. Global manager = `manager` + `unitId: null`." },
              "canManageOrg":      { "type": "boolean", "example": false },
              "isActive":          { "type": "boolean", "example": true },
              "notifyOnNewTicket": { "type": "boolean", "example": true }
            }
          },
          "OrganizationTag": {
            "type": "object",
            "description": "Binds a FreeScout tag to an organization, optionally narrowed to one of its units. Requires the **Tags** module.",
            "properties": {
              "id":             { "type": "integer", "example": 3 },
              "organizationId": { "type": "integer", "example": 1 },
              "tagId":          { "type": "integer", "example": 5, "description": "ID of a tag from the FreeScout Tags module." },
              "unitId":         { "type": "integer", "nullable": true, "example": 7, "description": "Restrict this binding to a single unit, or `null` to apply it organization-wide." }
            }
          },
          "ModuleUnavailable": {
            "type": "object",
            "properties": {
              "message":   { "type": "string", "example": "Tags module is not active." },
              "errorCode": { "type": "string", "example": "TAGS_MODULE_IS_NOT_ACTIVE." }
            }
          },
          "StateConflict": {
            "type": "object",
            "description": "Returned with `422` when a request is well-formed but refused because of the current state of the data. `_embedded.errors` is a flat object of counters here, not the field-error array used by `400` responses.",
            "properties": {
              "message":   { "type": "string", "example": "Cannot delete an organization that has members. Remove all members first." },
              "errorCode": { "type": "string", "example": "CANNOT_DELETE_AN_ORGANIZATION_THAT_HAS_MEMBERS._REMOVE_ALL_MEMBERS_FIRST." },
              "_embedded": {
                "type": "object",
                "properties": {
                  "errors": { "type": "object", "example": { "members_count": 3 } }
                }
              }
            }
          },
          "Customer": {
            "type": "object",
            "properties": {
              "id":        { "type": "integer", "example": 42 },
              "firstName": { "type": "string",  "example": "John" },
              "lastName":  { "type": "string",  "example": "Doe" },
              "email":     { "type": "string",  "example": "john@acme.com" },
              "createdAt": { "type": "string", "format": "date-time" },
              "updatedAt": { "type": "string", "format": "date-time" }
            }
          },
          "Mailbox": {
            "type": "object",
            "properties": {
              "id":        { "type": "integer", "example": 1 },
              "name":      { "type": "string",  "example": "Support" },
              "email":     { "type": "string",  "example": "support@example.com" },
              "createdAt": { "type": "string", "format": "date-time" },
              "updatedAt": { "type": "string", "format": "date-time" }
            }
          },
          "PaginationPage": {
            "type": "object",
            "properties": {
              "size":          { "type": "integer", "example": 25 },
              "totalElements": { "type": "integer", "example": 100 },
              "totalPages":    { "type": "integer", "example": 4 },
              "number":        { "type": "integer", "example": 1 }
            }
          },
          "SuccessResponse": {
            "type": "object",
            "properties": {
              "success": { "type": "boolean", "example": true },
              "message": { "type": "string",  "example": "Operation completed successfully." }
            }
          },
          "ValidationError": {
            "type": "object",
            "properties": {
              "message":   { "type": "string", "example": "Validation failed" },
              "errorCode": { "type": "string", "example": "VALIDATION_FAILED" },
              "_embedded": {
                "type": "object",
                "properties": {
                  "errors": {
                    "type": "array",
                    "items": {
                      "type": "object",
                      "properties": {
                        "path":    { "type": "string", "example": "name" },
                        "message": { "type": "string", "example": "Name is required." },
                        "source":  { "type": "string", "example": "JSON" }
                      }
                    }
                  }
                }
              }
            }
          },
          "NotFound": {
            "type": "object",
            "properties": {
              "message":   { "type": "string", "example": "Organization not found." },
              "errorCode": { "type": "string", "example": "ORGANIZATION_NOT_FOUND." }
            }
          },
          "Conflict": {
            "type": "object",
            "properties": {
              "message":   { "type": "string", "example": "Customer already has an active membership in another organization." },
              "errorCode": { "type": "string", "example": "CUSTOMER_ALREADY_HAS_AN_ACTIVE_MEMBERSHIP_IN_ANOTHER_ORGANIZATION." },
              "_embedded": {
                "type": "object",
                "properties": {
                  "errors": {
                    "type": "array",
                    "items": {
                      "type": "object",
                      "properties": {
                        "path":    { "type": "string", "example": "organizationId" },
                        "message": { "type": "string", "example": "Customer is an active member of organization #3. Deactivate or remove it first via DELETE /api/customers/42/organization." },
                        "source":  { "type": "string", "example": "JSON" }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      },
      "tags": [
        { "name": "Organizations",         "description": "Create and manage organizations" },
        { "name": "Units",                 "description": "Create and manage structural subdivisions (units) within an organization" },
        { "name": "Members",               "description": "List and manage an organization's membership records directly by member ID (see also Customer Membership, which operates by customerId)" },
        { "name": "Tags",                  "description": "Bind FreeScout tags to an organization or one of its units. Requires the Tags module to be active — every endpoint here returns 503 when it is not." },
        { "name": "Customer Membership",   "description": "Assign customers to organizations and manage their roles" },
        { "name": "Customers (FreeScout)", "description": "Standard FreeScout endpoints for looking up customers. Use these to obtain a `customerId` before membership operations." },
        { "name": "Mailboxes (FreeScout)", "description": "Standard FreeScout endpoints for listing mailboxes. Use these to obtain a `mailboxId` for scoping organizations or filtering results." }
      ],
      "paths": {

        "/api/organizations": {
          "get": {
            "tags": ["Organizations"],
            "summary": "List / search organizations",
            "description": "Lists organizations ordered by name, 25 per page by default.\n\nUse `exactName` to look up one organization by its full name — the reliable way to recover from a `400 \"An organization with this name already exists\"` on `POST`, since it returns the existing record instead of an error. Use `name` for a partial, case-insensitive contains-search. When both are sent, `exactName` wins.\n\nAll filters combine with `mailboxId` and with pagination.",
            "operationId": "listOrganizations",
            "parameters": [
              { "name": "page",      "in": "query", "schema": { "type": "integer", "default": 1 },  "description": "Page number" },
              { "name": "pageSize",  "in": "query", "schema": { "type": "integer", "default": 25, "minimum": 1, "maximum": 100 }, "description": "Items per page (max 100)" },
              { "name": "name",      "in": "query", "schema": { "type": "string" }, "description": "Partial, case-insensitive match on the organization name (contains). `%` and `_` are matched literally, not as wildcards. Ignored when `exactName` is present." },
              { "name": "exactName", "in": "query", "schema": { "type": "string" }, "description": "Exact match on the organization name. Returns zero or one organization — names are unique." },
              { "name": "isActive",  "in": "query", "schema": { "type": "boolean" }, "description": "Return only active (`true`) or only deactivated (`false`) organizations. Omit to return both." },
              { "name": "mailboxId", "in": "query", "schema": { "type": "integer" }, "description": "Filter by mailbox ID. Returns global organizations (mailboxId = null) plus those scoped to this mailbox." },
              { "name": "api_key",   "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": {
                  "application/json": {
                    "schema": {
                      "type": "object",
                      "properties": {
                        "_embedded": {
                          "type": "object",
                          "properties": {
                            "organizations": { "type": "array", "items": { "$ref": "#/components/schemas/Organization" } }
                          }
                        },
                        "page": { "$ref": "#/components/schemas/PaginationPage" }
                      }
                    }
                  }
                }
              }
            }
          },
          "post": {
            "tags": ["Organizations"],
            "summary": "Create an organization",
            "description": "Creates an organization. It is **active** unless you explicitly pass `isActive: false` — no follow-up `PUT` is needed to make a new organization usable.\n\nIf the name is already taken the call fails with `400`; use `GET /api/organizations?exactName=…` to fetch the existing organization instead.",
            "operationId": "createOrganization",
            "parameters": [
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": {
                "application/json": {
                  "schema": {
                    "type": "object",
                    "required": ["name"],
                    "properties": {
                      "name":      { "type": "string",  "maxLength": 191, "example": "Acme Corp", "description": "Organization name — must be unique, max 191 characters" },
                      "color":     { "type": "string",  "nullable": true, "example": "#2e6da4", "description": "Badge color as a hex string (`#rgb` or `#rrggbb`). Omit or pass `null` for the default gray." },
                      "isActive":  { "type": "boolean", "default": true, "description": "Defaults to `true`. Pass `false` to create the organization in a deactivated state." },
                      "mailboxId": { "type": "integer", "nullable": true, "example": null, "description": "Mailbox to scope this organization to. Omit or pass `null` for a global organization (visible in all mailboxes)." }
                    }
                  }
                }
              }
            },
            "responses": {
              "201": {
                "description": "Organization created",
                "headers": {
                  "Resource-ID": { "schema": { "type": "integer" }, "description": "ID of the newly created organization" }
                },
                "content": {
                  "application/json": { "schema": { "$ref": "#/components/schemas/Organization" } }
                }
              },
              "400": {
                "description": "Validation error — empty name, name longer than 191 characters, duplicate name, malformed color, or unknown mailboxId",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" } } }
              }
            }
          }
        },

        "/api/organizations/{id}": {
          "get": {
            "tags": ["Organizations"],
            "summary": "Get an organization (with members)",
            "operationId": "getOrganization",
            "parameters": [
              { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/OrganizationWithMembers" } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "put": {
            "tags": ["Organizations"],
            "summary": "Update an organization",
            "description": "Updates the organization. Send only the fields you want to change — omitted fields keep their current value — except `name`, which is **required on every call**, even when you are only flipping `isActive`.\n\nTo clear the mailbox scope (make the org global), pass `\"mailboxId\": null`. To reset the badge color to the default gray, pass `\"color\": null`.",
            "operationId": "updateOrganization",
            "parameters": [
              { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": {
                "application/json": {
                  "schema": {
                    "type": "object",
                    "required": ["name"],
                    "properties": {
                      "name":      { "type": "string",  "maxLength": 191, "example": "Acme Corporation", "description": "New organization name — must be unique, max 191 characters. Required even when unchanged." },
                      "color":     { "type": "string",  "nullable": true, "example": "#2e6da4", "description": "Badge color as a hex string (`#rgb` or `#rrggbb`). Pass `null` to reset to the default gray. Omit to keep the current value." },
                      "isActive":  { "type": "boolean", "example": false, "description": "Activate (`true`) or deactivate (`false`) the organization. Omit to keep the current value." },
                      "mailboxId": { "type": "integer", "nullable": true, "example": null, "description": "Mailbox scope. Pass `null` to make the organization global. Omit this field entirely to keep the current value unchanged." }
                    }
                  }
                }
              }
            },
            "responses": {
              "200": {
                "description": "Organization updated",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Organization updated." } } }
              },
              "400": {
                "description": "Validation error",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "delete": {
            "tags": ["Organizations"],
            "summary": "Delete an organization",
            "description": "Deletes the organization.\n\nBlocked with `422` while **any** membership record still points at it — including **deactivated** ones, which are not visible through `GET /api/customers/{customerId}/organization`. List them with `GET /api/organizations/{id}/members` (it returns active and deactivated alike) and hard-delete each via `DELETE /api/organizations/{id}/members/{memberId}` before retrying.\n\nAlso blocked with `422` while any ticket is still attributed to the organization — reassign or delete those first.",
            "operationId": "deleteOrganization",
            "parameters": [
              { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Organization deleted",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Organization deleted." } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "422": {
                "description": "Organization still has membership records (active or deactivated) or tickets, and cannot be deleted",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/StateConflict" }, "examples": {
                  "has_members": { "summary": "Organization has members (counts deactivated ones too)", "value": { "message": "Cannot delete an organization that has members. Remove all members first.", "errorCode": "CANNOT_DELETE_AN_ORGANIZATION_THAT_HAS_MEMBERS._REMOVE_ALL_MEMBERS_FIRST.", "_embedded": { "errors": { "members_count": 3 } } } },
                  "has_tickets": { "summary": "Organization has tickets", "value": { "message": "Cannot delete an organization that has tickets. Reassign or delete all tickets first.", "errorCode": "CANNOT_DELETE_AN_ORGANIZATION_THAT_HAS_TICKETS._REASSIGN_OR_DELETE_ALL_TICKETS_FIRST.", "_embedded": { "errors": { "conversations_count": 12 } } } }
                } } }
              }
            }
          }
        },

        "/api/organizations/{id}/units": {
          "get": {
            "tags": ["Units"],
            "summary": "List units",
            "operationId": "listUnits",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "type": "object", "properties": { "_embedded": { "type": "object", "properties": { "units": { "type": "array", "items": { "$ref": "#/components/schemas/Unit" } } } } } } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "post": {
            "tags": ["Units"],
            "summary": "Create unit",
            "operationId": "createUnit",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": { "application/json": { "schema": { "type": "object", "required": ["name"], "properties": { "name": { "type": "string", "maxLength": 191, "example": "Sales department", "description": "Unit name — must be unique within this organization, max 191 characters" } } } } }
            },
            "responses": {
              "201": {
                "description": "Unit created",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/Unit" } } }
              },
              "400": {
                "description": "Validation error",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          }
        },

        "/api/units/{unitId}": {
          "put": {
            "tags": ["Units"],
            "summary": "Rename unit",
            "operationId": "updateUnit",
            "parameters": [
              { "name": "unitId",  "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Unit ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": { "application/json": { "schema": { "type": "object", "required": ["name"], "properties": { "name": { "type": "string", "maxLength": 191, "example": "Renamed unit", "description": "New unit name — must be unique within the owning organization, max 191 characters" } } } } }
            },
            "responses": {
              "200": {
                "description": "Unit updated",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Unit updated." } } }
              },
              "400": {
                "description": "Validation error",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" } } }
              },
              "404": {
                "description": "Unit not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "delete": {
            "tags": ["Units"],
            "summary": "Delete unit",
            "description": "Deletes the unit. Its members are unassigned (`unitId` becomes `null`) and any unit managers are demoted to members.",
            "operationId": "deleteUnit",
            "parameters": [
              { "name": "unitId",  "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Unit ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Unit deleted",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Unit deleted." } } }
              },
              "404": {
                "description": "Unit not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          }
        },

        "/api/organizations/{id}/members": {
          "get": {
            "tags": ["Members"],
            "summary": "List members",
            "description": "Lists **all** membership records for the organization, active and deactivated alike. Use `isActive` on each record to tell them apart.",
            "operationId": "listMembers",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "type": "object", "properties": { "_embedded": { "type": "object", "properties": { "members": { "type": "array", "items": { "$ref": "#/components/schemas/Member" } } } } } } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          }
        },

        "/api/organizations/{id}/members/{memberId}": {
          "get": {
            "tags": ["Members"],
            "summary": "Get a member",
            "operationId": "getMember",
            "parameters": [
              { "name": "id",       "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "memberId", "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Membership record ID" },
              { "name": "api_key",  "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/Member" } } }
              },
              "404": {
                "description": "Organization or member not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "put": {
            "tags": ["Members"],
            "summary": "Update a member",
            "description": "Updates `role`, `unitId`, `canManageOrg`, and/or `isActive`. All fields are optional — only the ones present in the body are changed.\n\nSet `role = manager` with a `unitId` to make this a **unit manager** (sees only that unit's tickets), or with `unitId = null` to make it a **global manager** (sees the whole organization).\n\nSetting `isActive: false` is the correct way to \"fire\" a member without losing their ticket history — deactivated members keep their history but can no longer be assigned as a ticket author. Compare with `DELETE`, below, which is a hard delete blocked once the member has tickets.",
            "operationId": "updateMember",
            "parameters": [
              { "name": "id",       "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "memberId", "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Membership record ID" },
              { "name": "api_key",  "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": false,
              "content": { "application/json": { "schema": { "type": "object", "properties": {
                "role":         { "type": "string",  "enum": ["member", "manager"], "example": "manager" },
                "unitId":       { "type": "integer", "nullable": true, "example": 7, "description": "Must belong to this organization. `null` = no unit (global manager when role = manager)." },
                "canManageOrg": { "type": "boolean", "example": true },
                "isActive":     { "type": "boolean", "example": false, "description": "Set to `false` to deactivate (\"fire\") this member while preserving their ticket history." }
              } } } }
            },
            "responses": {
              "200": {
                "description": "Member updated (or no changes if the body was empty)",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Member updated." } } }
              },
              "400": {
                "description": "Validation error",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" }, "examples": {
                  "bad_role": { "summary": "Invalid role", "value": { "message": "Validation failed", "_embedded": { "errors": [{ "path": "role", "message": "role must be \"member\" or \"manager\".", "source": "JSON" }] } } },
                  "bad_unit": { "summary": "Unit belongs to a different organization", "value": { "message": "Validation failed", "_embedded": { "errors": [{ "path": "unitId", "message": "Unit does not belong to organization #1.", "source": "JSON" }] } } }
                } } }
              },
              "404": {
                "description": "Organization or member not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "delete": {
            "tags": ["Members"],
            "summary": "Remove a member",
            "description": "Hard-deletes the membership record — active or deactivated alike. This is the only way to clear a **deactivated** membership, which is what `DELETE /api/organizations/{id}` requires before it will delete the organization itself.\n\nBlocked with `422` if the member has tickets in this organization — use `PUT` with `isActive: false` instead to deactivate (\"fire\") them and keep their ticket history intact.\n\nWhich tickets count depends on the **Org Snapshot visibility** setting. With it on, only tickets actually attributed to *this* organization block the delete; with it off there is no per-ticket attribution, so any ticket the customer has ever created blocks it.",
            "operationId": "deleteMember",
            "parameters": [
              { "name": "id",       "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "memberId", "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Membership record ID" },
              { "name": "api_key",  "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Member removed",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Member removed." } } }
              },
              "404": {
                "description": "Organization or member not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "422": {
                "description": "Member has tickets in this organization and cannot be removed",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/StateConflict" }, "example": { "message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "errorCode": "CANNOT_REMOVE_THIS_MEMBER:_THEY_HAVE_TICKETS_IN_THIS_ORGANIZATION._DEACTIVATE_THEM_INSTEAD_(ISACTIVE:_FALSE)_TO_PRESERVE_THEIR_TICKET_HISTORY.", "_embedded": { "errors": { "tickets_count": 5 } } } } }
              }
            }
          }
        },

        "/api/organizations/{id}/tags": {
          "get": {
            "tags": ["Tags"],
            "summary": "List tag bindings",
            "description": "Lists every tag bound to this organization. A binding with a `unitId` applies to that unit only; one with `unitId: null` applies organization-wide.\n\nRequires the **Tags** module — returns `503` when it is not active.",
            "operationId": "listOrganizationTags",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "type": "object", "properties": { "_embedded": { "type": "object", "properties": { "tags": { "type": "array", "items": { "$ref": "#/components/schemas/OrganizationTag" } } } } } } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "503": {
                "description": "Tags module is not active",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ModuleUnavailable" } } }
              }
            }
          },
          "put": {
            "tags": ["Tags"],
            "summary": "Replace tag bindings",
            "description": "**Full replace, not a merge.** Every existing binding for this organization is deleted and replaced by the array you send — so send the complete desired set, and send `[]` to clear all bindings.\n\nThe request body is a bare JSON **array**, not an object. Validation is all-or-nothing: if any entry is invalid, nothing is written.\n\nRequires the **Tags** module — returns `503` when it is not active.",
            "operationId": "setOrganizationTags",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Organization ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": { "application/json": { "schema": {
                "type": "array",
                "items": {
                  "type": "object",
                  "required": ["tagId"],
                  "properties": {
                    "tagId":  { "type": "integer", "example": 5, "description": "ID of a tag from the FreeScout Tags module." },
                    "unitId": { "type": "integer", "nullable": true, "example": 7, "description": "Restrict the binding to this unit. Must belong to the organization. Omit or pass `null` to apply it organization-wide." }
                  }
                },
                "example": [ { "tagId": 5, "unitId": 7 }, { "tagId": 8 } ]
              } } }
            },
            "responses": {
              "200": {
                "description": "Tag bindings replaced",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Tags updated." } } }
              },
              "400": {
                "description": "Validation error — body was not an array, a tagId was missing/non-positive, or a unitId belongs to a different organization",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" }, "example": { "message": "Validation failed", "errorCode": "VALIDATION_FAILED", "_embedded": { "errors": [ { "path": "[0].tagId", "message": "tagId must be a positive integer.", "source": "JSON" } ] } } } }
              },
              "404": {
                "description": "Organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "503": {
                "description": "Tags module is not active",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ModuleUnavailable" } } }
              }
            }
          }
        },

        "/api/customers/{customerId}/organization": {
          "get": {
            "tags": ["Customer Membership"],
            "summary": "Get customer's organization",
            "operationId": "getCustomerOrganization",
            "parameters": [
              { "name": "customerId", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "FreeScout customer ID" },
              { "name": "api_key",    "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/CustomerMembership" } } }
              },
              "404": {
                "description": "Customer not found or not a member of any organization",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              }
            }
          },
          "put": {
            "tags": ["Customer Membership"],
            "summary": "Assign / update customer membership",
            "description": "Assigns a customer to an organization, or updates their existing membership in it.\n\n**Partial update.** For an existing membership only the fields present in the body are changed; `role`, `unitId`, `canManageOrg` and `isActive` all keep their current values when omitted. Sending just `organizationId` is a no-op that returns `\"No changes.\"`. When the membership is being **created**, omitted fields fall back to `role: member`, `unitId: null`, `canManageOrg: false`, `isActive: true`.\n\nEach customer can have **at most one active** membership. The request is rejected with `409 Conflict` if it would leave the customer active in two organizations at once — that covers both creating a membership while another is active, and re-activating a dormant one with `isActive: true`. Deactivate or remove the other membership first via `DELETE /api/customers/{customerId}/organization`.\n\nSet `role = manager` with a `unitId` to create a **unit manager** (sees only that unit's tickets), or with `unitId = null` to create a **global manager** (sees the whole organization).",
            "operationId": "setCustomerOrganization",
            "parameters": [
              { "name": "customerId", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "FreeScout customer ID" },
              { "name": "api_key",    "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "requestBody": {
              "required": true,
              "content": {
                "application/json": {
                  "schema": {
                    "type": "object",
                    "required": ["organizationId"],
                    "properties": {
                      "organizationId": { "type": "integer", "example": 1, "description": "Target organization ID. The only required field." },
                      "unitId": { "type": "integer", "nullable": true, "example": 7, "description": "Structural unit within the organization, or `null` for none. Must belong to `organizationId`. Omit to keep the member's current unit." },
                      "role": {
                        "type": "string",
                        "enum": ["member", "manager"],
                        "description": "`member` — regular member. `manager` — with a `unitId` sees that unit's tickets; with `unitId = null` is a global manager that sees all organization tickets and receives new-ticket notifications. Omit to keep the current role (defaults to `member` on creation)."
                      },
                      "canManageOrg": { "type": "boolean", "description": "Grant a global manager the right to promote others to global manager from the portal. Omit to keep the current value (defaults to `false` on creation)." },
                      "isActive": { "type": "boolean", "example": false, "description": "Deactivate (`false`) or re-activate (`true`) this membership. Omit to keep the current value (defaults to `true` on creation). Re-activating is subject to the one-active-membership rule and can return `409`." }
                    }
                  }
                }
              }
            },
            "responses": {
              "200": {
                "description": "Membership updated, or no updatable field was present in the body (`\"No changes.\"`)",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "examples": {
                  "updated":    { "summary": "Fields changed",   "value": { "success": true, "message": "Membership updated." } },
                  "no_changes": { "summary": "Nothing to change", "value": { "success": true, "message": "No changes." } }
                } } }
              },
              "201": {
                "description": "Membership created",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Membership created." } } }
              },
              "400": {
                "description": "Validation error",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/ValidationError" } } }
              },
              "404": {
                "description": "Customer or organization not found",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "409": {
                "description": "Customer already belongs to a different organization",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/Conflict" } } }
              }
            }
          },
          "delete": {
            "tags": ["Customer Membership"],
            "summary": "Remove customer from organization",
            "operationId": "removeCustomerOrganization",
            "description": "Removes the customer's **active** membership only, and returns `404` when they have none — a deactivated membership is not reachable here. Historical (deactivated) memberships are preserved and untouched; to hard-delete one, use `DELETE /api/organizations/{id}/members/{memberId}`.\n\nBlocked with `422` if the customer has tickets in this organization — deactivate them instead via `PUT /api/customers/{customerId}/organization` with `isActive: false` to preserve their ticket history.\n\nWhich tickets count depends on the **Org Snapshot visibility** setting: with it on, only tickets attributed to this organization block removal; with it off, any ticket the customer has ever created does.",
            "parameters": [
              { "name": "customerId", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "FreeScout customer ID" },
              { "name": "api_key",    "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Membership removed",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Membership removed." } } }
              },
              "404": {
                "description": "Customer not found or not an active member of any organization",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
              },
              "422": {
                "description": "Customer has tickets in this organization and cannot be removed",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/StateConflict" }, "example": { "message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": { "errors": { "tickets_count": 5 } } } } }
              }
            }
          }
        },

        "/api/customers": {
          "get": {
            "tags": ["Customers (FreeScout)"],
            "summary": "List customers",
            "description": "Standard FreeScout endpoint. Use it to find a `customerId` before membership operations.",
            "operationId": "listCustomers",
            "parameters": [
              { "name": "email",    "in": "query", "schema": { "type": "string" }, "description": "Filter by email address" },
              { "name": "page",     "in": "query", "schema": { "type": "integer", "default": 1 } },
              { "name": "pageSize", "in": "query", "schema": { "type": "integer", "default": 25, "maximum": 100 } },
              { "name": "api_key",  "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": {
                  "application/json": {
                    "schema": {
                      "type": "object",
                      "properties": {
                        "_embedded": {
                          "type": "object",
                          "properties": {
                            "customers": { "type": "array", "items": { "$ref": "#/components/schemas/Customer" } }
                          }
                        },
                        "page": { "$ref": "#/components/schemas/PaginationPage" }
                      }
                    }
                  }
                }
              }
            }
          }
        },

        "/api/mailboxes": {
          "get": {
            "tags": ["Mailboxes (FreeScout)"],
            "summary": "List mailboxes",
            "description": "Standard FreeScout endpoint. Returns all mailboxes accessible with your API key. Use the returned `id` values as `mailboxId` when creating or filtering organizations.",
            "operationId": "listMailboxes",
            "parameters": [
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": {
                  "application/json": {
                    "schema": {
                      "type": "object",
                      "properties": {
                        "_embedded": {
                          "type": "object",
                          "properties": {
                            "mailboxes": {
                              "type": "array",
                              "items": { "$ref": "#/components/schemas/Mailbox" }
                            }
                          }
                        }
                      }
                    },
                    "example": {
                      "_embedded": {
                        "mailboxes": [
                          { "id": 1, "name": "Support", "email": "support@example.com", "createdAt": "2024-01-01T00:00:00+00:00", "updatedAt": "2024-01-01T00:00:00+00:00" },
                          { "id": 2, "name": "Sales",   "email": "sales@example.com",   "createdAt": "2024-01-01T00:00:00+00:00", "updatedAt": "2024-01-01T00:00:00+00:00" }
                        ]
                      }
                    }
                  }
                }
              }
            }
          }
        },

        "/api/mailboxes/{id}": {
          "get": {
            "tags": ["Mailboxes (FreeScout)"],
            "summary": "Get mailbox by ID",
            "description": "Standard FreeScout endpoint.",
            "operationId": "getMailbox",
            "parameters": [
              { "name": "id",      "in": "path",  "required": true, "schema": { "type": "integer" }, "description": "Mailbox ID" },
              { "name": "api_key", "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/Mailbox" } } }
              },
              "404": { "description": "Mailbox not found" }
            }
          }
        },

        "/api/customers/{customerId}": {
          "get": {
            "tags": ["Customers (FreeScout)"],
            "summary": "Get customer by ID",
            "description": "Standard FreeScout endpoint.",
            "operationId": "getCustomer",
            "parameters": [
              { "name": "customerId", "in": "path", "required": true, "schema": { "type": "integer" }, "description": "FreeScout customer ID" },
              { "name": "api_key",    "in": "query", "schema": { "type": "string" }, "description": "API key (alternative to header)" }
            ],
            "responses": {
              "200": {
                "description": "Success",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/Customer" } } }
              },
              "404": { "description": "Customer not found" }
            }
          }
        }

      }
    };
    </script>

    <script src="https://cdn.redoc.ly/redoc/latest/bundles/redoc.standalone.js"></script>
    <script>
        Redoc.init(spec, {
            scrollYOffset: 0,
            hideDownloadButton: true,
            expandResponses: "200,201",
            theme: {
                colors: { primary: { main: '#2e6da4' } },
                typography: { fontSize: '14px', fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif' },
                sidebar: { width: '260px' }
            }
        }, document.getElementById('redoc-container'));
    </script>
</body>
</html>
