 🩺 ProDoctors — Medical Portal & Doctor Appointment Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11%2B%20%2F%2012%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge" alt="Status" />
</p>

**ProDoctors** is a comprehensive healthcare platform connecting patients, verified medical specialists, and clinics. Inspired by portals like *ProDoctorov*, it empowers patients to find verified doctors, book appointments, read authenticated patient reviews, and access medical consultation tools.

---

## 🌟 Key Features

### 👤 1. Patients & Public Portal
- **Smart Search & Filters:** Filter doctors by specialty (Cardiologist, Neurologist, Pediatrician, etc.), clinic, experience, price, rating, and district/city.
- **Doctor Profiles:** Detailed biographies, certifications, clinical experience, clinic affiliations, working hours, and real patient feedback.
- **Digital QR Visit Card:** Each doctor has an automatic, high-resolution QR card to easily scan, share, and save contacts on mobile devices.
- **Appointment Booking:** Online scheduling with calendar slot availability.
- **Patient Reviews & Ratings:** Transparent, 5-star rating system with verified feedback moderation.
- **Direct Messaging / Inquiries:** Secure communication channel with specialists.
- **Multi-language Support:** Full localization in Turkmen (`tk`), Russian (`ru`), and English (`en`).

---

### 🩺 2. Doctor Portal (`/doctor/portal`)
- **Doctor Dashboard:** Overview of total appointments, profile views, patient reviews, and rating analytics.
- **Profile Customization:**
  - Biography and title across multiple languages.
  - Medical degree and academic status (Candidate of Medical Sciences, Professor, etc.).
  - Associated clinics and departments.
  - List of treated illnesses and medical services.
- **Schedule & Availability:** Configure work shifts, consultation duration, and days off across multiple clinics.
- **Patient Appointments:** Accept, reschedule, or cancel patient bookings with instant status updates.

---

### 🏥 3. Clinics Directory (`/clinics`)
- **Clinic Profiles:** Public profiles displaying medical departments, affiliated doctors, photo galleries, working hours, and physical addresses.
- **Multi-Branch Support:** Manage multiple clinics or private medical centers.
- **Staff Directory:** Direct access to all verified specialists practicing at the facility.

---

### 🛡️ 4. Administration & Moderation Panel (`/admin`)
- **Doctor Verification:** Review diplomas, medical licenses, and credentials before awarding the verified badge (`is_verified`).
- **Review Moderation:** Anti-spam review moderation system to prevent fraudulent or competitor ratings.
- **Clinic & Specialty Management:** Dynamic taxonomy management for medical specialties, clinics, and departments.

---

### 🔐 5. Authentication & Security
- **Passwordless OTP Login:** Phone number authentication via SMS OTP (with a developer master code for staging/testing).
- **Role-Based Access Control (RBAC):** Distinct roles for `patient`, `doctor`, and `admin`.
- **Session & CSRF Protection:** Secure session cookies and strict route middleware protection.

---

## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | [Laravel](https://laravel.com/) (PHP 8.4+) |
| **Frontend Engine** | Blade Templates, Alpine.js / Vanilla JS |
| **CSS Framework** | Bootstrap 5.3 + Bootstrap Icons (`bi bi-*`) |
| **Database** | PostgreSQL (or MySQL 8.0+) |
| **File Storage** | Laravel Storage (Local / Public symlink / S3) |
| **Caching / Session** | Redis / Database / File Cache |
| **QR Code Engine** | Dynamic SVG/PNG QR Code Generator API |

---

## 🗄️ Database Schema Overview

