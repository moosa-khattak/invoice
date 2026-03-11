# 💼 Professional Invoice Management System

A comprehensive and modern invoice management application built with **Laravel 12** and **Tailwind CSS 4.0**. This project provides a complete solution for businesses to create, manage, export, and collect payments on their invoices, featuring a clean UI and robust architecture.

---

## ✨ Features Supported

Based on the core project files, this application includes the following production-ready features:

### 🔐 Authentication System

- **Standard Login & Registration**: Secure access using email and password.
- **Social Sign-In**: Integrated one-click login using **Google** and **GitHub** via Laravel Socialite.

### 📝 Invoice Management (CRUD)

- **Create & Edit Invoices**: Fully dynamic forms that allow you to add, edit, or remove multiple line items instantly.
- **Automatic Calculations**: The system automatically calculates subtotals, applies tax rates, subtracts discounts, adds shipping costs, and outputs the final balance due.
- **Customizable Details**: Support for client information (Bill To, Ship To), customizable due dates, and PO Numbers.
- **Unique Numbering**: Auto-generates unique invoice numbers (e.g., INV-001) tied to the specific user.
- **Dashboard View**: View all invoices in a centralized table indicating their current payment status (Paid, Pending, Overdue).

### 💳 Payments & Billing

- **Stripe Integration**: Securely process credit card payments directly on the invoice using Stripe Payment Intents.
- **Real-time Status Updates**: Once paid, the invoice balance is automatically updated, and the status changes from Pending to Paid.
- **Success Receipts**: Users are shown a summary template (`successfullypaid.blade.php`) immediately upon a successful transaction.

### 📄 Export & Downloads

- **PDF Generation**: Instantly generate and download A4-optimized PDF invoices using the `barryvdh/laravel-dompdf` package.
- **QR Codes**: Scannable QR Codes generation built into the PDF/views for quick referencing.
- **Logo Uploads**: Upload and persist your business logo onto the invoice documents.

---

## 🛠️ Tech Stack & Architecture

This application is built with modern, scalable tools and design patterns:

- **Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 4.0, Vanilla JavaScript, Vite and blade templete
- **Database**: MySQL & Eloquent ORM
- **Architecture**: **Repository & Service Pattern**
    - _Repositories_ (e.g., `InvoiceRepository`, `StripeRepository`) handle database queries.
    - _Services_ (e.g., `InvoiceService`) handle complex business logic and third-party API processing.
- **PDF Engine**: DomPDF
- **Payments Engine**: Stripe PHP SDK

---

## 🚀 Installation & Setup

Follow these simple steps to get the project running locally.

### 1. Clone the Project

Open your terminal and clone the repository:

```bash
git clone https://github.com/your-username/invoice-app.git
cd invoice-app
```

### 2. Install Dependencies

Install both PHP packages and Frontend assets:

```bash
composer install
npm install
```

### 3. Setup Environment Variables

Create your configuration file:

```bash
cp .env.example .env
php artisan key:generate
```

> [!IMPORTANT]
> Open the `.env` file and configure your **Database credentials**, **Stripe API Keys** (`STRIPE_KEY`, `STRIPE_SECRET`), and **Google/GitHub App Credentials**.

### 4. Database Setup

Run the migration files to construct the database tables, and run the storage link command so logo uploads work:

```bash
php artisan migrate
php artisan storage:link
```

### 5. Compile the UI & Run Server

Build the Tailwind CSS styling and start the PHP server:

```bash
npm run build
php artisan serve
```

Visit the application at `http://localhost:8000`.

---

## 🚀 Deployment Guide (Vercel)

Deploying this Laravel app to Vercel is highly streamlined! The `vercel.json` configuration and `api/index.php` entry points have already been added to this repository.

### Step 1: Pre-requisites

1. Ensure your code is pushed to a **GitHub repository**.
2. Create an account on [Vercel](https://vercel.com).
3. Have a functional **MySQL/PostgreSQL Database URL** (e.g., from an external service like PlanetScale, Supabase, or AWS) because Vercel is highly serverless.

### Step 2: Import Project to Vercel

1. On Vercel, click **Add New** -> **Project**.
2. Import your GitHub repository containing this invoice project.
3. Keep the "Framework Preset" as **Other**.

### Step 3: Configure Environment Variables

Before clicking "Deploy", expand the **Environment Variables** section and meticulously add all your `.env` variables from your local machine, especially:

- `APP_ENV=production`
- `APP_DEBUG=true`
- `APP_KEY` (Copy this from your local `.env`)
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Use your production database)
- `STRIPE_KEY` and `STRIPE_SECRET`

**Crucial Settings for Vercel:**
Add these specific variables to ensure views and sessions work on Vercel's serverless environment:

- `VIEW_COMPILED_PATH=/tmp`
- `CACHE_STORE=array`
- `SESSION_DRIVER=cookie`
- `LOG_CHANNEL=stderr`

### Step 4: Deploy

Click **Deploy**! Vercel will automatically look at `vercel.json`, use its PHP runtime to build the app and execute `npm run build` for Tailwind assets. Your project will be live in less than 2 minutes.

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
