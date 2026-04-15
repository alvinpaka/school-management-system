# EduManage Pro - Technical Documentation

EduManage Pro is a premium, modern School Management System (SMS) designed to streamline academic operations, financial management, and communication within educational institutions. Built with Laravel 13, Vue 3, and Inertia.js, it offers a seamless, single-page application (SPA) experience with a robust backend and a visually stunning frontend.

---

## 🛠️ Tech Stack

### Backend
- **Framework**: [Laravel 13.x](https://laravel.com)
- **Language**: PHP 8.2+
- **Authentication**: Laravel Sanctum / Breeze (Inertia flavor)
- **Authorization**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **PDF Generation**: [dompdf (via barryvdh/laravel-dompdf)](https://github.com/barryvdh/laravel-dompdf)
- **Caching**: Redis / File-based (via Laravel Cache)

### Frontend
- **Framework**: [Vue 3](https://vuejs.org) (Composition API)
- **Infrastructure**: [Inertia.js](https://inertiajs.com) (Server-side routing for SPAs)
- **Build Tool**: [Vite](https://vitejs.dev)
- **Styling**: [Tailwind CSS 3.x/4.x](https://tailwindcss.com)
- **UI Components**: [shadcn-vue](https://www.shadcn-vue.com) & [Lucide Icons](https://lucide.dev)
- **State Management**: Reactive Vue props via Inertia shared data

---

## 🏗️ Core Architecture

### Backend Design Pattern
The application follows a **Controller-Service-Model** architecture to ensure a clean separation of concerns and high maintainability.

-   **Controllers** (`app/Http/Controllers`): Responsible for handling incoming requests, performing basic validation, and returning Inertia responses.
-   **Services** (`app/Services`): Contains the core business logic. Each module (Student, Teacher, Fee, etc.) has its own service class that extends a `BaseService`.
-   **Models** (`app/Models`): Standard Eloquent models representing the database schema and relationships.
-   **BaseService**: Implements standardized caching logic using a `remember` helper, improving application performance for frequently accessed data.

### Frontend Integration
-   **Inertia.js**: Acts as the bridge between Laravel and Vue. Instead of building a separate API, controllers return `Inertia::render`, which sends data to specific Vue components located in `resources/js/Pages`.
-   **Layouts**: Shared application structure is handled in `resources/js/Layouts/AuthenticatedLayout.vue`.
-   **Shared Data**: Critical data like user profile, roles, and permissions are shared globally via `HandleInertiaRequests.php` middleware, making them accessible in any Vue component via `$page.props.auth`.

---

## 📊 Database & Data Models

### Key Entities & Relationships
1.  **User**: Core identity model. Has one `Student`, `Teacher`, or `ParentUser`.
2.  **Student**: Belongs to an `AcademicClass` and a `Section`. Has many `Grades`, `Attendances`, and `Fees`.
3.  **Teacher**: Manages many `AcademicClasses` (Class Teacher) and specializes in multiple `Subjects`.
4.  **AcademicClass**: The primary academic grouping (e.g., S1, S2). Contains many `Students` and `Sections`.
5.  **Subject**: Linked to classes and teachers. Core unit of the academic curriculum.
6.  **Exam & Grade**: Exams are scheduled events; Grades are the scores achieved by students for specific subjects in an exam.
7.  **Fee**: Financial records linked to students, tracking amount, status (paid/pending), and payment dates.
8.  **Notice**: School-wide or role-specific announcements.

---

## 👥 User Roles & Permissions

The system uses a granular Role-Based Access Control (RBAC) system powered by Spatie.

| Role | Responsibility | Key Permissions |
| :--- | :--- | :--- |
| **Super Admin** | Full System Control | All permissions |
| **Admin** | School Operations | Manage students, teachers, classes, and settings |
| **Teacher** | Academic Mgmt | View students, mark attendance, enter grades |
| **Accountant** | Financial Mgmt | Manage fees, view financial reports |
| **Librarian** | Library Mgmt | Manage books, track borrowings |
| **Receptionist** | Front Desk | View students/teachers, manage notices |
| **Parent** | Child Monitoring | View own children's grades, attendance, and fees |
| **Student** | Personal Portal | View own profile, grades, and subjects |

---

## 🚀 Key Modules

### Academic Management
-   **Classes & Sections**: Hierarchical grouping of students. Teachers can be assigned as "Class Teachers" to specific classes.
-   **Subject Catalog**: Comprehensive list of subjects linked to academic levels.
-   **Timetable**: (Integrated in `TimetableController`) Visual schedule management for classes.

### Student & Teacher Management
-   **Profiles**: Detailed record-keeping including contact info, academic history, and specialization (for teachers).
-   **Enrollment**: Streamlined process for admitting students and assigning them to classes.

### Examination & Grading
-   **Exam Scheduling**: Create and manage exam periods.
-   **Grade Entry**: Optimized interface for teachers to enter marks for their respective subjects.
-   **Performance Tracking**: Historical grade data stored for every student.

### Financial Management (Fees)
-   **Fee Tracking**: Automated fee generation for students.
-   **Status Monitoring**: Real-time status of payments (Paid, Pending, Overdue).
-   **Accountant Dashboard**: Specialized view for monitoring total collections and outstanding balances.

---

## ✨ Special Features

### 📄 Dynamic Report Card Generation
Using `dompdf`, the system generates professional, print-ready PDF report cards.
-   **Real-time Previews**: Students and parents can view report cards directly in the browser.
-   **Bulk Generation**: Admins can generate report cards for an entire class with a single click.

### 📱 WhatsApp Integration
A unique feature allowing admins to share report cards and important notices directly to parents' WhatsApp numbers.
-   **Direct Link Generation**: Generates a pre-filled WhatsApp message with the report card details.

### 📊 Role-Based Dashboards
Seven distinct dashboard views (Admin, Accountant, Librarian, etc.) provide curated widgets and real-time statistics tailored to the user's role.

---

## 🛠️ Development Workflow

### Installation
1.  **Clone & Install**: `composer install` & `npm install`.
2.  **Environment**: `cp .env.example .env` and configure database.
3.  **Database**: `php artisan migrate --seed` to initialize the database with roles, permissions, and sample data.
4.  **Build**: `npm run build` or `npm run dev`.

### Coding Standards
-   **Backend**: Follows [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
-   **Frontend**: Uses Vue 3 **SFC (Single File Components)** with `<script setup>`.
-   **CSS**: Predominantly Tailwind utility classes. Avoid custom CSS unless absolutely necessary (defined in `index.css`).

---

## 🌐 Deployment & Optimization

### Production Requirements
-   **Server**: Nginx/Apache with PHP 8.2+ and Node.js.
-   **Database**: MySQL 8.0 or PostgreSQL.
-   **Cache**: Redis is highly recommended for `DashboardService` caching.

### Optimization Strategy
-   **Route Caching**: `php artisan route:cache`.
-   **Config Caching**: `php artisan config:cache`.
-   **Vite Build**: `npm run build` ensures minified, fingerprinted assets for production.
-   **Service Layer Caching**: Uses `BaseService` remember logic to minimize database hits on heavy pages like the Dashboard.
