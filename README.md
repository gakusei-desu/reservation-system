# Reservation System
This Reservation System is a lightweight, modular web application built with PHP, MySQL, Bootstrap, jQuery, AJAX, and JSON. While it functions independently as a fully operational reservation tool, I designed it to serve as the groundwork for a scalable, full-featured restaurant ERP system. The project emphasizes clean code architecture, modular design, and real-time client-server interaction to ensure a responsive and intuitive user experience. With dynamic availability checking, a streamlined reservation workflow, and an MVC-style structure, this system reflects practical full-stack development and thoughtful planning for long-term extensibility.

## Features

- Submit and confirm reservations
- View reservation and customer data
- Organized using a modular PHP file structure
- Secure database connection via environment variables (`getenv()`)

## Security

- Database credentials are not stored in the codebase.  
  They are accessed using `getenv()` and set via `.htaccess`, which is excluded from this repository.
- Development/testing files like `session_test.php` are removed to keep the code clean and production-ready.


## License
This project is open-source and provided for educational/demo purposes.
Feel free to fork and adapt it for your own needs.
