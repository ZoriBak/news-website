# Simple PHP News Website

## How to use
1. Import `database.sql` into your MySQL server.
2. Set your DB credentials in `config/db.php`.
3. Run with XAMPP, WAMP, or PHP's built-in server:
   ```
   php -S localhost:8000
   ```

## Pages
- `/index.php` - Homepage with 5-article pagination
- `/article.php?id=1` - Individual article view
- `/login.php`, `/register.php`, `/profile.php`, `/admin/` (to be added)

## To do
- Add login/register logic
- Admin dashboard for managing users/articles