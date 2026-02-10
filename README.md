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
- **Database**: MySQL / PostgreSQL / SQLite
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
