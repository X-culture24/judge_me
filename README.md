
# Judging System - LAMP Stack Implementation

## Project Overview

A comprehensive judging system built on the LAMP stack (Linux, Apache, MySQL, PHP), providing:
- Admin panel for managing judges and users
- Judge portal for scoring participants
- Public scoreboard to display rankings
- User authentication and role-based access control

## Features

### Admin Panel
- View and manage all users
- Add/remove judges
- Promote normal users to judges
- View scores submitted by judges
- Reset participant scores
- Manage events (future enhancement)

### Judge Portal
- Log in to see list of participants
- Assign scores (1–100) to each participant
- Update or overwrite previously submitted scores

### Public Scoreboard
- Displays all participants with total scores
- Automatically updates to reflect new scores
- Highlights top performers dynamically

---

## Workflow and Assumptions

### Workflow
1. Admin logs in using default credentials and sets up the environment.
2. Admin promotes normal users to judges.
3. Judges log in and score participants.
4. Public scoreboard shows results in real time.
5. Admin oversees system integrity and can reset scores or manage users.

### Assumptions
- Each judge can only submit one score per participant.
- Admin has the highest level of access to control all system entities.
- Participants are pre-registered or can register via the user portal.
- Scoring is limited to the range 1–100.
- Judges cannot view each other’s scores for privacy and bias prevention.

---

## Admin Access

- **Default Admin Credentials:**
  - Username: `admin`
  - Password: `admin123`

- Admin Powers:
  - Full user and judge management
  - Reset participant scores
  - Monitor scoring activity
  - Promote/demote users
  - Maintain data integrity

---

## User Instructions

### Normal Users
- Can log in and view their own scores (if enabled).
- Cannot submit scores.
- Must be promoted by an admin to become a judge.

### Judges
- Can log in to view participants
- Must score every participant
- Cannot score the same participant multiple times unless updating the score

### Public
- Can view the scoreboard without authentication

---

## Prerequisites

- Docker and Docker Compose **(Recommended)**  
  OR manually install:
  - Apache 2.4+
  - MySQL 5.7+ / MariaDB 10.3+
  - PHP 7.4+
  - Composer (for dependency management)

---

## Installation

### Docker Setup

```bash
# Clone the repository
git clone https://github.com/X-culture24/judge_me.git
cd judging-system

#Build and Start the containers
docker-compose up --build
docker-compose up -d

# Initialize the database
docker-compose exec app php database/initialize.php
````

**Access the app:**

* Frontend: [http://localhost:8080](http://localhost:8080)
* Adminer: [http://localhost:8081](http://localhost:8081)

---

### Traditional LAMP Setup

```bash
# Clone the repository
git clone https://github.com/yourusername/judging-system.git /var/www/html/judging-system

# Set up the database
mysql -u root -p < database/schema.sql
mysql -u root -p judging_system < database/initial_data.sql

# Set permissions
chown -R www-data:www-data /var/www/html/judging-system
find /var/www/html/judging-system -type d -exec chmod 755 {} \;
find /var/www/html/judging-system -type f -exec chmod 644 {} \;

# Restart Apache
sudo systemctl restart apache2
```

---

## Accessing the Application

* **Admin Login**: [http://localhost:8080/admin-login.php](http://localhost:8080/admin-login.php)

  * Username: `admin`
  * Password: `admin123`

* **Judge/User Login**: [http://localhost:8080/login.php](http://localhost:8080/login.php)

* **Public Scoreboard**: [http://localhost:8080/public/scoreboard.php](http://localhost:8080/public/scoreboard.php)

---

## Database Schema (Summary)

```sql
CREATE DATABASE IF NOT EXISTS judging_system;
USE judging_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_judge BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT NOT NULL,
    user_id INT NOT NULL,
    score INT NOT NULL CHECK (score BETWEEN 1 AND 100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (judge_id) REFERENCES users(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY (judge_id, user_id)
);

-- Admin user (password: admin123)
INSERT INTO users (username, display_name, password, is_admin)
VALUES ('admin', 'Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE);
```
## Design Choices

* Unified `users` table with role flags (`is_admin`, `is_judge`)
* One score per judge per participant (unique constraint)
* Passwords hashed with bcrypt
* Session-based authentication
* CSRF protection and input validation
* Role-based routing and access

---

## Future Enhancements

1. Email alerts for judges and participants
2. Participant categories/divisions
3. Analytics and chart visualizations
4. PDF report downloads
5. Event-based scoring
6. Action logs for auditing
7. Multi-language support

---

## Troubleshooting

### DB Issues

* Confirm DB credentials in `includes/config.php`
* Check if the MySQL container is running (`docker ps`)

### Permissions

```bash
sudo chown -R www-data:www-data /var/www/html/judging-system
sudo find /var/www/html/judging-system -type d -exec chmod 755 {} \;
sudo find /var/www/html/judging-system -type f -exec chmod 644 {} \;
```

### Debugging PHP

Edit `includes/init.php`:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---


