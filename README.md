# Invoice App

> A Laravel-based invoice management application — invoices, currencies, PDF export, and a clear development workflow.

## Table of Contents
- **Project:** Quick overview
- **Requirements:** What you need locally
- **Setup:** Step-by-step installation
- **Usage:** Run and access the app
- **Project Structure:** Key files and controllers
- **Source Control:** Git workflow for create & update
- **Testing & Deployment:** Commands and tips
- **Changelog & Updating README:** How to keep docs current
- **Contributing & Contact:** How to help and reach the author

## Project

This repository contains a Laravel invoice application that supports multiple currencies and PDF generation. It includes a service/repository pattern with controllers, models, views, and seeders to rapidly run and test locally.

## Requirements
- **PHP** 8.0+ (match your Laravel version)
- **Composer**
- **Node.js** + **npm**  for frontend build
- A database (MySQL)
- Optional: `wkhtmltopdf` / `dompdf` for PDF rendering (project already bundles dompdf)

## Quick Setup (Development)

1. Clone the repository

```bash
git clone <repo-url> invoice-app
cd invoice-app
```

2. Install PHP dependencies

```bash
composer install
```

3. Install JavaScript dependencies and build assets

```bash
npm install
# For dev
npm run dev
# For production
npm run build
```

4. Environment

```bash
cp .env.example .env
php artisan key:generate
# Edit .env to set DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

5. Database

```bash
php artisan migrate
php artisan db:seed
```

6. Storage link (if using public storage)

```bash
php artisan storage:link
```

7. Run the app

```bash
php artisan serve
# Visit http://127.0.0.1:8000
```

## Project Structure & Key Files
- **Controllers:** `app/Http/Controllers/` — web controllers for invoices and other resources.
- **Models:** `app/Models/Invoice.php`, `app/Models/Currency.php`
- **Services:** `app/Services/InvoiceService.php` — business logic and PDF orchestration.
- **Repositories:** `app/Repositories/InvoiceRepository.php` — data access for invoices.
- **Views:** `resources/views/` — blade templates: forms, tables, PDFs.
- **Routes:** `routes/web.php` — web endpoints for invoice CRUD and PDF export.
- **Migrations / Seeders:** `database/migrations/` and `database/seeders/` (e.g., `CurrencySeeder`)

If you need to locate the controller handling invoices, look in `app/Http/Controllers` for files with names like `InvoiceController` or routes referenced in `routes/web.php`.

## Usage Examples
- Create invoice via the web form (see `resources/views/form.blade.php`).
- View all invoices: check `resources/views/allinvoice.blade.php` and the route in `routes/web.php`.
- Export PDF: the service uses `laravel-dompdf` — typical controller action will return `PDF::loadView(...)->stream()` or `download()`.

## Source Control: Practical Git Workflow (Create → Update over time)

Initial repository setup

```bash
# initialize and create main branch
git init
git add .
git commit -m "chore: initial project import"
git branch -M main
git remote add origin <remote-url>
git push -u origin main
```

Feature workflow and incremental updates

1. Create feature branch

```bash
git checkout -b feat/add-invoice-filter
```

2. Work and commit incrementally (use clear messages)

```bash
git add .
git commit -m "feat(invoice): add date-range filter"
```

3. Push and open a Pull Request

```bash
git push -u origin feat/add-invoice-filter
```

Commit message guidelines
- Use prefixes: `feat`, `fix`, `chore`, `docs`, `refactor`, `test`.
- Keep messages short and descriptive, e.g. `feat(invoice): add PDF attachment support`.

Updating `README.md` over time

- When you add or change features, update the `Usage`, `Project Structure`, and `Changelog` sections.
- Prefer small, focused README commits: `docs(readme): document new currency support`.
- Add a `CHANGELOG.md` for release notes, or use a `Releases` section in your Git host.

Tagging releases

```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin --tags
```

Keeping docs in sync (recommended cadence)
- After any breaking change: update README and changelog immediately.
- For small UX tweaks: update the relevant view or usage snippet and commit a docs update in the same PR.

## Tests

Run the test suite

```bash
# PHPUnit or Pest
./vendor/bin/pest
php artisan test
```

Add tests for new controller behavior in `tests/Feature/` and `tests/Unit/`.

## Deployment Notes
- Use environment-specific `.env` variables on the host.
- Build assets (`npm run build`) before deploying to production.
- Ensure file permissions and `storage` are writable.

## Changelog and Versioning
- Maintain a `CHANGELOG.md` or use GitHub Releases.
- Follow Semantic Versioning (`MAJOR.MINOR.PATCH`) for public releases.

## Contributing
- Fork, create a feature branch, write tests, open a Pull Request.
- Code style: follow PSR-12 for PHP; run `./vendor/bin/php-cs-fixer` if configured.

## Contact
- Author / Maintainer: (replace with your name and email)

---

If you want, I can:
- commit `README.md` for you and create the initial git commit,
- or expand any section with specific controller references after I inspect `app/Http/Controllers`.
# 💼 Professional Invoice Generator

A modern, robust, and feature-rich invoice management system built with **Laravel 12**. This application provides a seamless workflow for creating, managing, and exporting professional invoices.

---

## ✨ Key Features

- **Smart Auto-Generation**: Unique invoice numbers are automatically generated based on the highest existing number in the database.
- **Dynamic Line Items**: Add or remove rows dynamically for products and services with real-time UI updates.
- **Intelligent Calculations**: Automatic calculation of subtotals, tax rates, discounts, shipping, and remaining balance.
- **Logo Persistence**: Upload business logos with live preview and secure storage management.
- **PDF Core**: High-quality PDF exports powered by DomPDF, optimized for A4 printing.
- **Audit History**: Clean dashboard to track and manage all saved invoices.
- **Multi-Currency Support**: Flexible currency selection for global billing.

---

## 🏗️ Architecture & Best Practices

This project follows **Clean Architecture** principles to ensure maintainability and scalability:

- **Repository Pattern**: Centralized data access logic in `InvoiceRepository`.
- **Service Pattern**: Business logic (calculations, file processing) isolated in `InvoiceService`.
- **Form Requests**: Robust validation handled independently in `InvoiceRequest`.
- **Dependency Injection**: Services and Repositories are cleanly injected into controllers.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+ / Laravel 12
- **Frontend**: Tailwind CSS / Vanilla JavaScript
- **Database**: MySQL 
- **Tools**: Composer, Vite

---

## � Installation Guide

Follow these steps to set up the project on your local machine:

### 1. Prerequisites

Ensure you have **PHP 8.2+**, **Composer**, and **Node.js** installed.

### 2. Clone and Install

```bash
git clone [repository-url]
cd invoice
composer install
npm install && npm run build
```

### 3. Environment Configuration

Copy the example environment file and set your credentials:

```bash
cp .env.example .env
```

> [!IMPORTANT]
> Update your database connection details in the `.env` file before proceeding.

### 4. Database Initialization

```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
```

### 5. Running the Application

```bash
php artisan serve
```

Access the application at `http://localhost:8000`.

---

## 💻 Project Structure

```text
app/
├── Http/
│   ├── Controllers/    # Controller coordination
│   └── Requests/       # Validation logic
├── Repositories/       # Data access layer
├── Services/           # Business logic layer
└── Models/             # Database entities
resources/
└── views/              # Blade templates
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
