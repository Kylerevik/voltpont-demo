-- Az adatbázist előbb létre kell hozni (XAMPP-on: voltpont, tárhelyen a vezérlőpulton), majd a kiválasztott adatbázisba importálni.
CREATE TABLE IF NOT EXISTS quote_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    city VARCHAR(60) NOT NULL,
    service VARCHAR(30) NOT NULL,
    property_type VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    urgent TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
