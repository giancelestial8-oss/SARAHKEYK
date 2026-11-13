# SarahKeyk Inventory System

A PHP/MySQL cake inventory dashboard for XAMPP. The public storefront and account registration are disabled; use an existing administrator account to log in.

## Run with XAMPP

1. Copy this project folder into XAMPP's `htdocs` directory. For example: `C:\xampp\htdocs\SarahKeyk`.
2. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin`, choose **Import**, select this project's `database.sql`, then click **Import**. The script creates the `sarahkeyk_db` database and its tables.
4. Check `config/db.php`. The default XAMPP MySQL settings are host `localhost`, user `root`, and an empty password. Update these values if your MySQL setup differs.
5. Visit `http://localhost/SarahKeyk/` (use the actual folder name if different) and log in with an existing administrator account.
6. The dashboard shows an inventory overview. Choose **Inventory** in the left sidebar to add, edit, or delete cake items. Uploaded images are stored in `assets/uploads/cakes/`.

## Notes

- PHP must have the MySQLi extension enabled (the standard XAMPP PHP build includes it).
- The upload limit in this application is 5 MB per cake image. Ensure PHP's `upload_max_filesize` and `post_max_size` settings allow at least 5 MB; restart Apache after changing `php.ini`.
- If PHP is not recognized in a terminal, this does not prevent running the application through XAMPP. PHP's command-line binary is typically in `C:\xampp\php\php.exe`.
- Bootstrap and Google Fonts are loaded from their public CDNs, so those styling assets need an internet connection.
