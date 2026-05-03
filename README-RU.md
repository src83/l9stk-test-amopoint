## L9STK — Laravel 9 Starter Kit

**English version:** [README.md](README.md)

- Версия проекта: v0.4.0
- Laravel: v9.52.21
- PHP: 8.2

---

### О проекте

L9STK — это стартовый набор на базе Laravel, реализующий подход **модульного монолита**.

Каждая функциональная единица (например, CRUD) инкапсулируется в отдельный модуль.
Цель — упростить и ускорить разработку типовых бизнес-задач, сохраняя при этом структурированность и масштабируемость кодовой базы.

---

### Почему модульный монолит

- Снижает избыточную сложность по сравнению с микросервисами
- Сохраняет структуру проекта при росте
- Обеспечивает изоляцию на уровне фич
- Упрощает повторное использование и рефакторинг

---

### Важно

- Репозиторий является **Core-зависимостью**
- Новые фичи напрямую сюда не добавляются
- Допускаются только: инфраструктура, контракты и базовые абстракции

---

### Установка

- `git clone git@github.com:src83/l9stk.git`
- установить права доступа в соответствии с пользователем web-сервера - **в основном требуется для production-среды**:

      1. Определите пользователя (владельца) вашего веб-сервера (PHP-FPM). 
         Обычно это: `www-data`, `user` или `nginx`. Выполните одну из команд:
         - ps aux | grep 'php-fpm: pool'
         - grep "^user" /etc/php/*/fpm/pool.d/*.conf
         - grep "^group" /etc/php/*/fpm/pool.d/*.conf
    
      2. Установите права доступа на все вложенные объекты.
         - `cd storage`
         - `sudo chown -R <web-user>:<web-user> ./*`

- скопировать `.env.example` в `.env` и настроить его
- `composer config --global audit.block-insecure false`
- `composer update`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan ide-helper:models`
- `npm install`
- `npm run dev`
- в локальной среде в `/etc/hosts` добавить `127.0.0.1 l9stk.loc`
- открыть в браузере `http://l9stk.loc`

---

### Быстрый старт (создание модуля)

1. Скопировать референсный модуль:
   `app/Modules/Example → app/Modules/Post`

2. Переименовать:
    - Namespace: `Example → Post`
    - ServiceProvider
    - Routes / Controllers / Requests

3. Зарегистрировать ServiceProvider в:
   `config/app.php`

4. (Опционально) добавить frontend-ассеты в `webpack.mix.js`

5. Выполнить:
    - `php artisan migrate`
    - `npm run dev`

6. Для создания миграции внутри модуля используйте:
```bash
php artisan make:migration create_example_table --path=app/Modules/Example/database/migrations
```

Модуль готов.

---

### Точки интеграции (подключение модуля)

- Зарегистрировать `ServiceProvider` модуля в `config/app.php`
  - Маршруты подключаются через `loadRoutes()`
  - Представления — через `loadViews()`
  - Миграции — через `loadMigrations()`
- Интегрировать представления модуля в layout приложения (например, cabinet layout)
- Добавить frontend-ассеты в `webpack.mix.js`

---

### Примеры эндпоинтов

Cabinet:
- `GET /cabinet/example`

Ajax:
- `GET /cabinet/example/ajax/entities`
- `POST /cabinet/example/ajax/entities`

API:
- (зарезервировано для внешних API, см. `routes/api.php`)

---

### Структура модуля

```
app/Modules/Example/
 ├── Http/
 │    ├── Controllers/
 │    │     ├── Api/
 │    │     ├── Cabinet/
 │    │     │    └── Ajax/
 │    │     └── Web/
 │    │          └── Ajax/
 │    └── Requests/
 │          ├── Cabinet/
 │          │    └── StoreEntityRequest.php
 │          └── Web/
 │
 ├── Models/
 ├── Providers/
 │    └── ExampleServiceProvider.php
 │
 ├── Repositories/
 ├── Services/
 ├── database/
 │    ├── migrations/
 │    ├── factories/
 │    └── seeders/
 │
 ├── resources/
 │    ├── cabinet/
 │    │     ├── css/app.css
 │    │     ├── img/
 │    │     └── js/app.js
 │    │
 │    └── views/
 │         └── cabinet/
 │              └── example.blade.php
 │
 └── routes/
      ├── api.php
      ├── cabinet.php
      └── web.php
```

- Все модули расположены в папке: `app/Modules`
- `app/Modules/Example` — референсный модуль (рекомендуется как точка старта)

Вы можете копировать или адаптировать его для создания новых фич без необходимости заново проектировать архитектуру.

---

### Принципы

- Каждый модуль — это самодостаточная функциональная единица
- Модуль должен быть отделяемым и переносимым
- Новые фичи предпочтительно реализовывать в виде модулей
- Немодульный код в базовой структуре фреймворка, например легаси, может сосуществовать с модулями без конфликтов

---

### Что даёт модуль

- Структурированную и масштабируемую архитектуру
- Инкапсулированную логику и маршрутизацию
- Изолированные представления и ассеты
- Интеграцию через ServiceProvider
- Контроллеры, готовые к DI

---

### Roadmap

- Авто-дискавери модулей (пока что требуется ручная регистрация ServiceProvider)
- Динамическая конфигурация webpack
- Автоматическая регистрация провайдеров

---

### Заметки

В текущей реализации используется слой `Ajax` как внутренний API для взаимодействия с UI.

В production-проектах рекомендуется использовать более абстрактные названия, такие как:
- `InternalApi`
- `UiApi`

Это поможет избежать привязки к конкретному способу взаимодействия.
