# OrgPortal REST API

[← Powrót do README](../README.pl.md)

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

*Opcjonalnie — wymaga modułu [API i Webhooks](https://freescout.net/module/api-webhooks/).*

Uwierzytelnianie — nagłówek `X-FreeScout-API-Key` lub parametr zapytania `api_key`.

> **Dokumentacja interaktywna** (ReDoc) jest dostępna na stronie **Zarządzanie → API i Webhooks** (link "Dokumentacja API OrgPortal") lub bezpośrednio na `/orgportal/admin/api-docs`.

## Punkty końcowe

| Metoda | Punkt końcowy | Opis |
|--------|--------------|------|
| `GET` | `/api/organizations` | Lista organizacji (paginacja, filtr skrzynki) |
| `POST` | `/api/organizations` | Utwórz organizację |
| `GET` | `/api/organizations/{id}` | Pobierz organizację z członkami i jednostkami |
| `PUT` | `/api/organizations/{id}` | Aktualizuj organizację |
| `DELETE` | `/api/organizations/{id}` | Usuń organizację |
| `GET` | `/api/organizations/{id}/units` | Lista jednostek strukturalnych |
| `POST` | `/api/organizations/{id}/units` | Utwórz jednostkę strukturalną |
| `PUT` | `/api/units/{unitId}` | Zmień nazwę jednostki |
| `DELETE` | `/api/units/{unitId}` | Usuń jednostkę (członkowie nieprzypisani, menedżerowie zdegradowani) |
| `GET` | `/api/customers/{id}/organization` | Członkostwo organizacji klienta |
| `PUT` | `/api/customers/{id}/organization` | Ustaw/aktualizuj członkostwo klienta |
| `DELETE` | `/api/customers/{id}/organization` | Usuń klienta z organizacji |

## Kody odpowiedzi

| Kod | Znaczenie |
|-----|-----------|
| `200` | Sukces lub brak zmian (nic się nie zmieniło) |
| `201` | Zasób utworzony; nagłówek `Resource-ID` zawiera ID |
| `400` | Błąd walidacji — szczegóły w `_embedded.errors` |
| `401` | Nieprawidłowy lub brakujący klucz API |
| `404` | Zasób nie znaleziony |
| `409` | Konflikt — klient już ma aktywne członkostwo w innej organizacji |

---

## Organizacje

### GET /api/organizations

**Parametry zapytania**

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|:--------:|--------|
| `page` | integer | `1` | Numer strony |
| `pageSize` | integer | `25` | Rekordów na stronę (max 100) |
| `mailboxId` | integer | — | Filtr skrzynki: zwraca organizacje globalne + powiązane z tą skrzynką |

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

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|------|-----|:--------:|--------|
| `name` | string | ✅ | Nazwa organizacji (max 255 znaków, unikatowa) |
| `mailboxId` | integer\|null | — | ID skrzynki lub `null` / pomiń dla organizacji globalnej |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(nagłówek `Resource-ID: 1`)*
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

Zwraca organizację z wbudowanymi **członkami** i **jednostkami**.

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

**Pola członka**

| Pole | Typ | Opis |
|------|-----|--------|
| `unitId` | integer\|null | Jednostka strukturalna, do której należy członek, lub `null` dla całej organizacji |
| `role` | string | `member` lub `manager` |
| `canManageOrg` | boolean | Czy menedżer może promować innych do globalnego menedżera z portalu |
| `isActive` | boolean | Aktywne członkostwo; nieaktywni członkowie nie otrzymują przypisań zgłoszeń ani powiadomień |
| `notifyOnNewTicket` | boolean | Starszy flagowy sygnał powiadomień o nowych zgłoszeniach na członka |

---

### PUT /api/organizations/{id}

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|------|-----|:--------:|--------|
| `name` | string | ✅ | Nowa nazwa organizacji (max 255 znaków, unikatowa) |
| `mailboxId` | integer\|null | — | Nowa skrzynka; `null` — uczynić globalną; pomiń — pozostać bez zmian |

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

Gdy nic się nie zmienia, wiadomość odpowiedzi to `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(wszyscy członkowie zostają usunięci kaskadowo)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Jednostki strukturalne

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

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|------|-----|:--------:|--------|
| `name` | string | ✅ | Nazwa jednostki (unikatowa w ramach organizacji) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(nagłówek `Resource-ID: 2`)*
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

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|------|-----|:--------:|--------|
| `name` | string | ✅ | Nowa nazwa jednostki (unikatowa w ramach organizacji) |

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

Usuwa jednostkę. Menedżerowie przydzieleni do tej jednostki są degradowani do `member`; wszyscy członkowie jednostki zostają nieprzypisani (ich `unitId` staje się `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Członkostwo klienta

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

Przypisuje klienta do organizacji lub aktualizuje jego członkostwo. **Jedno aktywne członkostwo na klienta**: jeśli klient już ma *aktywne* członkostwo w *innej* organizacji, żądanie jest odrzucane z `409 Conflict`. Aby przenieść — najpierw zdezaktywuj lub usuń bieżące członkostwo poprzez `DELETE`.

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|------|-----|:--------:|--------|
| `organizationId` | integer | ✅ | ID organizacji |
| `role` | string | — | `"member"` (domyślnie) lub `"manager"` |
| `unitId` | integer\|null | — | Jednostka strukturalna (musi należeć do docelowej organizacji), lub `null` dla całej organizacji |
| `canManageOrg` | boolean | — | Przyznaj temu menedżerowi prawo promocji innych do menedżera globalnego (domyślnie `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nowe członkostwo)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(członkostwo zaktualizowane)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(klient już aktywny w innej organizacji)*
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
