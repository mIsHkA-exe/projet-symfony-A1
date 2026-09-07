# Show Reservation Platform — Symfony

![Symfony](https://img.shields.io/badge/Symfony-Framework-black?style=for-the-badge\&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge\&logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge\&logo=mysql\&logoColor=white)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM-FC6A31?style=for-the-badge)
![Twig](https://img.shields.io/badge/Twig-Template%20Engine-BACC00?style=for-the-badge\&logo=twig\&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge\&logo=javascript\&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge\&logo=html5\&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge\&logo=css3\&logoColor=white)
![Git](https://img.shields.io/badge/Git-Version%20Control-F05032?style=for-the-badge\&logo=git\&logoColor=white)

## Overview

Academic project developed as part of the **Bachelor in Digital Engineering — Cybersecurity** program at **ESILV** during the 2025–2026 academic year.

The objective of this project is to develop a **web-based show reservation platform** allowing users to browse available shows, create an account, authenticate, make reservations and view their previous reservations.

> **Status:** Academic Project / Beta Version

---

## Table of Contents

* [Overview](#overview)
* [Features](#features)

  * [User Features](#user-features)
  * [Administrator Features](#administrator-features)
* [Architecture](#architecture)

  * [Model](#model)
  * [View](#view)
  * [Controller](#controller)
* [Technologies](#technologies)
* [Development Environment](#development-environment)
* [Installation](#installation)

  * [Prerequisites](#prerequisites)
  * [Clone the Repository](#1-clone-the-repository)
  * [Install Dependencies](#2-install-dependencies)
  * [Configure the Database](#3-configure-the-database)
  * [Create the Database](#4-create-the-database)
  * [Run Database Migrations](#5-run-database-migrations)
  * [Start the Symfony Development Server](#6-start-the-symfony-development-server)
* [Project Structure](#project-structure)
* [Main Pages](#main-pages)

  * [Home Page](#home-page)
  * [Login Page](#login-page)
  * [Registration Page](#registration-page)
  * [Reservation Page](#reservation-page)
* [Authentication & Security](#authentication--security)
* [Administration Interface](#administration-interface)
* [User Workflow](#user-workflow)
* [Demonstration Data](#demonstration-data)
* [Future Improvements](#future-improvements)
* [Current Limitations](#current-limitations)
* [Learning Objectives](#learning-objectives)
* [Team](#team)
* [Project Context](#project-context)
* [License](#license)
* [Author](#author)
* [Acknowledgements](#acknowledgements)

---

## Features

### User Features

* Create a user account
* Authenticate and log in
* Browse available shows
* Book a show
* Confirm a reservation
* Simulate the payment process
* View previous reservations
* View account information

### Administrator Features

* Access a secured administration interface
* Manage application entities
* Create, read, update and delete data
* Manage users and reservations
* Control access using user roles

The administration interface is protected using the `ROLE_ADMIN` role.

---

## Architecture

The application follows the **MVC (Model-View-Controller)** architecture.

```text
                    ┌──────────────────────┐
                    │        User          │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │      Controller      │
                    │       Symfony        │
                    └───────┬───────┬──────┘
                            │       │
                 ┌──────────┘       └──────────┐
                 ▼                             ▼
        ┌────────────────┐             ┌────────────────┐
        │      Model     │             │      View      │
        │ MySQL / Doctrine│             │ Twig / HTML    │
        └────────────────┘             └────────────────┘
```

### Model

The Model layer uses a **MySQL relational database** managed through **Doctrine ORM**.

### View

The View layer uses **Twig**, Symfony's templating engine.

### Controller

Controllers handle routes, HTTP requests, communication with entities and data transmission to the views.

---

## Technologies

| Technology   | Purpose                        |
| ------------ | ------------------------------ |
| PHP 8.4      | Backend programming            |
| Symfony      | Web framework                  |
| Twig         | Template engine                |
| MySQL        | Relational database            |
| Doctrine ORM | Entity and database management |
| SQL          | Database manipulation          |
| HTML5        | Web structure                  |
| CSS3         | Styling                        |
| JavaScript   | Client-side interactions       |
| Composer     | Dependency management          |
| Git          | Version control                |

---

## Installation

### Prerequisites

* PHP 8.4.x
* Composer
* Symfony CLI
* Git
* MySQL
* MySQL Workbench
* Web browser

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
cd YOUR-REPOSITORY
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure the Database

Configure the `DATABASE_URL` variable in `.env`:

```env
DATABASE_URL="mysql://root:root@127.0.0.1:3306/cinema?serverVersion=16&charset=utf8"
```

Adjust the username, password, port and database name according to your local configuration.

### 4. Create the Database

```bash
php bin/console doctrine:database:create
```

### 5. Run Database Migrations

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 6. Start the Symfony Development Server

```bash
symfony server:start
```

The application will be available at:

```text
http://localhost:8000
```

---

## Project Structure

```text
project/
│
├── assets/
├── config/
│   └── routes.yaml
├── migrations/
├── public/
│   ├── css/
│   ├── images/
│   └── ...
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   └── ...
├── templates/
│   ├── base.html.twig
│   ├── login/
│   ├── registration/
│   ├── reservation/
│   └── ...
├── .env
├── composer.json
└── README.md
```

---

## Main Pages

### Home Page

Displays available shows and reservation options.

### Login Page

Allows existing users to authenticate using Symfony's `form_login` mechanism.

### Registration Page

Allows new users to create an account.

```bash
php bin/console make:registration-form
```

### Reservation Page

The reservation process is divided into several stages:

```text
Show Selection
      │
      ▼
Reservation
      │
      ▼
Confirmation
      │
      ▼
Payment
```

---

## Authentication & Security

The application distinguishes between standard users and administrators.

Administrative routes are protected using Symfony's role-based access control:

```yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
```

This prevents unauthorized users from accessing the administration interface.

---

## Administration Interface

The administration interface uses **CRUD operations**:

```text
Create
  ↓
Read
  ↓
Update
  ↓
Delete
```

The project documentation also uses EasyAdmin to generate CRUD interfaces.

```bash
php bin/console make:admin:crud EntityName
```

---

## User Workflow

```text
Create an account
       ↓
     Login
       ↓
Browse available shows
       ↓
Make a reservation
       ↓
Confirm reservation
       ↓
     Payment
       ↓
View reservations
```

---

## Demonstration Data

The application uses demonstration data for academic and development purposes.

No real personal data is required to run the project.

---

## Future Improvements

* Production deployment
* Real payment provider integration
* Improved user interface
* Notification system
* Advanced ticket management
* Digital ticket generation
* QR-code ticket validation
* Stronger security mechanisms
* Improved role and permission management
* Automated testing
* API development
* Performance optimization
* Scalability improvements

---

## Current Limitations

The project is currently an **academic Beta version**.

Main limitations include:

* Payment is simulated
* The application currently runs locally
* Data is primarily intended for testing
* Additional security measures would be required before production deployment

---

## Learning Objectives

This project provided practical experience with:

* PHP web development
* Symfony
* MVC architecture
* MySQL
* Doctrine ORM
* Database migrations
* Routing and Controllers
* Twig
* Authentication
* Role-based access control
* CRUD administration
* Git
* Collaborative development
* Web application architecture

---

## Team

This project was developed by:

* **Kyllian Tiague Siewe**
* **Valentin Santos**
* **Housam Bendriouich**
* **Alexandre Yagapah**

Academic project completed as part of the **Bachelor in Digital Engineering — Cybersecurity** program at **ESILV**.

---

## Project Context

The project was developed as a collaborative academic assignment.

Team members contributed to different components of the application, including:

* Authentication
* Home page
* Reservation workflow
* Database management
* Administration features

---

## License

This project was developed for **academic purposes** at ESILV.

The repository is primarily intended to document and showcase the technical work completed during the project.

---

## Author

### Kyllian Tiague Siewe

**Cybersecurity Student — ESILV**

---

## Acknowledgements

Special thanks to **ESILV** and the teaching team for providing the academic framework and technical foundations required to develop this project.
