<p align="center">
  <img src="logo.png" alt="OrgPortal" width="120">
</p>

# OrgPortal — Organization Portal for FreeScout

Модуль для FreeScout, що додає поняття **Організації** (компанії/команди) до клієнтів, розширює End-User Portal для менеджерів і відображає плашку організації на тікетах та картках Kanban.

**Мінімальна версія FreeScout:** 1.8.147  
**Залежності:** [End-User Portal](https://freescout.net/module/end-user-portal/)  
**Опціональні залежності:** [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

## Можливості

### Управління організаціями (адмін)
- **Manage → Organizations** — повний CRUD: створення, редагування, видалення організацій
- Прив'язка клієнтів до організацій з вибором ролі: `member` або `manager`
- Пошук клієнтів за іменем або email при додаванні учасника
- Один клієнт — одна організація (обмеження на рівні БД)

### Плашка організації на тікетах
- Відображається поруч з тегами на сторінці тікета
- Клікабельна — відкриває пошук по всіх тікетах цієї організації
- Увімкнення/вимкнення через **Manage → OrgPortal Settings**

### Плашка організації на картках Kanban
- Відображається під назвою тікета на кожній картці Kanban
- Клікабельна — веде до пошуку по організації
- Client-side фільтр у тулбарі Kanban для фільтрації карток по організації
- Увімкнення/вимкнення через **Manage → OrgPortal Settings**

### Фільтр пошуку по організації
- Розширює стандартний пошук FreeScout фільтром **Organization**
- Показує всі тікети клієнтів вибраної організації

### Картка клієнта
- Поле **Organization** у формі редагування клієнта
- Вибір організації та ролі (`Member` / `Manager`) з випадаючого списку

### End-User Portal — розширений доступ для менеджерів
- Менеджери бачать додатковий пункт **Company Tickets** у навігації порталу
- Перегляд усіх тікетів членів своєї організації
- Відповідь на тікети від імені організації
- Сторінка **Org Settings** для налаштування email-сповіщень

### Email-сповіщення
- Менеджери з увімкненою опцією отримують email при створенні нового тікета будь-яким членом організації
- Використовує поштовий драйвер відповідного mailbox

### REST API
> Вимагає модуль [API and Webhooks](https://freescout.net/module/api-webhooks/)

| Метод | Endpoint | Опис |
|-------|----------|-------|
| `GET` | `/api/organizations` | Список організацій (пагінація) |
| `POST` | `/api/organizations` | Створити організацію |
| `GET` | `/api/organizations/{id}` | Отримати організацію |
| `PUT` | `/api/organizations/{id}` | Оновити організацію |
| `DELETE` | `/api/organizations/{id}` | Видалити організацію |
| `GET` | `/api/customers/{id}/organization` | Організація клієнта |
| `PUT` | `/api/customers/{id}/organization` | Встановити організацію клієнту |
| `DELETE` | `/api/customers/{id}/organization` | Видалити клієнта з організації |

Формат відповіді — HAL-подібний: `_embedded`, `page`, `errorCode`.

---

## Встановлення

1. Скопіюйте папку `OrgPortal` у `Modules/` вашого FreeScout
2. Переконайтесь, що встановлено та активовано модуль **End-User Portal**
3. У адмін-панелі: **Manage → Modules → OrgPortal → Activate**
4. Запустіть міграції:
   ```bash
   php artisan module:migrate OrgPortal
   ```
5. Очистіть кеш:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Структура БД

### `organizations`
| Поле | Тип | Опис |
|------|-----|-------|
| `id` | bigint PK | |
| `name` | varchar(255) unique | Назва організації |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### `organization_members`
| Поле | Тип | Опис |
|------|-----|-------|
| `id` | bigint PK | |
| `organization_id` | bigint FK → organizations | Каскадне видалення |
| `customer_id` | bigint FK → customers | Каскадне видалення |
| `role` | enum('member','manager') | Роль у організації |
| `notify_on_new_ticket` | boolean | Email при новому тікеті |
| Unique | (`organization_id`, `customer_id`) | |

---

## Налаштування

**Manage → OrgPortal Settings**

| Опція | За замовчуванням | Опис |
|-------|-----------------|-------|
| Show badge on ticket page | ✅ увімкнено | Плашка організації на сторінці тікета поруч з тегами |
| Show badge on Kanban cards | ✅ увімкнено | Плашка організації на картках Kanban + фільтр |

Налаштування зберігаються у таблиці `options` FreeScout.

---

## Переклади

Підтримуються мови: **English** (`en`), **Ukrainian** (`uk`).

Файли перекладів: `Resources/lang/{locale}/messages.php`

---

## Сумісність з модулями

| Модуль | Версія | Статус |
|--------|--------|--------|
| End-User Portal | ≥ 1.0.85 | Обов'язковий |
| API and Webhooks | ≥ 1.0.80 | Опціональний (API endpoints) |
| Kanban | ≥ 1.0.23 | Опціональний (badge + filter) |
| Tags | будь-яка | Сумісний |
| Custom Fields | будь-яка | Сумісний |
| Workflows | будь-яка | Сумісний (отримує події CustomerReplied) |
| Saved Replies | будь-яка | Сумісний |
| Mobile Notifications | будь-яка | Сумісний |

---

## Ліцензія

Proprietary — ASTIN UA.
