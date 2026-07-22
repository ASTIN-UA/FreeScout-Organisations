# OrgPortal — Moduł zarządzania organizacjami B2B dla FreeScout

[← Powrót do README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — moduł B2B dla FreeScout" width="140" align="right">

**OrgPortal** to moduł FreeScout, który dodaje pełne **zarządzanie organizacjami B2B** do Twojego helpdesku: grupuj klientów w firmy, definiuj hierarchie działów, daj menedżerom korporacyjnym portal samoobsługowy i automatyzuj powiadomienia — wszystko wewnątrz FreeScout, bez żadnych zewnętrznych narzędzi.

> Szukasz sposobu na zarządzanie kontami firmowymi w FreeScout? Na zapewnienie klientom korporacyjnym własnego portalu wsparcia? Na kontrolowanie, które zgłoszenia może widzieć każdy kontakt B2B na podstawie jego roli i działu? OrgPortal rozwiązuje to wszystko.

**Współpracuje z:** FreeScout 1.8.147+  
**Opcjonalne integracje:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/), [Custom Fields](https://freescout.net/module/custom-fields/)

> [!IMPORTANT]
> **Zawsze instaluj z [najnowszego wydania](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), a nie z kodu źródłowego repozytorium.**
> Pobierz `OrgPortal.zip` ze strony Releases — zawiera poprawną strukturę katalogów wymaganą przez FreeScout.
> Pobieranie kodu źródłowego (przez "Code → Download ZIP" lub `git clone`) **nie zadziała** i zniszczy strukturę modułu.
> Automatyczne aktualizacje również wymagają użycia ZIP wydania podczas instalacji początkowej.

---

🌐 **Dostępne również w:**
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

## Spis treści

- [Co OrgPortal dodaje do FreeScout](#co-orgportal-dodaje-do-freescout)
- [Organizacje](#organizacje)
- [Jednostki strukturalne — Kontrola dostępu na poziomie działu](#jednostki-strukturalne--kontrola-dostępu-na-poziomie-działu)
- [Domeny e-mail — Automatyczne członkostwo](#domeny-e-mail--automatyczne-członkostwo)
- [Org Snapshot — Trwałe przypisanie zgłoszeń](#org-snapshot--trwałe-przypisanie-zgłoszeń)
- [Integracja z Kanban](#integracja-z-kanban)
- [Integracja z polami niestandardowymi](#integracja-z-polami-niestandardowymi)
- [Kontrola dostępu i uprawnienia](#kontrola-dostępu-i-uprawnienia)
- [Ustawienia systemowe](#ustawienia-systemowe--manage--organizations--system-tab)
- [End-User Portal — Samoobsługa dla menedżerów korporacyjnych](#end-user-portal--samoobsługa-dla-menedżerów-korporacyjnych-opcjonalne)
- [Dzwonek powiadomień w czasie rzeczywistym](#dzwonek-powiadomień-w-czasie-rzeczywistym-opcjonalne)
- [Subskrypcje powiadomień](#subskrypcje-powiadomień-opcjonalne)
- [Ustawienia organizacji w portalu](#ustawienia-organizacji-w-portalu)
- [Wielojęzyczne szablony e-mail powiadomień](#wielojęzyczne-szablony-e-mail-powiadomień-opcjonalne)
- [REST API](#rest-api-opcjonalne)
- [Instalacja](#instalacja)
- [Automatyczne aktualizacje](#automatyczne-aktualizacje)
- [Kompatybilność modułów](#kompatybilność-modułów)
- [Konfiguracja](#konfiguracja)
- [Tłumaczenia](#tłumaczenia)
- [Zrzuty ekranu](#zrzuty-ekranu)
- [Licencja](#licencja)

---

## Co OrgPortal dodaje do FreeScout

FreeScout jest zbudowany wokół indywidualnych klientów — każda wiadomość e-mail pochodzi od osoby i nie ma wbudowanej koncepcji firmy, w której ta osoba pracuje. Sprawdza się to dobrze w helpdesku B2C. W B2B zawodzi.

OrgPortal wypełnia tę lukę:

- **Konta firmowe** — grupuj klientów w organizacje z nazwą, kolorową odznaką, powiązaniem ze skrzynką i statusem aktywny/nieaktywny
- **Automatyczne członkostwo przez domenę e-mail** — powiąż `company.com` z organizacją i każdy klient piszący z tej domeny zostanie automatycznie zarejestrowany i przypisany
- **Hierarchie działów** — dziel organizacje na jednostki strukturalne (działy, oddziały, zespoły); każdy członek jest przypisany do swojej jednostki
- **Dostęp oparty na rolach** — `member` widzi tylko własne zgłoszenia; `unit_manager` widzi całą jednostkę; `manager` widzi całą organizację
- **Korporacyjny portal samoobsługowy** — menedżerowie przeglądają wszystkie zgłoszenia firmy, odpowiadają, zamykają, zmieniają autorów i zarządzają preferencjami powiadomień bez kontaktowania się z Twoim zespołem
- **Trwałe przypisanie zgłoszeń** — każde zgłoszenie jest trwale przypisane do organizacji w momencie utworzenia; historyczne raporty przeżywają zmiany w składzie klientów
- **Wielojęzyczne powiadomienia** — automatyczne alerty e-mail w języku każdego menedżera, z szablonami dla każdego języka i wbudowanym edytorem WYSIWYG
- **REST API** — synchronizuj członkostwo z Twoim CRM, automatyzuj wdrożenia, zarządzaj tagami programowo

---

## Organizacje

*Jedno miejsce dla wszystkiego o koncie korporacyjnym.*

**Manage → Organizations** otwiera interfejs z zakładkami z trzema sekcjami: Organizations, Templates i System.

### Lista organizacji

- **Tworzenie, edycja, usuwanie, aktywacja/dezaktywacja** organizacji
- **Filtr statusu** — przełączaj między Active / Inactive / All za pomocą grupy przycisków radiowych; natychmiastowe filtrowanie tabeli po stronie klienta
- **Wyszukiwanie na żywo** — filtrowanie zaczyna się od 2+ znaków, bez przeładowania strony
- **Kolorowe odznaki** — interaktywny wybór koloru z 12 próbkami i podglądem odznaki na żywo obok selektora; odznaka pojawia się na każdym zgłoszeniu i karcie Kanban
- Kliknięcie odznaki lub liczby zgłoszeń otwiera wyszukiwanie FreeScout przefiltrowane dla tej organizacji
- **Powiązanie ze skrzynką** — organizacje mogą być globalne (wszystkie skrzynki) lub przypisane do konkretnej skrzynki
- **Kolumna Tags** — pokazuje ✓/✗ czy jakiekolwiek tagi FreeScout są powiązane z organizacją (wymagany moduł Tags); tagi są przypisywane w formularzu edycji za pomocą widgetu z chipami i wyszukiwaniem z podpowiedziami
- **Kolumna liczby zgłoszeń** — łączna liczba rozmów na organizację; klikalny link do pełnych wyników wyszukiwania
- **Kolumna liczby członków**
- **Aktywacja / dezaktywacja** — zawieś konto bez utraty historii; wymaga włączenia Org Snapshot (przycisk jest wyłączony z podpowiedzią, gdy nie jest)
- **Usuwanie** — dostępne tylko gdy organizacja ma 0 członków i 0 zgłoszeń (zabezpieczenie)
- Wszystkie akcje usuwania i dezaktywacji wymagają potwierdzenia

![Lista organizacji — filtr statusu, wyszukiwanie na żywo, kolorowe odznaki, tagi, liczby zgłoszeń](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Formularz edycji organizacji

- **Nazwa** i **powiązanie ze skrzynką**
- **Wybór koloru** — 12 próbek z podglądem odznaki na żywo
- **Tagi** — widget z chipami: wpisz, aby wyszukać istniejące tagi FreeScout, kliknij, aby dodać, × aby usunąć
- **Tabela członków** — dla każdego członka: nazwa, rola, jednostka strukturalna, pole wyboru `can_manage_org` (przyznaje dostęp administracyjny do organizacji bez pełnych praw administratora), przełącznik aktywny/nieaktywny
- **Panel jednostek strukturalnych** — tworzenie i zmiana nazw jednostek bezpośrednio w formularzu edycji; członkowie są przypisywani do jednostek w tym samym widoku
- **Dodawanie członka** — automatycznie wypełnia istniejące nieprzypisane rozmowy dla tego klienta

![Edycja organizacji — wybór koloru, chipy tagów, tabela członków z rolami i jednostkami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integracja z profilem klienta

- **Pole organizacji w formularzu edycji klienta FreeScout** — wyszukiwanie z podpowiedziami organizacji na żywo; lista rozwijana roli pojawia się po wybraniu organizacji; przycisk × do usunięcia
- **Skrót "View org tickets"** w formularzu klienta
- **Blok informacji o organizacji na pasku bocznym zgłoszenia administratora** — nazwa organizacji (klikalny link do strony edycji organizacji), jednostka strukturalna i rola członka; przełączanie widoczności dla każdej skrzynki w ustawieniach
- **Jedno aktywne członkostwo na klienta** — klient nie może być dodany do drugiej organizacji, gdy ma aktywne członkostwo; nieaktywne/zarchiwizowane członkostwa są dozwolone

![Edycja klienta — pole organizacji z podpowiedziami i selektorem roli](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Jednostki strukturalne — Kontrola dostępu na poziomie działu

*Obsługa dużych przedsiębiorstw ze złożonymi wewnętrznymi hierarchiami.*

Organizacje mogą być podzielone na nieograniczoną liczbę **jednostek strukturalnych** (działy, oddziały, biura regionalne, zespoły projektowe):

- Tworzenie, zmiana nazw i usuwanie jednostek w formularzu edycji organizacji administratora lub bezpośrednio z portalu (tylko globalni menedżerowie)
- Przypisywanie członków do jednostek — każdy członek należy do jednej jednostki
- **Usunięcie jednostki** automatycznie obniża rolę jej członków `unit_manager` do `member`

**Trzy poziomy ról:**

| Rola | Zakres dostępu |
|------|----------------|
| `member` | Tylko własne zgłoszenia |
| `unit_manager` | Wszystkie zgłoszenia w swojej jednostce strukturalnej |
| `manager` (globalny) | Wszystkie zgłoszenia w całej organizacji |

- Menedżerowie jednostek mają pełne możliwości portalu — odpowiedzi, załączniki, zmiana autora, zamykanie/otwieranie, zarządzanie powiadomieniami — ograniczone ściśle do swojej jednostki
- Dostęp do zgłoszeń i dostarczanie powiadomień są egzekwowane na granicach jednostek

![Edycja organizacji — członkowie z rolami i jednostkami, panel zarządzania jednostkami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Domeny e-mail — Automatyczne członkostwo

*Zaprzestań ręcznego dodawania pracowników jednej firmy po jednym.*

Powiąż domenę e-mail z organizacją i każdy klient piszący z tej domeny zostanie przypisany i automatycznie zarejestrowany jako członek — żaden krok ręczny, nic do zapomnienia gdy nowa osoba po raz pierwszy wyśle e-mail.

Konfiguruje się dla każdej organizacji w **Manage → Organizations → Edit → Email Domains**.

### Jak działa dopasowanie

| Reguła | Zachowanie |
|--------|-----------|
| **Tylko dokładne dopasowanie** | `company.com` pasuje do `jane@company.com`. **Nie pasuje** do `jane@mail.company.com` czy `jane@www.company.com` — dodaj te osobno jeśli chcesz |
| **Normalizacja** | `@Company.COM`, `https://www.company.com/` i `company.com.` są wszystkie zapisywane jako `company.com` |
| **Ręczne przypisanie zawsze wygrywa** | Klient już należący do innej organizacji nigdy nie zostanie przeniesiony. Podwykonawcy i świadome decyzje admina są bezpieczne |
| **Dezaktywacja jest trwała** | Dezaktywowanie członka jest trwałe aż do wyraźnego cofnięcia. Klient może wysyłać dalej e-maile; automatyzacja nie przywróci dostępu |
| **Zakres skrzynki** | Domena w organizacji specyficznej dla skrzynki dotyczy tylko tej skrzynki. Powiązanie specyficzne dla skrzynki zastępuje powiązanie globalne dla tej samej domeny |
| **Wiele domen** | Organizacja może posiadać tyle domen ile potrzeba (`company.com`, `company.co.uk`, przejętą markę…) |

### Dostawcy publiczni są zablokowani

`gmail.com`, `outlook.com`, `ukr.net`, `icloud.com`, e-mail jednorazowy i podobne są **odrzucane przy zapisaniu**. Powiązanie wciągnęłoby setki niepowiązanych klientów do jednej organizacji i — poprzez End-User Portal — dałoby im dostęp do zgłoszeń innych osób.

Lista jest dostarczana z modułem i może być **rozszerzona** (nigdy zmniejszona) za pośrednictwem ustawienia `orgportal.public_domains_extra` dla dostawców regionalnych. Hardkodowany fallback gwarantuje, że duzi dostawcy pozostają zablokowani nawet jeśli plik konfiguracyjny brakuje lub jest uszkodzony.

Dezaktywowane organizacje nie rejestrują już klientów.

### Istniejący klienci

Powiązanie dotyczy tylko przyszłych e-maili: istniejący już klienci nie są rejestrowany wstecz. Zostaną dodani gdy tylko napiszą ponownie.

### Usuwanie powiązania

Usunięcie domeny zatrzymuje przyszłe automatyczne przypisywanie. Członkowie już przez nią utworzeni są **domyślnie zatrzymywani** — mogą już korzystać z portalu. Zostaniesz poproszony osobno czy chcesz ich dezaktywować; ten rollback dotyczy tylko członków zarejestrowanych przez tę konkretną domenę, nigdy tych dodanych ręcznie.

Automatycznie utworzeni członkowie są oznaczeni odznaką **@** na liście członków.

---

## Org Snapshot — Trwałe przypisanie zgłoszeń

*Niezawodne historyczne raportowanie nawet gdy skład klientów się zmienia.*

Gdy zgłoszenie jest tworzone, OrgPortal rejestruje kontekst organizacji jako trwały snapshot:

- `org_id`, `org_unit_id` i `org_attributed_at` są zapisywane do rozmowy w momencie utworzenia
- **Niezmienne** — jeśli klient później opuści organizację, jego historyczne zgłoszenia pozostają przypisane do tej organizacji; raportowanie nigdy nie zostaje przerwane
- **Dodanie członka** wyzwala automatyczne uzupełnienie istniejących nieprzypisanych rozmów tego klienta

### Źródło przypisania — trzy tryby

Konfigurowane w **Manage → Organizations → System tab**:

| Tryb | Zachowanie |
|------|------------|
| `member` | Przypisz zgłoszenie do organizacji, której członkiem jest autor zgłoszenia |
| `tag` | Przypisz najpierw według tagu FreeScout powiązanego z organizacją; użyj członkostwa jako rezerwy jeśli żaden tag nie pasuje |
| `tag_only` | Przypisuj wyłącznie według tagów; członkostwo nie jest używane |

Tryby `tag` i `tag_only` są wyłączone gdy moduł Tags jest nieaktywny.

**Domeny e-mail służą jako ostatnia rezerwa** w trybach `member` i `tag`: gdy ani powiązanie tagiem ani istniejące członkostwo nie rozwiąże zgłoszenia, sprawdzana jest domena e-mail autora. Nigdy nie zastępuje one siebie nawzajem, więc reguła tagu lub ręczne przypisanie admina zawsze ma pierwszeństwo. W trybie `tag_only` nie jest używane dopasowanie domeny.

### Narzędzia uzupełniania

- **Pasek postępu** — pokazuje X / Y przypisanych zgłoszeń (%) ze wskaźnikiem "complete" po zakończeniu
- **Statystyki wstępne** — przed uruchomieniem uzupełniania pokazuje ile zgłoszeń zostanie przypisanych przez tag vs. przez członkostwo vs. nieprzypisanych
- Przycisk **Uruchom uzupełnianie** — przetwarza do 2000 zgłoszeń na kliknięcie; po zakończeniu wyświetlane jest podsumowanie wyników (by_tag / by_member / unmatched)
- **Auto-cron** (`attribution_cron_enabled`) — planuje uzupełnianie co 5 minut, 1000 zgłoszeń na uruchomienie, bez nakładania się
- **Reset przypisania** — czyści wszystkie snapshoty organizacji (niebezpieczna akcja, wymaga potwierdzenia)
- Wiersz poleceń: `php artisan orgportal:backfill-attribution`

![Zakładka System — źródło przypisania, pasek postępu, statystyki wstępne, kontrolki uzupełniania](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Integracja z Kanban

*Utrzymuj wizualny przepływ pracy zgodny z kontami B2B.*

- Odznaka organizacji na każdej karcie Kanban z przypisanym kolorem konta
- **Filtr organizacji** w panelu filtrów Kanban — modal wielokrotnego wyboru z polami wyboru; stan filtra jest zachowywany podczas nawigacji
- **Wielojęzyczne etykiety filtrów statusu Kanban** — nadaj każdej kolumnie Kanban niestandardową nazwę dla każdego języka portalu; przełączaj języki za pomocą selektora języka w ustawieniach skrzynki; przeciągaj, aby zmienić kolejność filtrów
- Przetłumaczone etykiety pojawiają się zarówno na pasku filtrów portalu, jak i w kolumnie **State** tabeli zgłoszeń firmy; łańcuch rezerwy: zapisany język → zapisany angielski → oryginalna nazwa kolumny

![Kanban — odznaki organizacji na kartach i modal filtra organizacji](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Integracja z polami niestandardowymi

*Wyświetl dane z modułu Pola niestandardowe bezpośrednio na stronie zgłoszenia w portalu.*

Wymaga zainstalowanego i aktywnego modułu [Custom Fields](https://freescout.net/module/custom-fields/).

- Panel dla każdej skrzynki w Ustawieniach skrzynki → OrgPortal pozwala wybrać, które pola niestandardowe pojawiają się na stronie zgłoszenia w portalu
- Przeciągnij pola, aby zmienić ich kolejność; każde pole może mieć niestandardową etykietę dla każdego języka portalu, z powrotem do zapisanej etykiety angielskiej, a następnie oryginalnej nazwy pola
- Na stronie zgłoszenia w portalu włączone pola są wyświetlane w responsywnej siatce dwukolumnowej między tematem zgłoszenia a wątkiem — wyświetlane są tylko pola z niepustą wartością
- Całkowicie opcjonalne — panel i blok na stronie zgłoszenia są automatycznie ukrywane, gdy moduł Pola niestandardowe nie jest zainstalowany lub nieaktywny

---

## Kontrola dostępu i uprawnienia

*Deleguj zarządzanie organizacją bez przyznawania dostępu administratora.*

- **"Allow managing organizations"** (`can_manage_org`) — dwa poziomy:
  - Jako **uprawnienie użytkownika** w ustawieniach agenta — pozwala liderowi zespołu wsparcia zarządzać wszystkimi organizacjami bez praw administratora
  - Jako **flaga dla członka** w formularzu edycji organizacji — pozwala konkretnym członkom organizacji zarządzać tą jedną organizacją z panelu administracyjnego
- **"Allow managing notification templates"** — oddzielne szczegółowe uprawnienie do edycji szablonów
- Usuwanie organizacji pozostaje wyłącznie dla administratorów
- Dostęp do portalu jest ściśle ograniczony do skrzynki: menedżer z Organizacji A nie może uzyskać dostępu do Organizacji B

![Szczegółowe uprawnienia — zezwolenie na zarządzanie organizacjami i szablonami powiadomień](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Ustawienia systemowe — Manage → Organizations → System tab

*Kontrolki tylko dla administratorów dotyczące przypisania, uzupełniania i przełącznika języka portalu.*

Zakładka **System** jest widoczna tylko dla administratorów FreeScout.

### Panel 1: Przypisanie zgłoszeń

Zobacz [Org Snapshot](#org-snapshot--trwałe-przypisanie-zgłoszeń) powyżej, aby uzyskać pełny opis trybów przypisania, narzędzi uzupełniania i auto-cron.

### Panel 2: Przełącznik języka portalu

- **Włączanie/wyłączanie** przełącznika języka na pasku nawigacyjnym End-User Portal
- **Wybierz, które z 19 języków** oferować (siatka pól wyboru); wszystkie są domyślnie włączone
- Gdy włączone, menedżerowie mogą przełączać język portalu; ich wybór jest zapisywany i używany do powiadomień e-mail
- Jest to wbudowany przełącznik języka OrgPortal — działa niezależnie od zewnętrznych modułów do przełączania języka; oba mogą współistnieć

![Zakładka System — panel przełącznika języka portalu z polami wyboru języka](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Samoobsługa dla menedżerów korporacyjnych *(opcjonalne)*

*Daj swoim klientom B2B portal, gdzie zarządzają relacją wsparcia swojej firmy — bez konieczności kontaktowania się z Twoim zespołem przy każdej aktualizacji statusu.*

Wymaga modułu [End-User Portal](https://freescout.net/module/end-user-portal/).

### Panel zgłoszeń firmy

Dedykowana sekcja **Company Tickets** w nawigacji portalu z pełnowartościową tabelą zgłoszeń:

| Kolumna | Opis |
|---------|------|
| **#** | ID zgłoszenia |
| **Subject** | Skrócony z podpowiedzią po najechaniu myszą |
| **Responsible** | Przypisany agent wsparcia |
| **Author** | Klient, który otworzył zgłoszenie; kliknij, aby filtrować według tego autora |
| **Status** | Active / Pending / Closed / Spam z ikonami |
| **State** | Nazwa kolumny Kanban w bieżącym języku portalu (tylko gdy moduł Kanban jest aktywny) |
| **Updated** | Data i czas ostatniej odpowiedzi |

**Dwa niezależne wskaźniki statusu odczytu w każdym wierszu** — śledzą dwie różne osoby i są wyświetlane jednocześnie:

| Wskaźnik | Czyj status odczytu | Co oznacza |
|----------|---------------------|------------|
| **Pogrubiony wiersz** | Menedżer przeglądający portal | Menedżer ma nieprzeczytane powiadomienia dla tej rozmowy — coś się wydarzyło, czego jeszcze nie widział |
| **Ikona 👁 oka** | Autor zgłoszenia (klient, który je przesłał) | Autor nie otworzył jeszcze ostatniej odpowiedzi agenta — przydatne do sprawdzenia, czy klient faktycznie zobaczył odpowiedź |

Te dwa stany są całkowicie niezależne: wiersz może być pogrubiony (menedżer nie przeczytał) gdy brakuje oka (autor już przeczytał) lub odwrotnie. Menedżer widzi oba jednocześnie, co daje pełny obraz tego, co dzieje się po obu stronach zgłoszenia bez jego otwierania.

**Filtr autora** — kliknięcie nazwy autora aktywuje filtr; na górze tabeli pojawia się baner z nazwą aktywnego autora i linkiem × do wyczyszczenia filtra.

Zarówno tabela desktopowa, jak i responsywny **układ kart mobilnych** są dostępne; przełączają się automatycznie w zależności od szerokości ekranu.

Szablon paska filtrów obsługuje **zastąpienie** przez `enduserportal::partials.tickets_filters` — umieść niestandardowy widok pod tą ścieżką, aby zastąpić domyślny pasek filtrów OrgPortal zachowując wszystkie inne funkcje.

![Zgłoszenia firmy — pełna tabela ze wskaźnikami odczytu, banerem filtra autora, filtrami statusu](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Akcje na zgłoszeniach w portalu

Menedżerowie mogą działać bezpośrednio — bez konieczności kontaktowania się z pomocą techniczną:

- **Odpowiedź z załącznikami** — przeciągnij i upuść, wiele plików na odpowiedź; nazwy załączników i rozmiary plików pokazywane przy każdym wątku
- **Zamknięcie zgłoszenia** — nowa odpowiedź automatycznie je otwiera ponownie; baner informuje menedżera o tym gdy zgłoszenie jest zamknięte
- **Zmiana autora zgłoszenia** — przypisz zgłoszenie do innego członka organizacji
- **Filtrowanie według jednostki** — globalni menedżerowie filtrują listę zgłoszeń według jednostki strukturalnej
- **Filtrowanie według statusu Kanban** — konfigurowalne dla każdej skrzynki, etykiety wyświetlane w bieżącym języku portalu

![Widok zgłoszenia w portalu — formularz odpowiedzi z przeciągnij i upuść załączniki i baner zamkniętego zgłoszenia](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Śledzenie wyświetleń przez menedżera

- Adnotacja **"viewed"** pojawia się pod odpowiedziami agentów w widoku zgłoszenia administratora, gdy menedżer otwiera zgłoszenie w portalu
- Pokazuje nazwę menedżera, rolę (Organization manager / Unit manager) i czas, który upłynął
- Wyświetlenia globalnego menedżera i menedżera jednostki są śledzone i wyświetlane niezależnie — taki sam UX jak natywne "Customer viewed" w FreeScout

![Śledzenie wyświetleń przez menedżera — adnotacja 'viewed' pojawia się pod odpowiedzią agenta w widoku zgłoszenia administratora](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Dzwonek powiadomień w czasie rzeczywistym *(opcjonalne)*

*Informuj menedżerów na bieżąco gdy cokolwiek dzieje się ze zgłoszeniami ich firmy.*

Wymaga modułu [End-User Portal](https://freescout.net/module/end-user-portal/).

- Ikona 🔔 dzwonka z odznaką liczby nieprzeczytanych na żywo na pasku nawigacyjnym EUP — automatycznie repozycjonuje się na urządzeniach mobilnych (obok przycisku hamburgera)
- Powiadomienia dla: **nowe zgłoszenie**, **odpowiedź agenta**, **odpowiedź klienta** — dla wszystkich ról menedżerskich
- Panel rozwijany z powiadomieniami pogrupowanymi według daty: nazwa aktora, typ zdarzenia, numer zgłoszenia, podgląd wiadomości, znacznik czasu
- **Automatyczne oznaczanie jako przeczytane** gdy menedżer otwiera zgłoszenie
- Oznaczanie poszczególnych powiadomień jako przeczytane przez ×; **Mark all as read** w nagłówku panelu
- Odpytuje co 15 sekund; odświeża się przy nawigacji wstecz/do przodu w przeglądarce (świadome bfcache)

![Dzwonek powiadomień w czasie rzeczywistym — panel rozwijany z pogrupowanymi nieprzeczytanymi powiadomieniami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Subskrypcje powiadomień *(opcjonalne)*

*Pozwól menedżerom decydować, o czym chcą być informowani — nic więcej, nic mniej.*

- **Wizualna macierz subskrypcji** na zakładce "Notifications" w ustawieniach organizacji portalu
- **Trzy typy zdarzeń:** New ticket · Agent reply · Customer reply
- **Dwa poziomy zakresu:** Cała organizacja (globalni menedżerowie) · Indywidualne jednostki strukturalne
- Członkowie bez jednostki są grupowani w oddzielnym rozszerzalnym wierszu **"No unit"**
- **Nadpisania dla poszczególnych członków** — rozwiń dowolny wiersz jednostki, aby pokazać poszczególnych członków i przełączać ich subskrypcje inline; menedżerowie jednostek z ograniczoną rolą są odpowiednio oznaczeni
- **Kaskadowa logika w obu kierunkach:**
  - Włączenie "Entire organization" → włącza wszystkie jednostki i wszystkich członków
  - Włączenie jednostki → włącza wszystkich jej członków
  - Wyłączenie członka → automatycznie uzgadnia pola wyboru jednostki i organizacji
- Globalni menedżerowie zarządzają wszystkimi członkami; menedżerowie jednostek zarządzają tylko własną jednostką
- Powiadomienia używają sterownika poczty odpowiedniej skrzynki

![Macierz subskrypcji powiadomień — przełączniki dla jednostek i poszczególnych członków](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Ustawienia organizacji w portalu

*Menedżerowie konfigurują strukturę organizacji bez dostępu administratora.*

**Organization Settings** w nawigacji portalu ma trzy zakładki:

### Zakładka Notifications

Macierz subskrypcji opisana powyżej.

### Zakładka Units *(tylko globalni menedżerowie)*

- **Tworzenie jednostki** — formularz inline z polem nazwy
- **Zmiana nazwy jednostki** — edycja inline bezpośrednio w wierszu tabeli
- **Usunięcie jednostki** — przycisk z potwierdzeniem; menedżerowie jednostek są automatycznie obniżani do roli member
- Liczba członków pokazywana dla każdej jednostki

### Zakładka Members

- Tabela wszystkich członków organizacji: nazwa, jednostka strukturalna, rola, odznaka statusu aktywny/nieaktywny
- Etykieta **"Global manager"** pokazywana obok nazwy członka tam gdzie ma zastosowanie
- Pole wyboru **Show deactivated** — pojawia się tylko gdy istnieją nieaktywni członkowie; domyślnie ukryte
- **Globalni menedżerowie** mogą aktualizować jednostkę i rolę dowolnego członka za pomocą formularza inline (wybór jednostki + wybór roli + Apply)
- **Globalni menedżerowie nie mogą promować członka do globalnego menedżera** z portalu — wymaga to dostępu administratora
- Przycisk **Aktywacja / dezaktywacja** dla każdego członka z potwierdzeniem dezaktywacji

![Ustawienia organizacji w portalu — zakładki Units i Members](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Wielojęzyczne szablony e-mail powiadomień *(opcjonalne)*

*Twoi klienci korporacyjni otrzymują wiadomości e-mail wsparcia we własnym języku — automatycznie, bez żadnego ręcznego wysiłku.*

Konfigurowane w **Manage → Organizations → Templates tab** (widoczne dla użytkowników z uprawnieniem "manage templates").

- **Szablony dla każdego języka** — oddzielny temat i treść dla każdego języka portalu; przełączaj między nimi za pomocą listy rozwijanej języka; wartości są zamieniane w pamięci bez przeładowania strony
- **Zwijalne panele** dla każdego typu zdarzenia (New ticket / Agent reply / Customer reply) — edytor Summernote inicjalizuje się leniwie gdy panel jest otwierany
- Przycisk **Load Default** w każdym panelu — przywraca wbudowany szablon dla aktualnie wybranego języka (używa angielskiego wbudowanego jeśli nie istnieje domyślny dla konkretnego języka)
- **Edytor WYSIWYG Summernote** do tworzenia bogatych wiadomości e-mail HTML
- **Selektor zmiennych makro** — wstawiaj symbole zastępcze do tematu lub treści jednym kliknięciem; pozycja kursora jest zachowywana w polu tematu
- **19 wbudowanych domyślnych szablonów** — gotowych do użycia od razu; nie wymagają konfiguracji

**Dostępne zmienne makro:**

| Zmienna | Opis |
|---------|------|
| `{manager_name}` | Nazwa menedżera otrzymującego powiadomienie |
| `{author_name}` | Klient, który stworzył lub odpowiedział na zgłoszenie |
| `{org_name}` | Nazwa organizacji |
| `{unit_name}` | Nazwa jednostki strukturalnej |
| `{subject}` | Temat zgłoszenia |
| `{ticket_number}` | ID zgłoszenia |
| `{ticket_url}` | Bezpośredni link do zgłoszenia w portalu |
| `{ticket_text}` | Pełna treść wiadomości początkowej (HTML) |
| `{reply_text}` | Pełna treść najnowszej odpowiedzi (HTML) |
| `{created_date}` | Data utworzenia zgłoszenia |
| `{created_time}` | Czas utworzenia zgłoszenia |
| `{created_datetime}` | Data i czas utworzenia zgłoszenia |
| `{reply_date}` | Data odpowiedzi |
| `{reply_time}` | Czas odpowiedzi |
| `{reply_datetime}` | Data i czas odpowiedzi |

**Łańcuch rezerwy:** zapisany szablon języka → wbudowany szablon języka → zapisany szablon angielski → wbudowany szablon angielski

Język powiadomień jest określany przez wybór języka portalu każdego menedżera, zapisywany automatycznie gdy używają przełącznika języka.

![Szablony e-mail — zwijalne panele dla języków, przycisk Load Default, edytor Summernote](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(opcjonalne)*

*Zintegruj OrgPortal ze swoim CRM, ERP lub przepływem pracy onboardingu klientów.*

Wymaga modułu [API and Webhooks](https://freescout.net/module/api-webhooks/).

- Pełne CRUD dla organizacji, jednostek strukturalnych, członkostw klientów i tagów
- **Pola organizacji:** `name`, `color`, `mailboxId`, `isActive` — wszystkie dostępne do odczytu i aktualizacji przez API
- **Podzasób members** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — aktualizuj rolę, jednostkę, `canManageOrg` i flagę `isActive` dla poszczególnych członków niezależnie bez dotykania reszty członkostwa
- **Podzasób tags** — `GET/PUT /api/organizations/{id}/tags` — wylistuj lub całkowicie zastąp powiązania tagów (wymaga modułu Tags; zwraca `503` jeśli nieaktywny)
- Uwierzytelnianie przez nagłówek `X-FreeScout-API-Key` lub parametr zapytania `api_key`
- Interaktywna **dokumentacja ReDoc** w **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Pełna dokumentacja API → [docs/api/README.md](docs/api/README.md)**

![Interaktywna dokumentacja API — ReDoc ze wszystkimi endpointami OrgPortal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Instalacja

> [!IMPORTANT]
> Pobierz `OrgPortal.zip` ze **[strony Releases](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — **nie** używaj "Code → Download ZIP" i nie klonuj repozytorium. Tylko ZIP wydania ma poprawną strukturę dla FreeScout i obsługuje automatyczne aktualizacje.

1. Pobierz `OrgPortal.zip` z [najnowszego wydania](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Wypakuj i skopiuj folder `OrgPortal` do `Modules/` swojej instalacji FreeScout
2. Przejdź do **Manage → Modules → OrgPortal → Activate**
3. Uruchom migracje:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Wyczyść pamięć podręczną:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Obsługa języka gruzińskiego** jest wdrażana automatycznie przy pierwszym uruchomieniu — nie wymaga ręcznego kopiowania plików.

---

## Automatyczne aktualizacje

OrgPortal obsługuje **aktualizacje jednym kliknięciem** przez wbudowany mechanizm aktualizacji modułów FreeScout.

> **Wymaga FreeScout 1.8.170 lub nowszego.** W starszych wersjach aktualizuj ręcznie, zastępując folder `OrgPortal` najnowszym plikiem ZIP z wydania.

Gdy dostępna jest nowa wersja, na stronie **Manage → Modules** pojawia się baner. Kliknij **Update now** — FreeScout automatycznie pobiera i instaluje najnowszą wersję.

---

## Kompatybilność modułów

| Moduł | Status | Uwagi |
|-------|--------|-------|
| End-User Portal ≥ 1.0.85 | Opcjonalne | Portal menedżera, dzwonek powiadomień, subskrypcje |
| API and Webhooks ≥ 1.0.80 | Opcjonalne | Endpointy REST API |
| Kanban ≥ 1.0.23 | Opcjonalne | Odznaka na kartach, filtr organizacji, wielojęzyczne etykiety kolumny State |
| Custom Fields | ✅ Kompatybilne | — |
| Workflows | ✅ Kompatybilne | — |
| Tags | ✅ Kompatybilne | Chipy tagów w formularzu edycji organizacji; powiązania tagów przez API (`/organizations/{id}/tags`); przypisanie zgłoszeń oparte na tagach |

---

## Konfiguracja

### Ustawienia globalne — **Manage → Organizations → System tab**

| Opcja | Opis |
|-------|------|
| Show badge on ticket page | Odznaka organizacji na liście rozmów i widoku zgłoszenia |
| Show badge on Kanban cards | Odznaka organizacji na kartach tablicy Kanban |
| Attribution source | `member` / `tag` / `tag_only` — jak zgłoszenia są przypisywane do organizacji |
| Auto-cron backfill | Uruchamiaj uzupełnianie co 5 minut automatycznie |
| Snapshot visibility | Pokaż/ukryj dane przypisania na pasku bocznym zgłoszenia |
| Portal Language Switcher | Włącz przełącznik języka na pasku nawigacyjnym EUP; wybierz które z 19 języków oferować |

### Ustawienia dla skrzynki — **Mailbox Settings → OrgPortal**

Nadpisuje wartości globalne dla konkretnej skrzynki.

| Opcja | Opis |
|-------|------|
| Show badge on ticket page | Włącz/wyłącz odznakę dla tej skrzynki |
| Show badge on Kanban cards | Włącz/wyłącz odznakę dla tej skrzynki |
| Show organization block in customer profile | Przełącz blok informacji o organizacji na pasku bocznym zgłoszenia |
| Company ticket status filters | Mapuj kolumny Kanban na nazwane filtry w portalu; etykiety dla każdego języka z selektorem języka; przeciągaj, aby zmienić kolejność |

![Ustawienia skrzynki — widoczność odznak i filtry statusu Kanban z wielojęzycznymi etykietami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Tłumaczenia

OrgPortal jest w pełni zlokalizowany w **19 językach**:

| Język | Kod | Język | Kod |
|-------|-----|-------|-----|
| Angielski | `en` | Niderlandzki | `nl` |
| Ukraiński | `uk` | Norweski | `no` |
| Niemiecki | `de` | Duński | `da` |
| Francuski | `fr` | Szwedzki | `sv` |
| Hiszpański | `es` | Fiński | `fi` |
| Włoski | `it` | Portugalski (BR) | `pt-BR` |
| Czeski | `cs` | Portugalski (PT) | `pt-PT` |
| Słowacki | `sk` | Rumuński | `ro` |
| Polski | `pl` | Chiński uproszczony | `zh-CN` |
| Gruziński | `ka` | | |

Pliki tłumaczeń: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Szablony e-mail powiadomień mają wbudowane domyślne wartości dla wszystkich 19 języków.

### Integracja przełącznika języka

OrgPortal zawiera wbudowany przełącznik języka portalu (włącz w **System tab → Portal Language Switcher**). Integruje się również z [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — oba mogą być aktywne jednocześnie.

Język wybrany przez menedżera dotyczy wszystkich ciągów interfejsu OrgPortal i jest zapisywany jako język powiadomień — wiadomości e-mail są automatycznie wysyłane w wybranym języku.

> **Uwaga techniczna:** Middleware `OrgPortalSetLocale` ponownie stosuje język portalu po middleware `Localize` FreeScout, aby zapobiec jego resetowaniu do domyślnego systemowego przy każdym żądaniu.

---

## Zrzuty ekranu

| | |
|---|---|
| ![Lista organizacji](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Edycja organizacji](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Lista organizacji — filtr statusu, wyszukiwanie na żywo, kolorowe odznaki* | *Edycja organizacji — wybór koloru, chipy tagów, tabela członków* |
| ![Zakładka System](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Edycja klienta](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Zakładka System — tryby przypisania, uzupełnianie, przełącznik języka* | *Edycja klienta — pole organizacji z podpowiedziami* |
| ![Portal zgłoszeń firmy](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Odpowiedź w portalu](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Zgłoszenia firmy — tabela, filtr autora, wskaźniki odczytu* | *Zgłoszenie w portalu — odpowiedź z załącznikami, baner zamknięcia* |
| ![Ustawienia organizacji w portalu](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Dzwonek powiadomień](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Ustawienia organizacji w portalu — zakładki Units i Members* | *Dzwonek powiadomień w czasie rzeczywistym z panelem rozwijanym* |
| ![Macierz subskrypcji](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Szablony e-mail](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Macierz subskrypcji powiadomień — dla jednostek i poszczególnych członków* | *Szablony e-mail — przełącznik języka, Load Default, Summernote* |
| ![Integracja Kanban](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Ustawienia skrzynki](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — odznaki organizacji i modal filtra organizacji* | *Ustawienia skrzynki — filtry Kanban z wielojęzycznymi etykietami* |
| ![Dokumentacja API](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interaktywna dokumentacja API — ReDoc* | |

---

## Licencja

[MIT](LICENSE) — © 2026 ASTIN-UA
