USE sarahkeyk_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(120) NOT NULL,
  username VARCHAR(30) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cakes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  cake_name VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
  season VARCHAR(40) NOT NULL DEFAULT 'All season',
  cake_image VARCHAR(255) DEFAULT NULL,
  date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cakes_date_added (date_added)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
