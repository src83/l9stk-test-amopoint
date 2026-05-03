## L9STK Test AmoPoint — Test AmoPoint Module for Laravel 9 Starter Kit

**Данное приложение — рабочий прототип платформенного подхода на базе модульного монолита L9STK Core v0.4.0**
- Код и документация STK-сборки: https://github.com/src83/l9stk
- Версия STK-сборки: v0.4.0
- Версия приложения: v0.1.0
- Статус: Development
- Laravel: v9.52.21
- PHP: 8.2

---

### Назначение

- Реализация рабочего модуля / команды / API-эндпоинта
- Реализация JS-сниппета / дополнительных требований 
- Использование в production и demo

---

## Реализовано

### Earthquake ingestion (AFAD)
- Интеграция с API AFAD (землетрясения, Türkiye)
- Получение данных по скользящему временному окну (UTC)
- Маппинг ответа API → DTO (EarthquakeEventDTO)
- Слой интеграции (Provider) изолирован от бизнес-логики
- Сервисный слой обрабатывает сценарии:
  - успешное получение
  - пустой ответ
  - ошибки внешнего API
- Сохранение через Repository


- Запуск синхронизации (консольная команда):
  ```bash
  php artisan earthquake:update
  ```
- Рекомендуемый cron:
  ```bash
  */5 * * * * php artisan earthquake:update
  ```

---

#### Архитектура

Разделение по слоям:
- Console — запуск (Artisan command)
- Service — orchestration / бизнес-логика
- Integrations — работа с внешним API
- DTO — нормализация данных
- Repository — работа с БД

---

#### Хранение данных

- Таблица earthquake_events
- Идемпотентность через уникальный ключ event_id
- Массовая запись через upsert
- Автоматическое обновление существующих записей

---

#### UX / UI

- Отсутствует (CLI-only модуль)

---

### Event List API

REST API для доступа к сохранённым сейсмическим событиям.

#### Эндпоинты

| Метод | URL                  | Описание               |
|-------|----------------------|------------------------|
| GET   | `/api/events`        | Список событий         |
| GET   | `/api/events/{id}`   | Одиночная запись по ID |

#### Параметры

**GET /api/events**

| Параметр | Тип   | Обязательный | Описание                |
|----------|-------|--------------|-------------------------|
| `page`   | `int` | нет          | Номер страницы (min: 1) |

Количество записей на странице: `config('api.items_per_page')` → env `ITEMS_PER_PAGE` (default: `15`).

**GET /api/events/{id}**

| Параметр | Тип   | Обязательный | Описание           |
|----------|-------|--------------|--------------------|
| `id`     | `int` | да           | ID записи (min: 1) |

---

#### Контракты ответов

**Список — пустой результат**
```json
{
  "success": true, "http_code": 200, "http_text": "OK",
  "message": null, "meta": null, "data": []
}
```

**Список — с данными**
```json
{
  "success": true, "http_code": 200, "http_text": "OK",
  "message": { "gui": "Опциональное сопроводительное сообщение..." },
  "meta": {
    "paginator": {
      "page": 1, "per_page": 15, "total_item": 1,
      "total_page": 1, "last_item": 1, "has_next_page": false
    }
  },
  "data": [
    { "id": 12, "location": "Menderes (İzmir)", "magnitude": "3.3" }
  ]
}
```

**Список — ошибка валидации параметра**
```json
{
  "success": false, "http_code": 422, "http_text": "Unprocessable Content",
  "message": { "sys": "The page must be an integer." },
  "details": { "fields": { "page": ["The page must be an integer."] } }
}
```

**Одиночная запись — найдена**
```json
{
  "success": true, "http_code": 200, "http_text": "OK",
  "message": null, "meta": null,
  "data": { "id": 3, "location": "Dalaman (Muğla)", "magnitude": "2.0" }
}
```

**Одиночная запись — не найдена**
```json
{
  "success": false, "http_code": 404, "http_text": "Not Found",
  "message": { "sys": "Event with ID 99999 not found" },
  "details": null
}
```

**Одиночная запись — блокировка по бизнес-логике**
```json
{
  "success": false, "http_code": 409, "http_text": "Conflict",
  "message": { "sys": "Запись заблокирована бизнес-логикой" },
  "details": null
}
```

---

#### Поля data-объекта (EventResource)

| Поле        | Тип      | Описание      |
|-------------|----------|---------------|
| `id`        | `int`    | ID записи     |
| `location`  | `string` | Место события |
| `magnitude` | `string` | Магнитуда     |

> Набор полей определяется в `EventResource::toArray()` и может быть расширен
> без изменения контракта обёртки (`success`, `http_code`, `meta` и т.д.).

---

#### Ограничения

- API AFAD не указывает timezone явно (принят UTC)
- Возможна задержка публикации событий API
- Данные могут содержать неполные или некорректные значения
- Нет пагинации/ограничения батча со стороны API

---

### Conditional Field Visibility (JS)

Динамическое скрытие/показ полей формы в зависимости от выбранного типа.

#### Как работает

На странице есть `<select name="type_val">` и набор полей/кнопок с атрибутом `name`.
При выборе значения в селекте остаются видимыми только те элементы, чей `name` **содержит** выбранное значение (`includes`). Остальные скрываются.

Инициализация происходит автоматически при загрузке страницы по текущему значению селекта.

#### Алгоритм

```
typeSelect.value → applyVisibility(value)
  для каждого [name]:not([name="type_val"]):
    container = el.closest('p') || el.parentElement
    container.style.display = el.name.includes(value) ? '' : 'none'
```

#### Файлы

| Файл | Описание |
|------|----------|
| `app/Modules/Example/resources/cabinet/js/app.js` | Исходник (Vanilla JS, IIFE) |
| `public/js/cabinet/example/app.min.js` | Скомпилированный бандл (webpack) |

---

