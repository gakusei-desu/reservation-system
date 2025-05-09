# Reservation System
This project is a simple reservation system built with PHP and MySQL. It allows users to check room availability, make and confirm reservations, and view customer and reservation records. The app is structured with separation of concerns, using basic models and configuration files to improve maintainability.

## Features

- Check room availability
- Submit and confirm reservations
- View reservation and customer data
- Organized using a modular PHP file structure
- Secure database connection via environment variables (`getenv()`)

## Security

- Database credentials are not stored in the codebase.  
  They are accessed using `getenv()` and set via `.htaccess`, which is excluded from this repository.
- Development/testing files like `session_test.php` are removed to keep the code clean and production-ready.

## Getting Started

1. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/reservation-system.git
   ```
2. Set up your database and import the schema (you may create or request the SQL file if not included).

3. Configure your local environment to define the following variables (e.g., via .htaccess, .env, or Apache config):
   ```bash
   DB_HOST
   DB_NAME
   DB_USER
   DB_PASS
   ```
4. Run the app using a local PHP server like XAMPP, MAMP, LAMP, or built-in PHP server:

```bash
php -S localhost:8000
```

## License
This project is open-source and provided for educational/demo purposes.
Feel free to fork and adapt it for your own needs.
