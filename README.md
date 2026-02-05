# 📝 Laravel Blog - Microservices Architecture Simulation

A modern blog application built with **Laravel 12** and **Vue 3** that simulates a microservices architecture within a monolithic application. This project demonstrates how to structure a Laravel application with clear service boundaries, inter-service communication patterns, and a gateway API layer.

## 🎯 What This Project Represents

This is an **educational project** that simulates microservices architecture patterns in a monolithic Laravel application. It showcases:

- **Service-Oriented Architecture**: Clear separation between Auth, Posts, Comments, and RBAC services
- **API Gateway Pattern**: Single entry point for all client requests via `/api/gateway`
- **Service Mesh Simulation**: Inter-service communication using a custom `ServiceMessenger` class
- **DTO Pattern**: Type-safe data transfer objects for all service operations
- **Proxy Pattern**: Service proxies that abstract internal service communication
- **JWT Authentication**: Custom token-based authentication without Laravel Sanctum/Passport
- **RBAC System**: Role-Based Access Control with permissions and content-level access
- **Modern Frontend**: Vue 3 with Composition API, Tailwind CSS 4, and Vite

### 🏗️ Architecture Overview

```
┌─────────────┐
│   Vue 3 SPA │ (Frontend)
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│   API Gateway       │ (/api/gateway/*)
│  (Laravel Routes)   │
└──────┬──────────────┘
       │
       ▼
┌──────────────────────────────────────┐
│        Service Messenger             │
│   (Inter-Service Communication)      │
└────┬─────────┬─────────┬────────┬────┘
     │         │         │        │
     ▼         ▼         ▼        ▼
┌────────┐ ┌───────┐ ┌──────────┐ ┌──────┐
│  Auth  │ │ Posts │ │ Comments │ │ RBAC │
│Service │ │Service│ │ Service  │ │Service│
└────────┘ └───────┘ └──────────┘ └──────┘
```

Each service has its own:
- **Models**: Data layer (Eloquent)
- **DTOs**: Request/Response data structures
- **Service Layer**: Business logic
- **Proxy**: External interface for other services

## ✨ Features

- 🔐 Custom JWT authentication system
- 📄 CRUD operations for blog posts
- 💬 Comments system
- 👥 Role-Based Access Control (RBAC)
- 🎨 Modern UI with Tailwind CSS 4
- 🔄 Real-time data updates
- 📱 Responsive design
- 🧪 Microservices simulation

## 🛠️ Tech Stack

**Backend:**
- Laravel 12
- PHP 8.2+
- SQLite
- Custom JWT implementation

**Frontend:**
- Vue 3 (Composition API)
- Vite 7
- Tailwind CSS 4
- Axios

## 📋 Prerequisites

Before you begin, ensure you have installed:

- **PHP 8.2** or higher
- **Composer**
- **Node.js 18+** and **npm**
- **SQLite**

## 🚀 Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/vmleroy/blog-laravel-vue.git
cd blog-laravel-vue
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Environment configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database setup

```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

This will create:
- Sample users with different roles (admin, editor, viewer)
- Blog posts with authors
- Comments on posts
- RBAC roles and permissions

### 6. Start the development servers

You'll need **two terminal windows**:

**Terminal 1 - Laravel Backend:**
```bash
php artisan serve
```
Backend will run on: `http://localhost:8000`

**Terminal 2 - Vite Frontend:**
```bash
npm run dev
```
Frontend assets will be served via Vite

### 7. Access the application

Open your browser and navigate to:
```
http://localhost:8000
```

## 👤 Default Users

After seeding, you can login with:

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password123 | Admin
| joao@example.com  | password123 | User
| maria@example.com | password123 | User

## 📁 Project Structure

```
app/
├── DTOs/                    # Data Transfer Objects
│   ├── Requests/            # Request DTOs for each service
│   └── Responses/           # Response DTOs
│
├── Http/
│   ├── Controllers/
│   │   └── Gateway/         # API Gateway Controllers
│   ├── Middleware/          # Custom middleware (JWT auth)
│   └── Requests/            # Custom form requests
│
├── Models/                  # Eloquent Models organized by service
│   ├── Auth/
│   ├── Comments/
│   ├── Posts/
│   └── RoleBasedAccess/
│
├── Providers/
│   └── MessengerServiceProvider.php  # Service registration
│
└── Services/                # Service Layer (business logic)
    ├── Auth/
    ├── Comments/
    ├── MessageQueue/        # ServiceMessenger implementation
    ├── Posts/
    ├── Proxies/             # Service Proxies
    └── RoleBasedAccess/

resources/
├── js/
│   ├── api/                 # API client (gatewayApi.js)
│   ├── components/          # Vue components
│   ├── composables/         # Vue composables
│   └── pages/               # Vue pages
│
└── views/                   # Blade template (SPA entry point)

routes/
├── api.php                  # Standard API routes
├── gateway.php              # Gateway API routes
└── web.php                  # Web routes
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Acknowledgments

- Built with [Laravel](https://laravel.com) framework
- Frontend powered by [Vue.js](https://vuejs.org)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Inspired by microservices architecture patterns
