# Post Management Application – Multi-Tenant CMS

## 📌 Overview
The Post Management Application is a robust, multi-tenant content management system (CMS) designed for modern web projects. Built with Laravel (backend), Vue.js (frontend via Inertia.js), and Tailwind CSS, it enables organizations to manage posts efficiently across multiple subdomains (tenants). Administrators can register new tenants, each with isolated data and customizable branding.

> **Note:** This project was created as part of an assignment and is intended for educational purposes only.

---

## ✨ Key Features

- **Multi-Tenant Architecture:** Supports multiple organizations via subdomains, ensuring data isolation and security.
- **Post CRUD Operations:** Create, edit, update, and delete posts with rich text content and featured images.
- **Image Upload & Preview:** Upload featured images for posts with instant preview functionality.
- **Responsive UI:** Tailwind CSS ensures a seamless experience across devices.
- **Real-Time Validation:** Instant feedback on form inputs for improved usability.
- **Role-Based Access:** Admins manage tenants; users manage posts within their tenant.
- **Secure Storage:** Images and files are stored securely using Laravel's public disk.
- **Scalable Database:** MySQL powers reliable data storage for tenants and posts.
- **Clean & Intuitive Interface:** User-friendly design for both admins and regular users.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12+
- **Frontend:** Vue.js 3 + Inertia.js
- **Styling:** Tailwind CSS
- **Database:** MySQL
- **Image Storage:** Laravel Storage (public disk)
- **Authentication:** Laravel Sanctum (API Token-based Authentication)

---

## 📂 Installation & Setup

To run the project locally, follow these steps:

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/SherryAppsQueeze/Multi-Tenant-Cms
cd Multi-Tenant-Cms
````

### 2️⃣ Install PHP Dependencies

```bash
composer install
```

### 3️⃣ Install JavaScript Dependencies

```bash
npm install
```

### 4️⃣ Configure Environment

Copy `.env.example` to `.env` and update database and storage settings:

```bash
cp .env.example .env
```

Edit `.env`:

```
APP_NAME="Post Management CMS"
APP_URL=http://app.domain.com
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5️⃣ Generate App Key

```bash
php artisan key:generate
```

### 6️⃣ Run Migrations & Seed Data

```bash
php artisan migrate --seed
```

### 7️⃣ Storage Link

```bash
php artisan storage:link
```

### 8️⃣ Run Development Servers

**Laravel Backend:**

```bash
php artisan serve
```

**Vite Frontend:**

```bash
npm run dev
```

---

## 🌐 Multi-Tenant Setup

* **Main Domain:** `app.domain.com` (Primary tenant app)
* Create additional subdomains for each tenant, e.g.:

  * `tenant1.app.domain.com`
  * `tenant2.app.domain.com`
* Point these subdomains to your Laravel application root in your hosting control panel.

---

## 👤 Author

**Name:** Shaharyar Shafiq
**Role:** Full Stack Developer
**Email:** [ranashaharyar625@gmail.com](mailto:ranashaharyar625@gmail.com)
**Phone:** +92 335 9493868

---

## 📜 License

This project is open for educational use only and can be freely used for learning purposes.
