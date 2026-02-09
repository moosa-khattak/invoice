# Professional Invoice Generator

A modern, robust, and feature-rich invoice management system built with Laravel. This application allows users to create, manage, and download professional invoices with ease.

## 🚀 Features

- **Logo Management**: Upload your business logo with live preview and automatic storage.
- **Dynamic Invoicing**: Add or remove rows dynamically for products and services.
- **Smart Calculations**: Automatic real-time calculation of subtotal, discounts, taxes, shipping, and balance due.
- **Multi-Currency Support**: Choose your preferred currency for each invoice.
- **PDF Generation**: High-quality PDF exports powered by DomPDF, optimized for A4 printing.
- **History Management**: Keep track of all your invoices in a clean, searchable dashboard.
- **Responsive Design**: Built with Tailwind CSS for a seamless experience across all devices.

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS, Vanilla JavaScript
- **Database**: MySQL
- **PDF Core**: barryvdh/laravel-dompdf

## 📦 Installation

To get this project running locally, follow these steps:

1. **Clone the repository**

    ```bash
    git clone [repository-url]
    cd invoice
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install && npm run build
    ```

3. **Configure Environment**
    - Copy `.env.example` to `.env`
    - Configure your database settings in `.env`
    - Set `APP_URL=http://localhost:8000`

4. **Initialize Database & Storage**

    ```bash
    php artisan key:generate
    php artisan migrate
    php artisan storage:link
    ```

5. **Run the Application**
    ```bash
    php artisan serve
    ```

## 📝 Usage

1. Navigate to `http://localhost:8000` to create your first invoice.
2. Upload your logo and fill in the sender/receiver details.
3. Add items, quantities, and rates.
4. Save the invoice and click "Download PDF" to generate a professional document.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
