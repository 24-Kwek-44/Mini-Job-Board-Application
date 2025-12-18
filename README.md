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


## Implementation Details & Reflections

### 🧱 Backend Architecture (Laravel)

#### Authentication & Authorization
The backend uses **Laravel Sanctum** to handle SPA token authentication. Sanctum provides a lightweight, secure token-based system that works well with a frontend SPA like Vue, without the complexity of OAuth2. This allows users to log in, stay authenticated, and make API calls securely. 

#### Role Management
There are two roles: **employer** and **applicant**.
Role validation is enforced through custom middleware (`EnsureUserHasRole`) so that only employers can access job management routes, and only applicants can apply for jobs. Offloading role checks to middleware keeps controllers clean and focused on business logic. 

#### Jobs & Applications Data Model
Jobs have fields such as title, description, location, salary_range, is_remote, and status. Applicants can submit a job application with a message and a file (resume). Eloquent relationships enforce integrity (e.g., applications belong to jobs and users). 

#### Validation & Error Handling
Each API endpoint includes validation for required fields and formats. Errors (422 validation, 401 unauthorized, etc.) return standardized JSON responses so the frontend can parse and display friendly messages. 

#### Bonus Features
- **Pagination**: Implemented on job listings to improve performance and client UX.
- **File Upload**: Applicants can upload resumes (PDF/DOC).
- **Email Notifications**: Employers receive notifications when new applications arrive. 

### 🖥️ Frontend Architecture (Vue 3 + Pinia + Tailwind)

#### State Management
**Pinia** manages global state for authentication (tokens, user role), notifications, and job lists. This central store prevents prop drilling and ensures UI consistency (e.g., logging out clears relevant state everywhere). 

#### Composition API
Vue 3’s `script setup` and Composition API were used throughout to organize logic clearly and follow modern best practices. Components are more readable, reusable, and easier to test. 

#### Routing & Guards
**Vue Router** handles navigation between public and protected pages. Navigation guards check auth status and roles before allowing access to certain dashboards or actions. 

#### UI & UX Enhancements
- **Tailwind CSS**: Enables utility-first styling for responsive layouts.
- **Custom Toast System**: A custom Toast notification system replaces `alert()` calls to provide smooth feedback (e.g., form validation errors, success messages). 

### 📦 API Contract & Conventions

To support clean integrations and predictable client behavior:
- **REST Conventions**: Use standard HTTP verbs (GET, POST, PUT, DELETE) and follow resource naming conventions (e.g., `/jobs`, `/applications`). 
- **JSON Responses**: Responses always include status, message, and optionally data fields so clients can easily parse success vs error payloads. 
- **Error Shape**: Validation failures return a structured list to inform UI form states. 

### 📈 Testing, Quality & Maintainability

While unit and feature tests are not currently included, the code structure was designed to make adding them easier:
- Clear separation of concerns through middleware and route groups.
- Using Pinia stores for discrete logic boundaries.
- Modular Vue components for reusability.

These choices help future test suites run with minimal overhead.

### 🚧 Challenges & Solutions

#### Authentication Cross-Domain
Handling Sanctum sessions with API calls from the frontend required proper CORS and cookie configurations. A common pitfall was missing credential flags in Axios requests — resolved by enabling `axios.defaults.withCredentials`. 

#### File Upload & Storage
Resume uploads required setting up `php artisan storage:link` so files are publicly accessible while still secure. 

#### Notification UX
Native browser alerts were replaced with a Toast system using Pinia and global components to improve the professional feel and reduce disruptive dialogues. 

### 📌 Future Improvements

Here are possible extensions if time allowed:
- **Automated Tests**: Backend feature tests (Laravel) and frontend component tests (Vitest).
- **Search & Filters**: Allow filtering jobs by location, remote status, salary range.
- **Resume Parsing**: Extract skills or keywords from uploaded resumes.
- **Deployment Workflows**: Add CI/CD pipelines to GitHub Actions to automate tests and deployment.
- **Role-Based Dashboards**: Personalized UI experiences with analytics per user role.

---
