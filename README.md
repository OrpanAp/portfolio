# Personal Portfolio Website

A dynamic personal portfolio website built with **PHP 8.2**, **MySQL/MariaDB**, vanilla **HTML/CSS/JavaScript**, and a lightweight custom MVC-style application architecture.

The project includes a public portfolio website and a protected administration panel for managing profile information, projects, categories, technologies, site settings, and CV files.

---

## ✨ Features

### Public Website

* Welcome/landing page
* Dynamic home page
* Dynamic profile/about section
* Portfolio/project listing
* Individual project pages using slugs
* Project categories
* Project technologies
* Featured projects section
* Automatic featured-project carousel
* Previous/next carousel controls
* Featured-project navigation dots
* Responsive layout
* Dark/light theme switching
* CV preview
* CV download
* Profile image support
* Dynamic site title and description
* Project thumbnails
* Project live-preview support through iframe handling

### Featured Projects

The home page displays featured projects dynamically from the database.

The featured-project system:

1. Retrieves published portfolio projects.
2. Filters projects marked as featured.
3. Sorts featured projects by creation date.
4. Displays the six newest featured projects.
5. Presents them in a responsive carousel.
6. Automatically scrolls through the projects.
7. Supports manual previous/next navigation.
8. Supports navigation dots.
9. Seamlessly loops back to the beginning.

This means newly created projects can appear on the home page without manually editing the home-page template.

### Administration Panel

The application includes an authenticated admin area for managing:

* Dashboard
* Portfolio projects
* Categories
* Technologies
* Profile information
* Profile image
* Website settings
* CV uploads
* Administrator authentication

Protected administrative routes use authentication middleware.

---

## 🛠️ Technology Stack

### Backend

* PHP 8.2+
* PDO
* MySQL / MariaDB
* Composer
* PSR-4 autoloading

### Frontend

* HTML5
* CSS3
* Vanilla JavaScript
* Responsive CSS
* Local storage for theme preference

### Architecture

The application follows a lightweight MVC-style structure containing:

* Controllers
* Repositories
* Services
* Core classes
* Middleware
* Views
* Database migrations
* Configuration files

The application uses a custom router and dependency registration instead of relying on a large PHP framework.

---

## 📁 Project Structure

```text
portfolio/
│
├── app/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── ...
│   │
│   ├── Core/
│   ├── Middleware/
│   ├── Repositories/
│   └── Services/
│
├── bin/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── upload.php
│
├── database/
│   └── migrations/
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   │
│   ├── projects/
│   ├── uploads/
│   │   ├── cv/
│   │   ├── portfolio/
│   │   ├── profile/
│   │   └── thumbnails/
│   │
│   └── index.php
│
├── resources/
│   └── views/
│       ├── admin/
│       ├── home/
│       ├── portfolio/
│       ├── profile/
│       └── welcome/
│
├── .env.example
├── .gitignore
├── .htaccess
├── cli.php
├── composer.json
└── create-portfolio.bat
```

---

## 🏗️ Application Architecture

The application is organized into several layers.

### Controllers

Controllers handle HTTP requests and coordinate application logic.

Examples include:

```text
HomeController
ProfileController
PortfolioController
CvController
```

Administrative controllers are grouped under:

```text
App\Controllers\Admin
```

---

### Repositories

Repositories provide database access and isolate SQL/database operations from controllers.

Examples include:

```text
PortfolioRepository
ProfileRepository
CategoryRepository
TechnologyRepository
SettingRepository
UserRepository
```

This keeps database logic separate from presentation and request handling.

---

### Services

Services contain reusable business logic.

Examples include:

```text
AuthService
CvService
UploadService
IframeService
ProfileService
SettingsService
```

---

### Core

The application contains its own lightweight core components, including:

* Router
* Request handling
* Response handling
* Session management
* Authentication
* CSRF protection
* Database connection
* View rendering
* Migration handling

---

## 🗄️ Database

The project uses MySQL/MariaDB through PDO.

The database contains tables for areas such as:

```text
users
profile
settings
portfolios
categories
technologies
portfolio_technology
migrations
```

Database schema changes are handled through migration files.

---

## ⚙️ Requirements

Before installing the project, make sure the following are available:

* PHP 8.2 or newer
* Apache
* MySQL or MariaDB
* Composer
* Git
* PHP PDO extension
* PHP PDO MySQL extension

For a Windows development environment, XAMPP can be used to provide Apache, PHP, and MySQL/MariaDB.

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd portfolio
```

### 2. Install Composer dependencies

```bash
composer install
```

### 3. Create the environment file

Copy:

```text
.env.example
```

to:

```text
.env
```

Configure the application according to your local environment.

Example:

```env
APP_NAME="portfolio"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/dashboard/portfolio/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=portfolio
DB_USER=root
DB_PASS=

SESSION_NAME=portfolio_session

UPLOAD_MAX_SIZE=52428800
```

> Never commit the real `.env` file. It is intentionally excluded from Git.

### 4. Run database migrations

```bash
php cli.php migrate
```

The CLI automatically prepares the database and runs pending migrations.

### 5. Create the administrator account

```bash
php cli.php admin:create
```

The command asks for:

* Username
* Email
* Password

Passwords are hashed before being stored.

### 6. Start Apache and MySQL

If using XAMPP:

* Start Apache.
* Start MySQL/MariaDB.

Then open the application through your configured local URL.

For the current development configuration:

```text
http://localhost/dashboard/portfolio/public
```

---

## 🖥️ CLI Commands

The project includes a custom command-line utility:

```bash
php cli.php <command>
```

Available commands include:

```text
help
admin:create
admin:list
admin:password
admin:reset-password

db:test

