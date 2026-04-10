# Skeleton

# Laravel Backend – Subdomain-Based Setup with Herd

This project is a basic Laravel API backend configured for local development with [Laravel Herd](https://laravel.com/docs/herd). It is intended to be used alongside a frontend hosted on a sibling subdomain of the same top-level domain.

---

## 🧱 Project Structure

- **Backend (this app):** `http://skeleton-api.test`
- **Frontend:** `http://applicatie.skeleton-api.test:3000`
- **Top-level Domain:** `skeleton-api.test`

Both apps are served locally via Herd using `.test` domains.

---

## 🚀 Getting Started

### 1. Clone the Repository

(ssh)
```bash
git clone git@bitbucket.org:encore-nl/skeleton-api.git
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Create and Configure `.env`

```bash
cp .env.example .env
```
Update the following variables in `.env` to reflect your subdomain setup:

```dotenv
APP_ENV=local
DB_DATABASE=skeleton
DB_USERNAME=...
DB_PASSWORD=...

# CORS
FRONTEND_URL=http://applicatie.skeleton-api.test:3000
# This value determines the domain and subdomains the session cookie is available to
SESSION_DOMAIN=skeleton-api.test
# Requests from the following domains / hosts will receive stateful API authentication cookies.
SANCTUM_STATEFUL_DOMAINS=applicatie.skeleton-api.test:3000
```

#### 🧠 Variable Reference

| Variable | Description |
|---------|-------------|
| `FRONTEND_URL` | Full URL to your frontend subdomain. Used in CORS and redirects. |
| `SESSION_DOMAIN` | Shared domain for Laravel cookies. |
| `SANCTUM_STATEFUL_DOMAINS` | List of frontend domains allowed to make stateful API requests (used by Sanctum for cookie-based auth). |

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Run Migrations

```bash
php artisan migrate
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🧹 Common Issues

| Issue | Fix                                                                          |
|-------|------------------------------------------------------------------------------|
| CORS errors | Double-check `FRONTEND_URL` and `config/cors.php`                            |
| CSRF mismatch | Ensure frontend sends `X-XSRF-TOKEN` and fetches `sanctum/csrf-cookie` first |
| Session not persisting | Verify `SESSION_DOMAIN` and cookies                                          |
| 401 Unauthorized | Check Sanctum stateful domains and credentials headers                       |

---

## 📚 Useful Commands

```bash
php artisan route:list       # List all API routes
php artisan migrate:fresh    # Reset and re-run all migrations
php artisan db:seed          # Seed the database
php artisan tinker           # Interactively test application logic
```

## Cron Jobs

Below is a table with processes that should run. Make sure to configure these in K8s.

| Command                                         | .env key  | Interval    | Enviroment |
|-------------------------------------------------|-----------|-------------|------------|
