# Mental Wellness Support System

A web-based student mental wellness support system developed using PHP, MySQL, Bootstrap, and XAMPP.

---

## Technology Stack

- PHP
- MySQL (MariaDB)
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- XAMPP
- Git & GitHub

---

## Installation

1. Clone the repository

```bash
git clone https://github.com/Joshua-055/mental-wellness-support-system.git
```

2. Move the project into:

```
C:\xampp\htdocs\
```

3. Start Apache and MySQL using XAMPP.

4. Import the database:

```
database/mental_wellness.sql
```

For an existing database created before password recovery was added, also run:

```
database/migrations/20260721_create_password_reset_tokens.sql
```

5. Update the database configuration:

```
config/database.php
```

6. Open the project in your browser:

```
http://localhost/mental-wellness-support-system
```

---

## Project Structure

```
mental-wellness-support-system/
│
├── assets/
├── config/
├── database/
├── includes/
├── student/
├── staff/
├── README.md
└── index.php
```

---

## Team Members

| Member | Responsibility |
|---------|----------------|
| Joshua | Authentication, Dashboard, Integration |
| Member 2 | Wellness Check-In |
| Member 3 | Resources & UI |
| Member 4 | Support Request & Appointment |

---

## Git Workflow

- Do **not** commit directly to `main`.
- Develop on your assigned feature branch.
- Commit your changes regularly.
- Push your branch to GitHub.
- Create a Pull Request to merge into `develop`.
- Only the team leader merges `develop` into `main`.

---

## Branches

- `main` → Stable version
- `develop` → Integration branch
- `feature/login`
- `feature/checkin`
- `feature/resources`
- `feature/support`
