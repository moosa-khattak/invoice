# 💼 Professional Invoice Management System

A high-performance, enterprise-ready invoice management solution built with **Laravel 12** and **Tailwind CSS 4.0**. This application provides a comprehensive suite for billing, multi-channel payments, and financial auditing with a premium, glassmorphism-inspired UI.

---

## 🚀 Vision & Development Journey

This project was engineered to solve complex billing workflows through a systematic development process:

1.  **Project Inception**: Defined a scalable architecture using the **Repository & Service Pattern**.
2.  **Core CRUD**: Built dynamic invoice generation with real-time math for taxes, shipping, and discounts.
3.  **Security First**: Integrated **OAuth (Google/GitHub)** and manual Auth for multi-user support.
4.  **Payment Ecosystem**: Enabled **Stripe**, **Cash**, and **Bank Transfer** with automatic status transitions.
5.  **Financial Integrity**: Added **Refund** processing and a granular **Activity Timeline** for every invoice.
6.  **Enterprise Polish**: Implemented server-side **Pagination**, **PDF Exports**, and **QR Code** integration.

---

## ✨ Comprehensive Features

### 🔐 Security & Identity
- **Provider-based Auth**: Seamless integration with Laravel Socialite for Google and GitHub.
- **CSRF Protected**: All actions are secured against cross-site requests.
- **User-Specific Data**: Every user manages their own unique set of clients and invoices.

### 📝 Dynamic Invoicing
- **Intelligent Calculations**: Handles floating-point precision for rates and quantities.
- **Brand Identity**: Upload business logos that persist across web and PDF views.
- **Customizable Schemas**: Support for PO numbers, multiple shipping addresses, and flexible tax/discount rates.

### 💳 Payment & Treasury
- **Hybrid Payments**:
    - **Digital**: Stripe Payment Intents with real-time success callbacks.
    - **Manual**: Cash and Bank Transfer flows with manual status reconciliation.
- **Refund Management**: Fully audited refund process that restores balances and updates histories.
- **QR Code Scanning**: Each invoice generates a unique QR code for instant mobile verification.

### 📊 Audit & Tracking
- **Activity Timeline**: A dedicated history page replacing simple modals. It tracks every payment, refund, and status change with reference IDs and notes.
- **Paginated Lists**: High-performance lists for Invoices and History, ensuring the UI remains snappy even with thousands of records.

---

## 🛠️ Technical Deep Dive

### Architecture
- **Controllers**: Handle HTTP routing and request validation.
- **Services**: Encapsulate business logic (e.g., `InvoiceService` for complex math and file uploads).
- **Repositories**: Abstract database interactions, making it easy to swap data sources if needed.
- **Interfaces**: Ensure strict contracts between layers.

### UI/UX Design
- **Tailwind CSS 4.0**: Leveraging modern CSS variables and advanced utility classes.
- **Blade Templates**: Clean structural split between components and layouts.
- **Interactive Layers**: Subtle animations, hover effects, and a custom scrollbar for the timeline view.

---

## 📂 Project Structure

```text
├── app/
│   ├── Http/Controllers/    # Application logic (Invoice, Stripe, PDF)
│   ├── Interfaces/          # Repository contracts
│   ├── Repositories/        # Database query abstractions
│   ├── Services/            # Core business logic (Calculations, File handling)
│   └── Models/              # Database entities (Invoice, Transaction, Item)
├── resources/
│   ├── views/               # Blade templates (Invoices, History, Auth)
│   └── css/                 # Tailwind CSS entry point
├── public/
│   ├── js/                  # Frontend logic (Filtering, Interactivity)
│   └── storage/             # Business logos
└── routes/
    └── web.php              # Secure application routes
```

---

## ⚙️ Configuration (.env)

| Vital Key | Purpose |
| :--- | :--- |
| `STRIPE_KEY` / `STRIPE_SECRET` | Enables online credit card processing. |
| `GOOGLE_CLIENT_ID` / `SECRET` | Enables Google Social Login. |
| `GITHUB_CLIENT_ID` / `SECRET` | Enables GitHub Social Login. |
| `DB_CONNECTION` | Database driver (MySQL recommended). |

---

## 🚀 Quick Install

```bash
# Clone the repository
git clone https://github.com/moosa-khattak/invoice

# Install Dependencies
composer install
npm install

# Setup Key & Database
php artisan key:generate
php artisan migrate --seed

# Compiles assets
npm run build

# Start the application
php artisan serve
```

---

## 📄 License
This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
