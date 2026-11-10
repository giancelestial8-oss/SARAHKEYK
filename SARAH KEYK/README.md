# SarahKeyk Cake Management System

A small PHP/MySQL cake storefront and admin catalog for XAMPP.

## Run with XAMPP

1. Copy this project folder into XAMPP's `htdocs` directory. For example: `C:\xampp\htdocs\SarahKeyk`.
2. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin`, choose **Import**, select this project's `database.sql`, then click **Import**. The script creates the `sarahkeyk_db` database and its tables.
4. Check `config/db.php`. The default XAMPP MySQL settings are host `localhost`, user `root`, and an empty password. Update these values if your MySQL setup differs.
5. Visit `http://localhost/SarahKeyk/` (use the actual folder name if different).
6. Choose **Create account**. The first registered account is automatically assigned the administrator role; later accounts are regular customers. Keep the first admin credentials private.
7. Log in to open the admin dashboard, then choose **Cake management** to add, edit, or delete cakes. Uploaded images are stored in `assets/uploads/cakes/` and cake records appear on the public landing page automatically.

## Notes

- PHP must have PDO MySQL enabled (the standard XAMPP PHP build includes it).
- The upload limit in this application is 5 MB per cake image. Ensure PHP's `upload_max_filesize` and `post_max_size` settings allow at least 5 MB; restart Apache after changing `php.ini`.
- If PHP is not recognized in a terminal, this does not prevent running the application through XAMPP. PHP's command-line binary is typically in `C:\xampp\php\php.exe`.
- Bootstrap and Google Fonts are loaded from their public CDNs, so those styling assets need an internet connection.
