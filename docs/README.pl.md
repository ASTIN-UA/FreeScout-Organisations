# OrgPortal — Portal organizacji dla FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Moduł FreeScout, który dodaje koncepcję **Organizacji** (firmy/zespoły) do klientów, rozszerza Portal End-User dla menedżerów i wyświetla odznakę organizacji na zgłoszeniach i kartach Kanban.

**Minimalna wersja FreeScout:** 1.8.147  
**Zależności:** brak wymaganych  
**Opcjonalne:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API i Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Język:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funkcje

### Zarządzanie organizacjami (admin)
- **Zarządzanie → Organizacje** — pełny CRUD: tworzenie, edycja, usuwanie organizacji
- **Powiązanie ze skrzynką** — organizacja może być **globalna** (widoczna we wszystkich skrzynkach) lub **powiązana z konkretną skrzynką**; odpowiednia etykieta wyświetla się na liście organizacji
- Przypisz klientów do organizacji z wyborem roli: `Członek` lub `Menedżer`
- **Zmiana roli członka** bezpośrednio w tabeli (bez usuwania i ponownego dodawania)
- Wyszukiwanie klienta z autouzupełnianiem według nazwy lub e-maila; klienci już w organizacji są wykluczeni z wyników
- E-mail członka wyświetla się pod nazwą w tabeli członków
- Jeden klient — jedna organizacja (wymuszane na poziomie bazy danych i API)
- **Kolor odznaki** — wizualna paleta z 12 kolorami w formularzu edycji organizacji; kolor domyślny to szary

### Uprawnienia użytkownika
- Nowe uprawnienie **"Zezwól na zarządzanie organizacjami"** — nie-administratorzy z tym uprawnieniem mają dostęp do stron listy, tworzenia i edycji organizacji
- Usuwanie organizacji pozostaje zastrzeżone dla administratorów

### Karta klienta
- Pole **Organizacja** w formularzu edycji klienta — wybierz organizację i rolę
- Przycisk **Zgłoszenia organizacji** — otwiera wyszukiwanie wszystkich zgłoszeń organizacji

### Odznaka organizacji na zgłoszeniach
- Wyświetlana pod tematem na stronie zgłoszenia i na liście rozmów
- Klikalna — otwiera wyszukiwanie wszystkich zgłoszeń tej organizacji
- Kolor odznaki określony jest ustawieniami organizacji (domyślnie szary)
- Włączenie/wyłączenie **na skrzynkę** za pośrednictwem **Ustawienia skrzynki → OrgPortal**; wartość globalna jest używana jako zapaśnik

### Odznaka organizacji na kartach Kanban
- Wyświetlana za licznikiem wiadomości na każdej karcie
- Klikalna — prowadzi do wyszukiwania organizacji
- Kolor odpowiada ustawieniom organizacji
- Filtr **Organizacja** wbudowany w standardowe rozwijane menu filtrów Kanban: modal z polami wyboru, podobnie do filtru Tagi; stan jest zachowywany podczas nawigacji
- Włączenie/wyłączenie **na skrzynkę** za pośrednictwem **Ustawienia skrzynki → OrgPortal**

### Filtr wyszukiwania organizacji
- Rozszerza standardowe wyszukiwanie FreeScout o filtr **Organizacja**
- Wyświetla wszystkie zgłoszenia klientów należących do wybranej organizacji

### Portal End-User — dostęp menedżerów *(opcjonalny)*

Menedżer organizacji ma rozszerzony dostęp poprzez EUP:

- Pozycja **Zgłoszenia firmy** w nawigacji portalu
- Tabela zgłoszeń firmy z kolumnami:
  - **#** i **Temat** z obcięciem elipsy i tooltipem po najechaniu myszą
  - **Odpowiedzialny** — przydzielony agent
  - **Autor** — klient, który otworzył zgłoszenie; kliknięcie filtruje zgłoszenia po autorze w organizacji
  - **Stan** — Aktywny / Oczekujący / Zamknięty / Spam z ikonami
  - **Status** — nazwa kolumny Kanban (z niestandardową etykietą, jeśli skonfigurowana); wyświetlane tylko gdy moduł Kanban jest aktywny
  - **Zaktualizowano** — data i godzina ostatniej odpowiedzi
- Wyszukiwanie w temacie zgłoszenia
- Filtrowanie po statusie Kanban (konfigurowalne za pośrednictwem **Ustawienia skrzynki → OrgPortal**)
- Odpowiedź na zgłoszenie z obsługą **Załączników** (Drag & Drop, wiele plików)
- **Zamknij zgłoszenie** — menedżer może zamknąć zgłoszenie; nowa odpowiedź je automatycznie otworzy
- Zmiana autora zgłoszenia — przypisanie zgłoszenia innemu członkowi organizacji
- Strona **Ustawienia org.** do konfiguracji powiadomień e-mail
- Dostęp do zgłoszeń jest **ściśle ograniczony do bieżącej skrzynki** (organizacja skopiowana do innej skrzynki — portal 403)

