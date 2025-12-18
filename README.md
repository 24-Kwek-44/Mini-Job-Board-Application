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
- Wrap passwords in quotes: `MAIL_PASSWORD="your password"`

## API Testing
Import `postman_collection.json` into Postman. 
1. Register/Login to get a Bearer token.
2. Use the token in the **Authorization** tab for protected requests.
