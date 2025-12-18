# Mini Job Board Application

## Overview
A full-stack job board application connecting employers and applicants.
Built with **Laravel 11** (Backend) and **Vue 3** (Frontend).

## Tech Stack
- **Backend**: Laravel 11, Sanctum (Auth), SQLite (Database)
- **Frontend**: Vue 3, Pinia (State), Vue Router, Tailwind CSS, Axios

## Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm

### Backend Setup
1. Navigate to `backend` directory.
2. Install dependencies: `composer install`
3. Setup Environment: `cp .env.example .env` (Edit DB connection if not using SQLite/defaults)
4. Generate Key: `php artisan key:generate`
5. Run Migrations: `php artisan migrate`
6. Link Storage: `php artisan storage:link`
7. Start Server: `php artisan serve`

### Frontend Setup
1. Navigate to `frontend` directory.
2. Install dependencies: `npm install`
3. Start Dev Server: `npm run dev`

## Features
- **Authentication**: Register/Login as Employer or Applicant via Laravel Sanctum.
- **Roles & Permissions**: Middleware-protected routes for role-specific actions.
- **Job Management (Employers)**: Create, Edit, Delete, and View own job listings with pagination.
- **Job Browsing (Applicants)**: Paginated list of all open jobs.
- **Job Applications**: Applicants can apply with a message and resume upload (file).
- **Application Management**: Employers can view a list of applicants for each of their jobs.
- **Real-time Feedback**: Custom toast notification system (Success/Error).
- **Email Notifications** (Bonus): Automatic emails to employers when someone applies.
- **Pagination** (Bonus): 9 items per page for both job board and dashboard.
- **File Upload** (Bonus): PDF/DOC resume support.

## Environment Configuration (Crucial)
### File Storage
Run `php artisan storage:link` inside the `backend` folder to enable resume downloads/viewing.

### Email Settings
By default, `MAIL_MAILER=log`. Emails appear in `storage/logs/laravel.log`.
To send real emails (e.g., via Gmail), update your `.env`:
- `MAIL_MAILER=smtp`
- `MAIL_ENCRYPTION=tls`
## Postman Collection
You can view and test the API endpoints using the following Postman Collection link:
[View Postman Collection](https://winter-firefly-8136300.postman.co/workspace/Kwek-Chee-Hao's-Workspace~72d094ea-e517-46e7-830c-e16cd11aacc5/request/50902096-a9566637-cdc9-40b7-925c-36a09845014c?action=share&creator=50902096&ctx=documentation)


## Technical Decisions & Reflections

### 🏗 Backend Architecture & Security
- **Domain-Driven Middleware**: Instead of cluttering controllers with authorization checks, I implemented a dedicated `EnsureUserHasRole` middleware. This ensures that the security layer is decoupled from the business logic, allowing for cleaner code and easier testing.
- **Stateless Authentication**: Chose **Laravel Sanctum** for its lightweight nature. It provides a robust token-based system that is perfect for SPA (Single Page Application) communication without the overhead of Oauth2.
- **Relational Integrity**: The database schema uses strict foreign key constraints (SQLite) to ensure that orphan applications cannot exist if a job or user is deleted (`onDelete('cascade')`).

### 🎨 Frontend Logic & State Management
- **State Centralization with Pinia**: Authentication state (user role, tokens) and the global Notification system are managed via Pinia. This avoids "prop drilling" and ensures that if a user logs out in one component, the entire UI responds instantly.
- **Composition API**: Leveraged Vue 3's `script setup` syntax for better logic grouping and code readability compared to the older Options API.
- **Tailwind CSS v4 Migration**: One specific technical decision was adopting the latest **Tailwind v4**. While it introduced configuration challenges (PostCSS integration), it resulted in a faster build process and a more modern, CSS-variable-based design system.

### 🧩 Problem Solving & Challenges
- **Real-time UX**: Replaced native browser `alert()` calls with a custom-built **Toast Notification system**. This significantly improves the professional feel of the app and handles backend validation errors (422) by parsing them into human-readable messages.
- **Environment Resilience**: Solved common deployment hurdles by implementing clear error handling for SMTP and ensuring that complex `.env` passwords (containing whitespace) are handled via proper quoting—a common pitfall in local development.


---
*Developed as part of the Full Stack Engineer Technical Assessment.*