### Powiadomienia e-mail *(opcjonalne)*
- Menedżerowie z włączoną opcją otrzymują e-mail, gdy członek organizacji utworzy nowe zgłoszenie
- Używa sterownika poczty odpowiadającej skrzynce

### Ustawienia skrzynki

**Ustawienia skrzynki → OrgPortal** (na skrzynkę):

| Opcja | Opis |
|-------|------|
| Pokaż odznaką na stronie zgłoszenia | Włączenie/wyłączenie odznaki w tej skrzynce |
| Pokaż odznaką na kartach Kanban | Włączenie/wyłączenie odznaki w tej skrzynce |
| Filtry statusu zgłoszeń firmy | Wybierz kolumny Kanban wyświetlane jako pola wyboru na stronie zgłoszeń; niestandardowa etykieta dla każdego filtru |

---

### REST API *(opcjonalne, wymaga API i Webhooks)*

OrgPortal udostępnia pełne REST API do zarządzania organizacjami, jednostkami strukturalnymi i członkostwami klientów — uwierzytelnianie za pomocą nagłówka `X-FreeScout-API-Key` lub parametru zapytania `api_key`.

📖 **Pełna dokumentacja API → [docs/api/README.pl.md](api/README.pl.md)** (wszystkie punkty końcowe, przykłady żądań/odpowiedzi, kody błędów)

Interaktywna dokumentacja ReDoc jest również dostępna w **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Instalacja

1. Skopiuj folder `OrgPortal` do `Modules/` twojego FreeScout
2. W panelu admin: **Zarządzanie → Moduły → OrgPortal → Aktywuj**
3. Uruchom migracje:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Wyczyść pamięć cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Aktualizacje

OrgPortal obsługuje **automatyczne aktualizacje** poprzez wbudowany mechanizm aktualizacji modułów FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Gdy dostępna jest nowa wersja, banner pojawia się na stronie **Zarządzanie → Moduły**. Kliknij **Zaktualizuj teraz** — FreeScout automatycznie pobierze i zainstaluje najnowszą wersję.

Nie jest wymagane ręczne kopiowanie plików.

---

## Kompatybilność modułów

| Moduł | Status |
|-------|--------|
| End-User Portal ≥ 1.0.85 | Opcjonalny — funkcje portalu dla menedżerów |
| API i Webhooks ≥ 1.0.80 | Opcjonalny — punkty końcowe REST API |
| Kanban ≥ 1.0.23 | Opcjonalny — odznaka, filtr, kolumna "Status" w zgłoszeniach firmy |
| Niestandardowe pola | Kompatybilne |
| Przepływy pracy | Kompatybilne |
| Tagi | Kompatybilne |

---

## Konfiguracja

### Globalna (**Zarządzanie → Ustawienia OrgPortal**)

| Opcja | Domyślnie |
|-------|----------|
| Pokaż odznaką na stronie zgłoszenia | ✅ |
| Pokaż odznaką na kartach Kanban | ✅ |

### Na skrzynkę (**Ustawienia skrzynki → OrgPortal**)

Zastępuje wartości globalne dla konkretnej skrzynki.

| Opcja | Opis |
|-------|------|
| Pokaż odznaką na stronie zgłoszenia | Odznaka na liście rozmów i na stronie zgłoszenia |
| Pokaż odznaką na kartach Kanban | Odznaka na kartach Kanban |
| Filtry statusu zgłoszeń firmy | Kolumny Kanban jako pola wyboru na stronie Zgłoszenia firmy; każdy filtr ma niestandardową etykietę widoczną dla użytkowników portalu |

---

## Tłumaczenia

Obsługiwane języki: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Русский** (`ru`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Pliki: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integracja EUPSWLANG

Moduł prawidłowo pracuje z [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): język wybrany w portalu stosuje się również do ciągów OrgPortal.

Aby język pojawił się na liście EUPSWLANG, musi istnieć plik `Modules/EndUserPortal/Resources/lang/{locale}.json`. Pliki dla **Română** (`ro`) znajdują się w pakiecie; **Georgian** (`ka`) jest obsługiwany tylko w strefie administracyjnej (bez wsparcia systemowego w rdzeniu FreeScout).

> **Szczegół techniczny:** Middleware `ReapplyEupLocale` (zarejestrowany ostatni w grupie tras portalu) przywraca ustawienie lokalne po tym, jak middleware `Localize` FreeScout w przeciwnym razie resetowałby je do domyślnego języka systemowego.

---

## Licencja

[MIT](../LICENSE) — © 2026 ASTIN-UA
