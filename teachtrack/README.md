# TeachTrack PWA

A Progressive Web App for digitizing teacher hours logging and payroll management in secondary schools in Cameroon.

## Setup Instructions

1. Create a Supabase project at https://supabase.com
2. Run the SQL in `db-schema.sql` in the Supabase SQL editor to create the tables.
3. Update `config/supabase.php` with your Supabase URL and anon key.
4. Deploy the `teachtrack/` folder to a PHP server (e.g., Apache with PHP 8).
5. Ensure the web root points to `teachtrack/public/`.
6. Create placeholder icons in `public/assets/icons/` (icon-192.png and icon-512.png).
7. Access the app at the server's URL.

## Features

- Teachers log teaching sessions with cap enforcement.
- Principals manage teachers, classes, and view payroll.
- Admins oversee multiple schools (future-ready).
- PWA installable on mobile devices.

## Tech Stack

- Frontend: HTML, Tailwind CSS, Vanilla JS
- Backend: PHP 8
- Database: Supabase (PostgreSQL)
- PWA: Service Worker, Manifest