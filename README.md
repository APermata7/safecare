# 🏠 SafeCare

**SafeCare** adalah aplikasi **crowdfunding berbasis website** yang dirancang khusus untuk membantu **panti asuhan** menggalang dana secara online. Website ini menyediakan platform bagi **donatur** untuk memberikan donasi kepada panti asuhan melalui sistem pembayaran digital yang aman dan terpercaya.

📌 **Note**: Aplikasi ini **tidak menggunakan teknologi blockchain**. Semua transaksi donasi dilakukan melalui **Midtrans Payment Gateway (Sandbox Mode)** sebagai simulasi pembayaran.

---

## 📌 Fitur Aplikasi

- 🏠 Registrasi & login untuk **Panti Asuhan**, **Donatur**, dan **Admin**
- 📋 Pengelolaan profil panti asuhan
- 💸 Donasi online via **Midtrans (Sandbox)**
- 📊 Dashboard transaksi & manajemen donasi oleh admin
- 📑 Riwayat donasi untuk donatur

---

## 🚀 Tech Stack

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-F72C1F?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" />
  <img src="https://img.shields.io/badge/Midtrans-00A9E0?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABrUlEQVQ4T62Tz0tCURjGf+fPmZmB0x0iCFIYWq2CEhF6RUrY4MvIBIShAFWkl0aoVogULCwsAksbEWpVEF6SCdTGFJ4Dojh3ryffnyPYT5q4Y5/nPe59z/n/xFwB1mGk9Y9ycYzaO+F6DRCcI2tWrV9ZIefNnd6t0b6NjVaqPpULp9gMcHLrb5PbA+BrQRETVjGGlvayzWTgNs6BgDy3t33UwaHpFr32cwNAHjrYe+oTbBKCkboDSHD4HIDhHKFjMzFgMQFb7FznMbArzOE+GuS1AFq2gH/2L9Iz7orUd3xDJMsoJqHxAABy0GlU8aRngBJK2HFiOHF++A1Y6VZBgu+FVrJODNUC+JWxB0ybfkFqF3Sfb/jZ1cnIC+a6ApN8oZOsKpI2IVTuBjYBK4JQbi3lgo5ZMG6E9UJ1WejEIRc0LCHD/NpHiHHpt0+I5kwAAAAAElFTkSuQmCC&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8892BF?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" />
</p>

---

## ⚙️ Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di komputer lokal:

```bash
# Clone repository
git clone https://github.com/APermata7/safecare.git
cd safecare

# Copy file konfigurasi environment
cp .env.example .env

# Install dependency backend
composer install

# Install dependency frontend
npm install

# Generate application key
php artisan key:generate

# Migrasi database & seeder data awal
php artisan migrate:fresh --seed

# Buat symbolic link ke storage
php artisan storage:link

# Jalankan server development
npm run dev
php artisan serve
```