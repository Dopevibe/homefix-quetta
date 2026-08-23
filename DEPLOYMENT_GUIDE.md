# HomeFix Quetta — Free Deployment Guide

This repository contains a standalone, production-ready PHP 8+ / MySQL application with zero npm/composer build steps required.

---

## 🎯 Option 1: InfinityFree (Recommended — 100% Free Forever with cPanel & MySQL)

InfinityFree provides free PHP 8.2 + MySQL hosting with phpMyAdmin, custom domain support, and free SSL.

### Step 1: Create an Account
1. Go to [https://www.infinityfree.com](https://www.infinityfree.com) and sign up for a free account.
2. Click **Create Account** and pick a free subdomain (e.g., `homefix-quetta.infinityfreeapp.com`) or connect your custom domain.

### Step 2: Create MySQL Database
1. In your InfinityFree Control Panel (cPanel), open **MySQL Databases**.
2. Create a database named `homefix_quetta` (it will prefix with your username, e.g. `epiz_12345678_homefix_quetta`).
3. Note your:
   - **MySQL Host**: (e.g. `sql123.infinityfree.com`)
   - **MySQL Database**: (e.g. `epiz_12345678_homefix_quetta`)
   - **MySQL Username**: (e.g. `epiz_12345678`)
   - **MySQL Password**: (your cPanel password)

### Step 3: Import Database Schema & Data
1. Click **phpMyAdmin** next to your database.
2. Click on the database name on the left sidebar.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `database/homefix_quetta.sql`.
5. Click **Go** / **Import** at the bottom.

### Step 4: Upload Application Files
1. Open the **Online File Manager** (or connect via FileZilla FTP).
2. Navigate to the `htdocs/` folder.
3. Upload and extract `homefix-quetta.zip` (or upload all files from `homefix-quetta-live/` directly into `htdocs/`).

### Step 5: Update Database Credentials (if not using env variables)
In `config/database.php`, your settings will look like:
```php
self::$host = 'sql123.infinityfree.com';
self::$db   = 'epiz_12345678_homefix_quetta';
self::$user = 'epiz_12345678';
self::$pass = 'YOUR_CPANEL_PASSWORD';
self::$port = 3306;
```
*(Or set environment variables in your server panel).*

### Step 6: Test Your Live Website
Visit your URL (e.g. `https://homefix-quetta.infinityfreeapp.com`) and test booking, tracking, and the admin panel (`/admin/login.php`).

---

## 🎯 Option 2: AlwaysData (Free 100MB Cloud Hosting with SSH & PHP 8.3)

1. Sign up at [https://www.alwaysdata.com](https://www.alwaysdata.com).
2. Go to **Databases > MySQL** and create database `homefix_quetta`. Import `database/homefix_quetta.sql` via phpMyAdmin.
3. Go to **Web > Sites**, set root directory to `/www`.
4. Upload files via FTP or Git into `/www`.
5. Configure DB credentials in `config/database.php`.

---

## 🎯 Option 3: Render / Railway (Containerized or PHP Native)

1. Create a free MySQL database on [Aiven.io](https://aiven.io) or [TiDB Cloud](https://tidbcloud.com) (free 5GB tier).
2. Push your codebase to a GitHub repository.
3. On Render.com or Railway.app, create a new Web Service linked to your repo.
4. Set Environment Variables:
   - `DB_HOST`: your remote MySQL host
   - `DB_NAME`: `homefix_quetta`
   - `DB_USER`: your remote MySQL user
   - `DB_PASS`: your remote MySQL password
   - `DB_PORT`: `3306`
5. Deploy!

---

## 🔐 Default Administrative Access
- **Admin Portal**: `/admin/login.php`
- **Email**: `admin@homefix.pk`
- **Password**: `Admin@123` *(Be sure to change this password in the admin panel after first deployment)*
