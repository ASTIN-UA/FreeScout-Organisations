# OrgPortal REST API

[← Back to README](../../README.md)

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

*Opcjonalne — wymaga modułu [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Uwierzytelnianie — nagłówek `X-FreeScout-API-Key` lub parametr zapytania `api_key`.

> **Dokumentacja interaktywna** (ReDoc) jest dostępna na stronie **Zarządzaj → API i Webhooks** (link "OrgPortal API Docs") lub bezpośrednio na `/orgportal/admin/api-docs`.

## Punkty końcowe

| Metoda | Punkt końcowy | Opis |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Wyświetl organizacje (paginacja, filtr skrzynki pocztowej) |
| `POST` | `/api/organizations` | Utwórz organizację |
| `GET` | `/api/organizations/{id}` | Uzyskaj organizację z członkami i jednostkami |
| `PUT` | `/api/organizations/{id}` | Zaktualizuj organizację (nazwa, kolor, skrzynka pocztowa, isActive) |
| `DELETE` | `/api/organizations/{id}` | Usuń organizację |
| `GET` | `/api/organizations/{id}/members` | Wyświetl członków organizacji |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Uzyskaj jednego członka |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Zaktualizuj członka (rola, jednostka, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Usuń członka |
| `GET` | `/api/organizations/{id}/tags` | Wyświetl powiązania tagów (wymaga modułu Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Zastąp wszystkie powiązania tagów (wymaga modułu Tags) |
| `GET` | `/api/organizations/{id}/units` | Wyświetl jednostki strukturalne |
| `POST` | `/api/organizations/{id}/units` | Utwórz jednostkę strukturalną |
| `PUT` | `/api/units/{unitId}` | Zmień nazwę jednostki |
| `DELETE` | `/api/units/{unitId}` | Usuń jednostkę (członkowie nieprzypisani, menedżerowie jednostki obniżeni w stopniu) |
| `GET` | `/api/customers/{id}/organization` | Przynależność klienta do organizacji |
| `PUT` | `/api/customers/{id}/organization` | Ustaw/zaktualizuj przynależność klienta |
| `DELETE` | `/api/customers/{id}/organization` | Usuń klienta z organizacji |

## Kody odpowiedzi

| Kod | Znaczenie |
|------|---------|
| `200` | Sukces |
| `201` | Zasób utworzony; nagłówek `Resource-ID` zawiera identyfikator |
| `400` | Błąd walidacji — szczegóły w `_embedded.errors` |
| `401` | Nieprawidłowy lub brakujący klucz API |
| `404` | Nie znaleziono zasobu |
| `409` | Konflikt — klient ma już aktywną przynależność w innej organizacji |
| `422` | Naruszenie reguły biznesowej — np. usunięcie organizacji, która wciąż ma członków lub bilety |
| `503` | Wymagany moduł (np. Tags) nie jest aktywny |

---

## Organizacje

### GET /api/organizations

**Parametry zapytania**

| Parametr | Typ | Domyślnie | Opis |
|-----------|------|:-------:|-------------|
| `page` | liczba całkowita | `1` | Numer strony |
| `pageSize` | liczba całkowita | `25` | Rekordy na stronie (max 100) |
| `mailboxId` | liczba całkowita | — | Filtr skrzynki pocztowej: zwraca organizacje globalne + te powiązane z tą skrzynką |

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

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|-------|------|:--------:|-------------|
| `name` | ciąg | ✅ | Nazwa organizacji (max 255 znaków, unikalna) |
| `mailboxId` | liczba całkowita\|null | — | Identyfikator skrzynki pocztowej lub `null` / pomiń dla organizacji globalnej |

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
  "color": null,
  "isActive": true,
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

**Pola członka**

| Pole | Typ | Opis |
|-------|------|-------------|
| `unitId` | liczba całkowita\|null | Jednostka strukturalna, do której należy członek, lub `null` dla całej organizacji |
| `role` | ciąg | `"member"` lub `"manager"`. **Menedżer jednostki** to `role: "manager"` z niezerowym `unitId`; **menedżer globalny** to `role: "manager"` z `unitId: null`. String `"unit_manager"` nie istnieje w API — jego przekazanie zwraca 400. |
| `canManageOrg` | logiczny | Czy ten menedżer może promować innych do menedżera globalnego z portalu |
| `isActive` | logiczny | Aktywne członkostwo; nieaktywni członkowie nie otrzymują przypisań biletów ani powiadomień |
| `notifyOnNewTicket` | logiczny | Flaga powiadomienia o nowym bilecie na członka |

---

### PUT /api/organizations/{id}

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|-------|------|:--------:|-------------|
| `name` | ciąg | ✅ | Nowa nazwa organizacji (max 255 znaków, unikalna) |
| `color` | ciąg\|null | — | Kolor odznaki jako heksadecymalny (`"#ff0000"`), `null` do resetowania na domyślny szary; pomiń aby zachować bieżący |
| `mailboxId` | liczba całkowita\|null | — | Nowa skrzynka pocztowa; `null` — uczyń globalną; pomiń — pozostaw bez zmian |
| `isActive` | logiczny | — | `false` aby dezaktywować organizację; pomiń aby zachować bieżący |

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

Zablokowany, gdy organizacja ma aktywnych członków lub bilety. Najpierw usuń wszystkich członków i przypisz ponownie/usuń wszystkie bilety.

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

## Członkowie organizacji

### GET /api/organizations/{id}/members

Zwraca listę wszystkich rekordów członków dla organizacji.

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

Zwraca pojedynczy rekord członka.

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

Zaktualizuj rolę, przypisanie jednostki, flagę canManageOrg lub status aktywny członka. Tylko pola obecne w treści są aktualizowane (aktualizacja częściowa).

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|-------|------|:--------:|-------------|
| `role` | ciąg | — | `"member"` lub `"manager"`. Aby utworzyć **menedżera jednostki**: `role: "manager"` + `unitId: <id>`. Aby utworzyć **menedżera globalnego**: `role: "manager"` + `unitId: null`. |
| `unitId` | liczba całkowita\|null | — | Jednostka strukturalna (musi należeć do tej organizacji), lub `null` aby przypisać |
| `canManageOrg` | logiczny | — | Udziel praw menedżera globalnego w portalu |
| `isActive` | logiczny | — | `false` aby dezaktywować bez usuwania |

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

Usuń członka z organizacji.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Tagi organizacji

> Wymaga, aby moduł [Tags](https://freescout.net/module/tags/) był aktywny. Zwraca `503` jeśli moduł nie jest zainstalowany.

### GET /api/organizations/{id}/tags

Zwraca wszystkie powiązania tagów dla organizacji. Każde powiązanie opcjonalnie ogranicza tag do określonej jednostki.

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

**Pełna zamiana** — zastępuje wszystkie istniejące powiązania tagów dla tej organizacji podaną listą. Wyślij pustą tablicę `[]` aby usunąć wszystkie powiązania.

**Treść żądania** — tablica JSON obiektów powiązań tagów:

| Pole | Typ | Wymagane | Opis |
|-------|------|:--------:|-------------|
| `tagId` | liczba całkowita | ✅ | Identyfikator tagu FreeScout |
| `unitId` | liczba całkowita\|null | — | Ogranicz tag do określonej jednostki, lub pomiń/`null` dla całej organizacji |

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
|-------|------|:--------:|-------------|
| `name` | ciąg | ✅ | Nazwa jednostki (unikalna w organizacji) |

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
|-------|------|:--------:|-------------|
| `name` | ciąg | ✅ | Nowa nazwa jednostki (unikalna w organizacji) |

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

Usuń jednostkę. Menedżerowie ograniczeni do tej jednostki są obniżani do `member`; wszyscy członkowie jednostki są nieprzypisani (ich `unitId` staje się `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Przynależność klienta

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

Przypisz klienta do organizacji lub zaktualizuj jego przynależność. **Jedno aktywne członkostwo na klienta**: jeśli klient ma już *aktywne* członkostwo w *innej* organizacji, żądanie jest odrzucane z `409 Conflict`. Aby przenieść — najpierw dezaktywuj lub usuń bieżące członkostwo za pomocą `DELETE`.

**Treść żądania**

| Pole | Typ | Wymagane | Opis |
|-------|------|:--------:|-------------|
| `organizationId` | liczba całkowita | ✅ | Identyfikator organizacji |
| `role` | ciąg | — | `"member"` (domyślnie) lub `"manager"` |
| `unitId` | liczba całkowita\|null | — | Jednostka strukturalna (musi należeć do docelowej organizacji), lub `null` dla całej organizacji |
| `canManageOrg` | logiczny | — | Udziel temu menedżerowi prawa promowania innych do menedżera globalnego (domyślnie `false`) |
| `isActive` | logiczny | — | `false` aby utworzyć/zaktualizować jako nieaktywny (domyślnie `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nowe członkostwo)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(członkostwo zaktualizowane)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(klient jest już aktywny w innej organizacji)*
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
