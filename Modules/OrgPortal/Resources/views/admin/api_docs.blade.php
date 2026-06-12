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
        "version": "1.2.0",
        "description": "REST API for managing organizations in FreeScout via the **OrgPortal** module.\n\n## Authentication\n\nPass your API key using **one** of these methods:\n\n| Method | Example |\n|--------|---------|\n| Header | `X-FreeScout-API-Key: YOUR_KEY` |\n| Query param | `?api_key=YOUR_KEY` |\n\nYour API key is available at **Manage → API & Webhooks**.\n\n## Response conventions\n\n| Code | Meaning |\n|------|---------|\n| `200` | Success — response body always present |\n| `201` | Resource created — body contains the new object, `Resource-ID` header contains its ID |\n| `400` | Validation error — see `_embedded.errors` for field-level details |\n| `401` | Invalid or missing API key |\n| `404` | Resource not found |\n| `409` | Conflict — e.g. customer already belongs to another organization |"
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
              "name":      { "type": "string",  "example": "Acme Corp" },
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
              "name":           { "type": "string",  "example": "Sales department" },
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
              "role":              { "type": "string", "enum": ["member", "manager"], "example": "manager" },
              "canManageOrg":      { "type": "boolean", "example": false, "description": "Whether this (global) manager may promote others to global manager from the portal. Admin-granted only." },
              "isActive":          { "type": "boolean", "example": true, "description": "Deactivated (`false`) members keep their ticket history but can no longer be assigned as a ticket author." },
              "notifyOnNewTicket": { "type": "boolean", "example": true },
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
              "role":              { "type": "string", "enum": ["member", "manager"], "example": "manager" },
              "canManageOrg":      { "type": "boolean", "example": false },
              "isActive":          { "type": "boolean", "example": true },
              "notifyOnNewTicket": { "type": "boolean", "example": true }
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
              "message":   { "type": "string", "example": "Customer already belongs to another organization." },
              "errorCode": { "type": "string", "example": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION." },
              "_embedded": {
                "type": "object",
                "properties": {
                  "errors": {
                    "type": "array",
                    "items": {
                      "type": "object",
                      "properties": {
                        "path":    { "type": "string", "example": "organizationId" },
                        "message": { "type": "string", "example": "Customer is already a member of organization #3. Remove the existing membership first via DELETE /api/customers/42/organization." },
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
        { "name": "Customer Membership",   "description": "Assign customers to organizations and manage their roles" },
        { "name": "Customers (FreeScout)", "description": "Standard FreeScout endpoints for looking up customers. Use these to obtain a `customerId` before membership operations." },
        { "name": "Mailboxes (FreeScout)", "description": "Standard FreeScout endpoints for listing mailboxes. Use these to obtain a `mailboxId` for scoping organizations or filtering results." }
      ],
      "paths": {

        "/api/organizations": {
          "get": {
            "tags": ["Organizations"],
            "summary": "List organizations",
            "operationId": "listOrganizations",
            "parameters": [
              { "name": "page",      "in": "query", "schema": { "type": "integer", "default": 1 },  "description": "Page number" },
              { "name": "pageSize",  "in": "query", "schema": { "type": "integer", "default": 25, "maximum": 100 }, "description": "Items per page (max 100)" },
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
                      "name":      { "type": "string",  "maxLength": 255, "example": "Acme Corp", "description": "Organization name — must be unique, max 255 characters" },
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
                "description": "Validation error (empty name or duplicate)",
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
            "description": "Updates the organization name and/or mailbox scope. Send only the fields you want to change — `name` is always required. To clear the mailbox scope (make the org global), pass `\"mailboxId\": null`.",
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
                      "name":      { "type": "string",  "maxLength": 255, "example": "Acme Corporation", "description": "New organization name — must be unique" },
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
            "description": "Deletes the organization and cascades to all its members.",
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
              "content": { "application/json": { "schema": { "type": "object", "required": ["name"], "properties": { "name": { "type": "string", "example": "Sales department" } } } } }
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
              "content": { "application/json": { "schema": { "type": "object", "required": ["name"], "properties": { "name": { "type": "string", "example": "Renamed unit" } } } } }
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
            "description": "Assigns a customer to an organization or updates their role/unit within an organization.\n\nEach customer can have **at most one active** membership. If the customer is already an **active** member of a **different** organization, the request is rejected with `409 Conflict`. Deactivate or remove the existing membership first via `DELETE /api/customers/{customerId}/organization`.\n\nSet `role = manager` with a `unitId` to create a **unit manager** (sees only that unit's tickets), or with `unitId = null` to create a **global manager** (sees the whole organization).",
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
                      "organizationId": { "type": "integer", "example": 1, "description": "Target organization ID" },
                      "unitId": { "type": "integer", "nullable": true, "example": 7, "description": "Structural unit within the organization, or `null` for none. Must belong to `organizationId`." },
                      "role": {
                        "type": "string",
                        "enum": ["member", "manager"],
                        "default": "member",
                        "description": "`member` — regular member. `manager` — with a `unitId` sees that unit's tickets; with `unitId = null` is a global manager that sees all organization tickets and receives new-ticket notifications."
                      },
                      "canManageOrg": { "type": "boolean", "default": false, "description": "Grant a global manager the right to promote others to global manager from the portal." }
                    }
                  }
                }
              }
            },
            "responses": {
              "200": {
                "description": "Membership updated (role changed or no-op)",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/SuccessResponse" }, "example": { "success": true, "message": "Membership updated." } } }
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
                "description": "Customer not found or not a member of any organization",
                "content": { "application/json": { "schema": { "$ref": "#/components/schemas/NotFound" } } }
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
