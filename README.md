# Show Reservation Platform — Symfony

## Overview

Academic project developed as part of the **Bachelor in Digital Engineering — Cybersecurity** program at **ESILV** during the 2025–2026 academic year.

The objective of this project is to design a **web-based show reservation platform** allowing users to browse available shows, create an account, log in, make reservations, and view their existing reservations.

The application was developed using **Symfony and PHP** and currently operates in a local development environment. It represents an academic Beta version that could be further developed into a larger-scale reservation platform.

> **Status:** Academic Project / Beta Version

---

## Features

### User Features

* Create a user account
* User authentication and login
* Browse available shows
* Book a show
* Confirm a reservation
* Simulated payment process
* View previous reservations
* View account information

### Administrator Features

* Access a secured administration interface
* Manage application entities
* Create, read, update, and delete data
* Manage users and reservation data
* Control access through user roles

Access to the administration interface is protected using the `ROLE_ADMIN` role.

---

## Architecture

The project follows the **MVC (Model-View-Controller)** architecture, which separates data management, user interface, and application logic.

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

The **Model** layer is based on a **MySQL relational database** managed through **Doctrine ORM**.

Application entities are synchronized with the database using Symfony's migration system.

### View

The **View** layer is implemented using **Twig**, Symfony's templating engine.

Twig `.twig` files are used to generate the application's web pages.

CSS files are organized in:

```text
/public/css
```

Images are organized in:

```text
/public/images
```

### Controller

Controllers handle communication between the application's data and its user interfaces.

They are responsible for:

* Defining application routes
* Handling HTTP requests
* Communicating with entities
* Passing data to views
* Managing application logic

---

## Technologies

| Technology       | Purpose                        |
| ---------------- | ------------------------------ |
| **PHP**          | Backend programming language   |
| **Symfony**      | PHP web framework              |
| **Twig**         | Template engine                |
| **MySQL**        | Relational database            |
| **Doctrine ORM** | Database and entity management |
| **SQL**          | Database manipulation          |
| **HTML5**        | Web page structure             |
| **CSS3**         | Web page styling               |
| **JavaScript**   | Client-side interactivity      |
| **Composer**     | PHP dependency management      |
| **Git**          | Version control                |

These technologies were used to develop the application and connect the web interface to the database.

---

## Development Environment

The project was developed using several tools:

* Visual Studio Code
* Visual Studio Insiders
* Phoenix Code
* MySQL Workbench
* Symfony CLI
* Git
* PowerShell / Command Prompt

---

## Installation

### Prerequisites

Before installing the project, make sure the following tools are available:

* PHP
* Composer
* Symfony CLI
* Git
* MySQL
* MySQL Workbench
* A web browser

The project was developed using **PHP 8.4.x**.

---

### 1. Clone the repository

```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
cd YOUR-REPOSITORY
```

---

### 2. Install dependencies

```bash
composer install
```

---

### 3. Configure the database

Create a MySQL database corresponding to the project configuration.

Then configure the `DATABASE_URL` variable in the `.env` file:

```env
DATABASE_URL="mysql://root:root@127.0.0.1:3306/cinema?serverVersion=16&charset=utf8"
```

> **Important:** Replace the username, password, port, and database name according to your local MySQL configuration.

The original project documentation uses `cinema` as the database name and port `3306` for MySQL.

---

### 4. Create the database

```bash
php bin/console doctrine:database:create
```

---

### 5. Run database migrations

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

Migrations synchronize Symfony entities with the database structure.

---

### 6. Start the Symfony development server

```bash
symfony server:start
```

The application will then be available at:

```text
http://localhost:8000
```

---

## Project Structure

```text
project/
│
├── assets/
│
├── config/
│   └── routes.yaml
│
├── migrations/
│
├── public/
│   ├── css/
│   ├── images/
│   └── ...
│
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   └── ...
│
├── templates/
│   ├── base.html.twig
│   ├── login/
│   ├── registration/
│   ├── reservation/
│   └── ...
│
├── .env
├── composer.json
└── README.md
```

---

## Main Pages

### Home Page

The home page displays the available shows and allows users to browse the different reservation options.

### Login Page

The login page allows existing users to authenticate themselves.

The authentication system relies on Symfony's `form_login` mechanism.

### Registration Page

The registration page allows new users to create an account.

Symfony provides the required basic structure through:

```bash
php bin/console make:registration-form
```

### Reservation Page

The reservation workflow is divided into several steps:

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

The reservation functionality uses a `ReservationController` and several Twig templates to manage the different stages of the process.

---

## Authentication & Security

The application distinguishes between standard users and administrators.

Example of access control configuration:

```yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
```

This configuration restricts access to administrative routes to users with the `ROLE_ADMIN` role.

---

## Administration Interface

The administration system relies on **CRUD operations** to manage the application's entities.

The four main operations are:

```text
Create
Read
Update
Delete
```

The project documentation uses EasyAdmin to generate CRUD interfaces:

```bash
php bin/console make:admin:crud EntityName
```

---

## User Workflow

The main user journey is structured as follows:

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

Final tests were performed to verify the account creation, authentication, reservation process, and additional account-related functionalities.

---

## Demonstration Data

For academic and development purposes, the application uses demonstration data.

No real personal data is required to run the project.

---

## Future Improvements

This project currently represents an **academic Beta version**. Several improvements could be implemented in future versions:

* Deploy the application to a production server
* Integrate a real payment provider
* Improve the user interface
* Add a notification system
* Implement advanced ticket management
* Generate digital tickets
* Integrate QR codes for ticket validation
* Strengthen application security
* Improve role and permission management
* Add automated testing
* Develop an API
* Optimize application performance
* Prepare the platform for larger-scale usage

---

## Current Limitations

Since the project was developed in an academic environment, several features would require further development before production deployment.

Current limitations include:

* The payment system is not connected to a real payment provider.
* The application currently runs in a local development environment.
* The available data is primarily intended for testing and demonstration.
* Additional security and data-management measures would be required for production use.

---

## Learning Objectives

This project provided an opportunity to apply several technical concepts:

* PHP web development
* Symfony framework
* MVC architecture
* MySQL database management
* Doctrine ORM
* Entity creation and management
* Database migrations
* Routing and Controllers
* Twig templating
* User authentication
* Role-based access control
* CRUD administration
* Git version control
* Collaborative software development
* Web project organization

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

The project was developed as a collaborative academic assignment. Each team member contributed to different components of the application, including authentication, the home page, reservation workflow, database management, and administration features.

---

## License

This project was developed for **academic purposes** at ESILV.

The repository is primarily intended to document and showcase the technical work carried out during the project.

---

## Author

### Kyllian Tiague Siewe

**Cybersecurity Student — ESILV**


---

## Acknowledgements

Special thanks to **ESILV** and the teaching team for providing the academic framework and technical foundations required to develop this project.