erDiagram
    USERS ||--o| DOCTOR_PROFILES : has
    DOCTOR_PROFILES ||--o{ APPOINTMENTS : receives
    USERS ||--o{ APPOINTMENTS : books
    CLINICS ||--o{ DOCTOR_PROFILES : employs
    SPECIALTIES ||--o{ DOCTOR_PROFILES : categorizes
    DOCTOR_PROFILES ||--o{ REVIEWS : receives
    USERS ||--o{ REVIEWS : writes
    USERS ||--o{ OTP_CODES : verifies

    USERS {
        bigint id PK
        string name
        string phone UK
        timestamp phone_verified_at
        enum role "patient, doctor, admin"
        string avatar
        tinyint status
    }

    DOCTOR_PROFILES {
        bigint id PK
        bigint user_id FK
        bigint clinic_id FK
        bigint specialty_id FK
        string title_tk
        string title_ru
        string title_en
        string degree_tk
        int experience_years
        decimal rating
        int reviews_count
        boolean is_verified
        text bio_tk
    }

    CLINICS {
        bigint id PK
        string name_tk
        string name_ru
        string address
        string phone
        string logo
    }

    APPOINTMENTS {
        bigint id PK
        bigint doctor_profile_id FK
        bigint user_id FK
        date appointment_date
        time appointment_time
        enum status "pending, confirmed, completed, cancelled"
    }

    REVIEWS {
        bigint id PK
        bigint doctor_profile_id FK
        bigint user_id FK
        int rating
        text comment
        boolean is_approved
    }


🚀 Quick Start & Installation

Prerequisites

PHP: ^8.2 or ^8.4 (with pdo_pgsql, mbstring, gd, curl, xml extensions)
Composer: ^2.5
PostgreSQL / MySQL
Nginx (or Apache)


1. Clone the Repository

git clone https://github.com/your-username/prodoctors.git
cd prodoctors

2. Install PHP Dependencies

composer install --optimize-autoloader --no-dev

3. Environment Configuration

cp .env.example .env
php artisan key:generate
Open .env and set your database and application details:

Env
APP_NAME=ProDoctors
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=prodoctors_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Cache Driver (Use file for quick setup or redis for production)
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
4. Database Migration & Seeders
code
Bash
# Run migrations and seed initial specialties & clinics
php artisan migrate --seed
5. Create Public Storage Link
code
Bash
php artisan storage:link
6. Set Proper File Permissions
code
Bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
7. Run Local Development Server
code
Bash
php artisan serve --port=8000
Open http://127.0.0.1:8000 in your browser.



🔑 Authentication & Testing

The platform uses an SMS OTP system. Until an active SMS Gateway API is connected, the application uses a Master Bypass Code for development and staging:

Field	Development Value
Phone Format	+9936XXXXXXX or 6XXXXXXX
Master OTP Code	240774
Test Admin	Pre-seeded with role admin
Test Doctor	Pre-seeded with role doctor

📂 Project Structure

prodoctors/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Front/              # Public controllers (Home, Doctor, Clinic, Review)
│   │   │   ├── DoctorPortal/       # Doctor dashboard, appointments, schedules
│   │   │   └── Admin/              # Admin moderation & verifications
│   │   └── Middleware/             # Role, Auth, and Localization middleware
│   └── Models/                     # User, DoctorProfile, Clinic, Appointment, etc.
├── database/
│   ├── migrations/                 # Database schema definitions
│   └── seeders/                    # Sample doctors, clinics, and specialties
├── resources/
│   └── views/
│       ├── front/                  # Public pages (doctor profiles, clinic catalog)
│       ├── doctor_portal/          # Doctor self-service dashboard
│       └── layouts/                # Base layout & UI components
├── routes/
│   ├── web.php                     # Web and portal routes
│   └── auth.php                    # OTP auth routes
└── storage/                        # Uploaded avatars, licenses, and documents


🗺️ Roadmap & Upcoming Features

Telemedicine Consultations: Integrated WebRTC video appointments.

E-Prescriptions & Document Sharing: Secure medical file exchange between doctor and patient.

SMS Gateway Integration: Automatic OTP codes and appointment SMS reminders.

Interactive Clinic Maps: Mapbox/OpenStreetMap integration for clinic branch locators.