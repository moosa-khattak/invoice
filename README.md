# 🧾 Invoice Management System

A full-featured invoice management web application built with **Laravel 12**, designed to help businesses create, manage, and track invoices with integrated payment processing, PDF generation, and multi-currency support.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Architecture](#project-architecture)
- [Getting Started](#getting-started)
- [Step-by-Step Walkthrough](#step-by-step-walkthrough)
- [Authentication](#authentication)
- [Payments](#payments)
- [PDF Export & QR Code](#pdf-export--qr-code)
- [Invoice History & Pagination](#invoice-history--pagination)
- [Known Challenges & Troubleshooting](#known-challenges--troubleshooting)

---

## ✨ Features

- **Invoice CRUD** – Create, read, update, and delete invoices
- **Multi-User Security** – Users can only view and manage their own invoices
- **Multi-Currency Support** – Select from multiple currencies per invoice
- **Floating-Point Precision** – All monetary values stored and displayed with 2-decimal accuracy
- **Status Management** – Dynamic invoice statuses: `Unpaid`, `Partial`, `Paid`, `Refunded`
- **Authentication** – Manual registration, Google OAuth, and GitHub OAuth
- **Payment Processing** – Cash, Bank Transfer, and Stripe (card) payments
- **Partial Payments** – Record partial payments and track balance due
- **Refund System** – Issue refunds with full audit trail
- **PDF Export** – Download invoices as professionally formatted PDFs with QR codes
- **Activity History** – Per-invoice transaction timeline on a dedicated page
- **Pagination** – All invoices and transaction history are paginated

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade Templates, Tailwind CSS, Vanilla JS |
| Database | MySQL |
| Authentication | Laravel Breeze / Socialite (Google, GitHub) |
| Payments | Stripe, Cash, Bank Transfer |
| PDF Generation | DomPDF (Laravel-DomPDF) |
| QR Code | SimpleSoftwareIO/simple-qrcode |

---

## 🏗 Project Architecture

The application follows a clean **Repository Pattern** to separate data access from business logic.

```
app/
├── Http/
│   ├── Controllers/        # InvoiceController, PDFController, etc.
│   ├── Requests/           # Form validation (InvoiceRequest)
├── Interfaces/             # InvoiceRepositoryInterface
├── Repositories/           # InvoiceRepository (data access layer)
├── Services/               # InvoiceService (business logic)
├── Models/                 # Invoice, InvoiceTransaction, User
resources/
├── views/
│   ├── invoice.blade.php        # Create Invoice
│   ├── editinvoice.blade.php    # Edit Invoice
│   ├── showinvoice.blade.php    # View Single Invoice
│   ├── allinvoice.blade.php     # View All Invoices
│   ├── invoice_history.blade.php # Per-Invoice Activity History
│   ├── partials/                # Reusable modals and fragments
public/
├── js/
│   ├── invoice.js           # Real-time calculations, currency, rows
│   ├── allInvoiceFilter.js  # Client-side search filtering
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

### Installation

```bash
# 1. Clone the repository
git clone <your-repo-url>
cd invoice

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Copy environment file and configure it
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
DB_DATABASE=invoice
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations and seed currencies
php artisan migrate --seed

# 7. Link storage for logo uploads
php artisan storage:link

# 8. Start the development server
composer run dev
```

### Environment Variables

Add these to your `.env` for full functionality:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# GitHub OAuth
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

---

## 📖 Step-by-Step Walkthrough

### 1. Project Structure
The project was initialized as a standard Laravel application. The repository pattern was set up early to cleanly separate concerns between the controller (HTTP layer), service (business logic), and repository (database queries).

### 2. Create an Invoice
- Navigate to the home page (`/`) to access the **Create Invoice** form.
- Add client details, select a currency, add line items with quantity and price.
- Real-time calculations update the subtotal, tax, discount, and total automatically via JavaScript.
- All monetary values support floating-point precision (e.g., `12.50`).

### 3. CRUD Operations

| Operation | Route | Description |
|---|---|---|
| **Create** | `POST /invoice` | Save a new invoice |
| **Read (Single)** | `GET /invoice/{id}` | View a specific invoice |
| **Read (All)** | `GET /allinvoices` | View all your invoices (paginated) |
| **Edit** | `GET /invoice/{id}/edit` | Edit an existing invoice |
| **Update** | `PUT /invoice/{id}` | Save edits to an invoice |
| **Delete** | `GET /invoice/{id}/delete` | Delete an invoice |

### 4. Data Isolation
Each invoice is stored with a `user_id` foreign key. All queries in the repository are scoped to the authenticated user: `where('user_id', Auth::id())`. This prevents any user from accessing another user's invoices.

---

## 🔐 Authentication

The application supports three ways to log in:

1. **Manual Registration** – Standard email/password sign-up at `/register`.
2. **Google OAuth** – Click "Login with Google" and authorize via your Google account.
3. **GitHub OAuth** – Click "Login with GitHub" and authorize via your GitHub account.

All three methods create or retrieve a user record and start an authenticated session.

---

## 💳 Payments

Multiple payment methods are supported:

### Cash Payment (`/invoice/{id}/cash`)
- Record a cash payment amount.
- The system auto-calculates the new balance due.
- Invoice status updates to `Partial` or `Paid` accordingly.

### Bank Transfer
- From the "All Invoices" page, click the status badge on an `Unpaid` or `Partial` invoice.
- Select `Paid`  from the dropdown to manually update the status after receiving a bank transfer.
- A transaction record is created in the activity history.

### Stripe (Card Payment)
- After saving an invoice, choose "Pay via Stripe".
- Enter card details on the Stripe-hosted payment form.
- On success, the invoice status updates to `Paid` and the transaction is logged with the Stripe transaction ID.

---

## 📄 PDF Export & QR Code

- From the single invoice view, click **Download PDF**.
- The PDF is generated by the `DomPDF` library and includes:
  - All invoice details and line items
  - Company logo (if uploaded)
  - A QR code generated by `simple-qrcode` linking back to the invoice

---

## 📜 Invoice History & Pagination

- On the "All Invoices" page, click the **clock icon** next to any invoice.
- This opens a dedicated **Activity History** page at `/invoice/{id}/history`.
- The timeline shows all payments and refunds with their dates, amounts, method, and reference IDs.
- The history is paginated (5 transactions per page).

---

## ⚠️ Known Challenges & Troubleshooting

These are real problems encountered and solved during development — documented here to help others facing the same challenges.

### 🔑 Google & GitHub Authentication
**Problem:** After OAuth callback, users were not being found or created correctly, leading to login failures.
**Solution:** Ensured the `Socialite` `findOrCreate` logic matched on email. For GitHub users who may not have a public email, a fallback was implemented using their GitHub username.

### 🔒 User Data Isolation
**Problem:** Early versions allowed any authenticated user to view any invoice by guessing the invoice number in the URL.
**Solution:** All repository queries were wrapped with `where('user_id', Auth::id())`. The `getByInvoiceNumber` method now automatically filters by the logged-in user, returning a 404 if the invoice doesn't belong to them.

### 💳 Stripe Integration
**Problem:** Stripe payment intents were created but not confirmed correctly, leading to payments being stuck in a `pending` state.
**Solution:** The webhook listener was properly configured to listen for `payment_intent.succeeded` events, which then update the invoice status and log the transaction.

### 📄 PDF Generation
**Problem:** The DomPDF library failed to render certain CSS properties and the QR code was not embedding correctly.
**Solution:** Switched to using inline styles within the PDF Blade template. The QR code was embedded as a base64-encoded PNG using `QrCode::format('png')->generate(...)`.

### 🗃 Database Relationships
**Problem:** `Invoice` had items stored as a JSON column initially, causing issues with querying and eager loading.
**Solution:** Migrated to a proper `invoice_items` table with a one-to-many relationship. An `invoice_transactions` table was added to support the payment history audit trail.

### 💸 Refund Process
**Problem:** Stripe refunds are processed asynchronously, causing a UI lag where the invoice still showed as `Paid` right after a refund was initiated.
**Solution:** The refund is processed synchronously via the Stripe API and the invoice status is updated to `Refunded` immediately before returning the redirect response.

---

## 📞 License

This project is open-source and built for educational and portfolio purposes.