migrate
migrate:create
migrate:status
migrate:rollback
```

### Check the database connection

```bash
php cli.php db:test
```

### Run migrations

```bash
php cli.php migrate
```

### Check migration status

```bash
php cli.php migrate:status
```

### Create a migration

```bash
php cli.php migrate:create migration_name
```

### Roll back the latest migration batch

```bash
php cli.php migrate:rollback
```

---

## 🔐 Authentication & Security

The application includes several security mechanisms:

* Session-based authentication
* Authentication middleware
* CSRF protection
* Password hashing
* Protected admin routes
* Server-side validation
* Escaped HTML output
* Environment variables for local configuration
* Upload restrictions
* Separation of public and administrative functionality

Administrative routes are protected by authentication middleware.

---

## 🖼️ File Uploads

Uploaded content is separated into dedicated directories.

Examples include:

```text
public/uploads/cv/
public/uploads/portfolio/
public/uploads/profile/
public/uploads/thumbnails/
public/projects/
```

Uploaded/generated files are excluded from Git through `.gitignore`.

The repository keeps the upload directories available while ignoring their generated contents.

---

## 🎨 Frontend

The frontend uses plain HTML, CSS, and JavaScript rather than a frontend framework.

Main frontend assets are located under:

```text
public/assets/
```

The project uses:

```text
public/assets/css/
public/assets/js/
```

The JavaScript currently handles functionality such as:

* Theme switching
* Featured-project carousel
* Automatic carousel movement
* Carousel navigation
* Carousel looping
* Navigation dots
* Responsive carousel behavior

---

## 📱 Responsive Design

The website is designed to adapt to different screen sizes.

The responsive styling is separated into:

```text
public/assets/css/responsive.css
```

while the main application styles are maintained in:

```text
public/assets/css/main.css
```

---

## 🌗 Dark / Light Theme

The website supports dark and light themes.

The selected theme is stored in browser local storage so that the user's preference can persist between visits.

The theme can be switched without requiring a backend request.

---

## 🧩 Portfolio Management

Portfolio projects are database-driven.

Each project can contain information such as:

* Title
* Slug
* Description
* Category
* Technologies
* Thumbnail
* Published state
* Featured state
* Project/live-preview information

The public portfolio uses project slugs for individual project pages.

Example route:

```text
/portfolio/{slug}
```

---

## 🏠 Home Page

The home page contains:

* Profile-driven introduction
* Dynamic headline
* Dynamic biography/intro
* Portfolio navigation
* Profile navigation
* Featured projects

The featured-project section is database-driven rather than hard-coded.

The Home controller retrieves profile data, site settings, and published featured projects before rendering the home view.

---

## 👤 Profile Management

The profile system allows the administrator to manage information displayed throughout the portfolio.

Profile information can include:

* Headline
* Biography
* Profile image
* Developer information

The public profile page consumes the stored profile data.

---

## ⚙️ Website Settings

Website-level settings can be managed through the administration panel.

Settings include information such as:

* Site title
* Site description
* Social links and other configurable website information

This allows content to be changed without modifying the source code.

---

## 📄 CV Management

The application supports administrator-controlled CV management.

The admin can:

* Upload a CV
* Replace the existing CV
* Download the stored CV

Public routes include:

```text
/cv
/cv/preview
/cv/download
```

---

## 🔗 Main Routes

### Public

```text
/
 /home
 /profile
 /cv
 /cv/preview
 /cv/download
 /portfolio
 /portfolio/{slug}
```

### Authentication

```text
/admin/login
/admin/logout
```

### Administration

```text
/admin
/admin/cv
/admin/profile
/admin/settings
/admin/categories
/admin/technologies
/admin/portfolio
```

The admin section also contains create/edit/delete routes for portfolio projects, categories, and technologies.

---

## 🧪 Development Workflow

A typical development workflow is:

```bash
composer install
php cli.php migrate
php cli.php db:test
```

Then run the project through Apache.

After making changes:

1. Test the affected page.
2. Check PHP syntax.
3. Test database functionality when applicable.
4. Verify responsive behavior.
5. Review the Git diff.
6. Commit the changes.
7. Push the verified commit.

---

## 🔍 PHP Syntax Checking

Individual PHP files can be checked with:

```bash
php -l path/to/file.php
```

Example:

```bash
php -l app/Controllers/HomeController.php
```

---

## 📦 Composer

The project uses Composer for dependency management and PSR-4 autoloading.

The application namespace is:

```text
App\
```

and maps to:

```text
app/
```

The project requires:

```text
PHP ^8.2
ext-pdo
ext-pdo_mysql
```

---

## 🗂️ Environment & Git

Sensitive and generated files are excluded from version control.

The `.gitignore` excludes:

```text
.env
/vendor/
public/uploads/*
public/projects/*
storage/logs/*
.vscode/
.idea/
```

Upload directories are retained through `.gitkeep` files.

---

## 🚧 Project Status

This project is actively being developed.

Current functionality includes:

* Custom PHP application architecture
* Database-backed portfolio management
* Admin authentication
* Profile management
* Website settings
* CV management
* Categories
* Technologies
* Featured projects
* Responsive frontend
* Dark/light theme
* Automatic featured-project carousel
* Database migrations
* CLI installation and administration tools

More functionality and UI improvements can be added as the project evolves.

---

## 👨‍💻 Author

**Alex Purification**

Software Developer

GitHub: `OrpanAp`

---

## 📄 License

No explicit open-source license has currently been added to this repository.

Unless a license is added, the repository should not be assumed to grant permission to reuse, modify, distribute, or commercially exploit the code.

---

## ⭐ Project

If you find the project useful or interesting, consider giving the repository a star and following its development.
