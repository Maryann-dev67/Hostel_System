# Preference Based Hostel Booking and Allocation Management System

## Overview
A smart hostel management system that allocates rooms based on student lifestyle preferences using a compatibility algorithm.

## Features
- Student registration and login
- Profile management
- Preference submission (Likert scale)
- Smart compatibility-based room allocation
- Real-time notifications
- Admin dashboard with charts and reports

## Technology Stack
- Frontend: HTML, CSS, JavaScript
- Backend: PHP 8.2
- Database: MySQL/MariaDB
- Charts: Chart.js
- Server: XAMPP / Apache

## Compatibility Algorithm
Matches students based on:
- Gender (exact match)
- Sleep schedule (exact match)
- Study habits (difference ≤ 2)
- Cleanliness (difference ≤ 2)
- Noise tolerance (difference ≤ 2)
- Social level (difference ≤ 2)

## Setup Instructions
1. Clone the repository
2. Copy `config/db.example.php` to `config/db.php`
3. Update database credentials
4. Import `hostel_system.sql`
5. Run on XAMPP/Apache

## Author
Maryann (GitHub: Maryann-dev67)
