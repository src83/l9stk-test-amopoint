## L9STK — Laravel 9 Starter Kit

**Русская версия:** [README-RU.md](README-RU.md)

- Project version: v0.4.0
- Laravel: v9.52.21
- PHP: 8.2

---

### About

L9STK is a Laravel-based starter kit that implements a **modular monolith** approach.

Each functional unit (e.g., CRUD) is encapsulated as a module.
The goal is to simplify and speed up development of typical business features while keeping the codebase structured and scalable.

---

### Why Modular Monolith

- Reduces complexity compared to microservices
- Maintains a clear structure as the project grows
- Enables feature-level isolation
- Simplifies reuse and refactoring

---

### Important

- This repository acts as a **core dependency**
- New features are not added directly
- Only infrastructure, contracts, and minimal abstractions belong here

---

### Install

- `git clone git@github.com:src83/l9stk.git`
- set access rights according to the web server user - **mainly required for production environments**:

      1. Determine your web server user (PHP-FPM owner). 
         Typically, this is `www-data`, `user` or `nginx`. Run one of the following commands:
         - ps aux | grep 'php-fpm: pool'
         - grep "^user" /etc/php/*/fpm/pool.d/*.conf
         - grep "^group" /etc/php/*/fpm/pool.d/*.conf
    
      2. Set permissions on all nested objects.
         - `cd storage`
         - `sudo chown -R <web-user>:<web-user> ./*`

- copy `.env.example` to `.env` and configure it
- `composer config --global audit.block-insecure false` 
- `composer update`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan ide-helper:models`
- `npm install`
- `npm run dev`
- in the local environment, add `127.0.0.1 l9stk.loc` to `/etc/hosts`
- open in browser `http://l9stk.loc`

---

### Quick Start (create module)

1. Copy the reference module:
   `app/Modules/Example → app/Modules/Post`

2. Rename:
    - Namespace: `Example → Post`
    - ServiceProvider
    - Routes / Controllers / Requests

3. Register ServiceProvider in:
   `config/app.php`

4. (Optional) Add frontend assets to `webpack.mix.js`

5. Run:
    - `php artisan migrate`
    - `npm run dev`

6. To create a migration inside a module, use:
```bash
php artisan make:migration create_example_table --path=app/Modules/Example/database/migrations
```

Module is ready.

---

### Integration Points (to connect a module)

- Register module `ServiceProvider` in `config/app.php`
  - Routes are loaded via `loadRoutes()`
  - Views are loaded via `loadViews()`
  - Migrations are loaded via `loadMigrations()`
- Integrate module views into application layout (e.g., cabinet layout)
- Frontend assets should be added to `webpack.mix.js`

---

### Example Endpoints

Cabinet:
- `GET /cabinet/example`

Ajax:
- `GET /cabinet/example/ajax/entities`
- `POST /cabinet/example/ajax/entities`

API:
- (reserved for external API endpoints, see `routes/api.php`)

---

### Module Structure

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

- All modules are located in: `app/Modules`
- `app/Modules/Example` — reference module (recommended as a starting point)

You can copy or adapt it to create new features without rethinking architecture.

---

### Principles

- Each module is a self-contained functional unit
- Modules should be separable and portable
- New features should preferably be implemented as modules
- Non-modular code in the base framework structure (e.g., legacy) can coexist with modules without conflicts

---

### What the module provides

- Structured and scalable architecture
- Encapsulated logic and routing
- Isolated views and assets
- ServiceProvider-based integration
- DI-ready controllers

---

### Roadmap

- Module auto-discovery (actually manual ServiceProvider registration required)
- Dynamic webpack configuration
- Provider auto-registration

---

### Notes

The current implementation uses an `Ajax` layer as an internal API for UI interactions.

In production-grade systems, it is recommended to use more abstract naming, such as:
- `InternalApi`
- `UiApi`

It will help to avoid coupling with a specific transport mechanism.
