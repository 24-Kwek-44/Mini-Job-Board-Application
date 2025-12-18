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
- **Authentication**: Register/Login as Employer or Applicant.
- **Employers**: Post, Edit, Delete Jobs. View Applications.
- **Applicants**: Browse Jobs, Apply (with message & resume upload).

## API Testing
Import `postman_collection.json` into Postman to test API endpoints.
