# DWCSJ Peer e-Tutoring - Full Build (Requires Composer install)

This archive provides a fuller runnable project scaffold for the DWCSJ Peer e-Tutoring system.
IMPORTANT: To make this project fully runnable you **must** run `composer install` locally to download Laravel and dependencies.

Steps to prepare and run locally (on your machine):

1. Ensure PHP 8.1+, Composer and Node.js/npm are installed on your machine (XAMPP + Composer recommended).
2. Extract this archive into your chosen folder, e.g. `C:/xampp/htdocs/peer_tutoring`.
3. In the project folder, run:
   - `composer install` (this will download Laravel framework and packages listed in composer.json)
   - `cp .env.example .env` (or copy the provided .env.example)
   - Edit `.env` to configure database connection (XAMPP default: DB_HOST=127.0.0.1, DB_DATABASE=peer_tutoring, DB_USERNAME=root, DB_PASSWORD=)
   - `php artisan key:generate`
   - `php artisan migrate --seed`
   - `npm install`
   - `npm run dev`
   - `php artisan serve`
4. Open `http://localhost:8000` or your local Apache URL.

Notes:
- This package includes the app, database, resources, and public assets already prepared.
- The actual Laravel framework files will be installed when you run `composer install`.
- Admin seeded account: Student ID `ADMIN001`, password `password123`

If you'd like, I can instead prepare a fully pre-packaged runnable VM or container image, but you will need to download a larger file. For now, this approach keeps the archive smaller while letting Composer fetch official packages on your machine.
