@echo off
setlocal

set "PROJECT=portfolio"

echo Creating portfolio project structure...
echo.

:: =========================================================
:: APP
:: =========================================================

mkdir "%PROJECT%\app\Controllers\Admin" 2>nul
mkdir "%PROJECT%\app\Core" 2>nul
mkdir "%PROJECT%\app\Middleware" 2>nul
mkdir "%PROJECT%\app\Models" 2>nul
mkdir "%PROJECT%\app\Repositories" 2>nul
mkdir "%PROJECT%\app\Services" 2>nul
mkdir "%PROJECT%\app\Helpers" 2>nul

:: Admin Controllers
type nul > "%PROJECT%\app\Controllers\Admin\AuthController.php"
type nul > "%PROJECT%\app\Controllers\Admin\DashboardController.php"
type nul > "%PROJECT%\app\Controllers\Admin\PortfolioController.php"
type nul > "%PROJECT%\app\Controllers\Admin\CategoryController.php"
type nul > "%PROJECT%\app\Controllers\Admin\TechnologyController.php"
type nul > "%PROJECT%\app\Controllers\Admin\ProfileController.php"
type nul > "%PROJECT%\app\Controllers\Admin\SettingsController.php"
type nul > "%PROJECT%\app\Controllers\Admin\CvController.php"

:: Public Controllers
type nul > "%PROJECT%\app\Controllers\HomeController.php"
type nul > "%PROJECT%\app\Controllers\ProfileController.php"
type nul > "%PROJECT%\app\Controllers\PortfolioController.php"
type nul > "%PROJECT%\app\Controllers\WelcomeController.php"
type nul > "%PROJECT%\app\Controllers\CvController.php"

:: Core
type nul > "%PROJECT%\app\Core\Database.php"
type nul > "%PROJECT%\app\Core\Router.php"
type nul > "%PROJECT%\app\Core\Request.php"
type nul > "%PROJECT%\app\Core\Response.php"
type nul > "%PROJECT%\app\Core\Session.php"
type nul > "%PROJECT%\app\Core\Auth.php"
type nul > "%PROJECT%\app\Core\Csrf.php"
type nul > "%PROJECT%\app\Core\View.php"

:: Middleware
type nul > "%PROJECT%\app\Middleware\AuthMiddleware.php"
type nul > "%PROJECT%\app\Middleware\GuestMiddleware.php"

:: Models
type nul > "%PROJECT%\app\Models\User.php"
type nul > "%PROJECT%\app\Models\Category.php"
type nul > "%PROJECT%\app\Models\Technology.php"
type nul > "%PROJECT%\app\Models\Portfolio.php"
type nul > "%PROJECT%\app\Models\Profile.php"
type nul > "%PROJECT%\app\Models\Setting.php"

:: Repositories
type nul > "%PROJECT%\app\Repositories\UserRepository.php"
type nul > "%PROJECT%\app\Repositories\CategoryRepository.php"
type nul > "%PROJECT%\app\Repositories\TechnologyRepository.php"
type nul > "%PROJECT%\app\Repositories\PortfolioRepository.php"
type nul > "%PROJECT%\app\Repositories\ProfileRepository.php"
type nul > "%PROJECT%\app\Repositories\SettingRepository.php"

:: Services
type nul > "%PROJECT%\app\Services\AuthService.php"
type nul > "%PROJECT%\app\Services\PortfolioService.php"
type nul > "%PROJECT%\app\Services\ProfileService.php"
type nul > "%PROJECT%\app\Services\SettingsService.php"
type nul > "%PROJECT%\app\Services\UploadService.php"
type nul > "%PROJECT%\app\Services\CvService.php"
type nul > "%PROJECT%\app\Services\IframeService.php"

:: Helpers
type nul > "%PROJECT%\app\Helpers\url.php"
type nul > "%PROJECT%\app\Helpers\security.php"
type nul > "%PROJECT%\app\Helpers\formatting.php"


:: =========================================================
:: CONFIG
:: =========================================================

mkdir "%PROJECT%\config" 2>nul

type nul > "%PROJECT%\config\app.php"
type nul > "%PROJECT%\config\database.php"
type nul > "%PROJECT%\config\upload.php"


:: =========================================================
:: DATABASE
:: =========================================================

mkdir "%PROJECT%\database" 2>nul

type nul > "%PROJECT%\database\schema.sql"
type nul > "%PROJECT%\database\seed.sql"


:: =========================================================
:: PUBLIC
:: =========================================================

mkdir "%PROJECT%\public\assets\css" 2>nul
mkdir "%PROJECT%\public\assets\js" 2>nul
mkdir "%PROJECT%\public\assets\images" 2>nul

mkdir "%PROJECT%\public\uploads\portfolio" 2>nul
mkdir "%PROJECT%\public\uploads\thumbnails" 2>nul
mkdir "%PROJECT%\public\uploads\cv" 2>nul

mkdir "%PROJECT%\public\projects" 2>nul

type nul > "%PROJECT%\public\index.php"

:: CSS
type nul > "%PROJECT%\public\assets\css\main.css"
type nul > "%PROJECT%\public\assets\css\auth.css"
type nul > "%PROJECT%\public\assets\css\admin.css"
type nul > "%PROJECT%\public\assets\css\responsive.css"

:: JavaScript
type nul > "%PROJECT%\public\assets\js\main.js"
type nul > "%PROJECT%\public\assets\js\theme.js"
type nul > "%PROJECT%\public\assets\js\portfolio.js"
type nul > "%PROJECT%\public\assets\js\admin.js"

:: Keep projects directory in Git
type nul > "%PROJECT%\public\projects\.gitkeep"


:: =========================================================
:: RESOURCES / VIEWS
:: =========================================================

mkdir "%PROJECT%\resources\views\layouts" 2>nul
mkdir "%PROJECT%\resources\views\components" 2>nul

mkdir "%PROJECT%\resources\views\welcome" 2>nul
mkdir "%PROJECT%\resources\views\home" 2>nul
mkdir "%PROJECT%\resources\views\profile" 2>nul
mkdir "%PROJECT%\resources\views\portfolio" 2>nul
mkdir "%PROJECT%\resources\views\cv" 2>nul

mkdir "%PROJECT%\resources\views\admin\auth" 2>nul
mkdir "%PROJECT%\resources\views\admin\portfolio" 2>nul
mkdir "%PROJECT%\resources\views\admin\categories" 2>nul
mkdir "%PROJECT%\resources\views\admin\technologies" 2>nul
mkdir "%PROJECT%\resources\views\admin\profile" 2>nul
mkdir "%PROJECT%\resources\views\admin\settings" 2>nul
mkdir "%PROJECT%\resources\views\admin\cv" 2>nul

:: Layouts
type nul > "%PROJECT%\resources\views\layouts\app.php"
type nul > "%PROJECT%\resources\views\layouts\admin.php"

:: Components
type nul > "%PROJECT%\resources\views\components\navbar.php"
type nul > "%PROJECT%\resources\views\components\footer.php"
type nul > "%PROJECT%\resources\views\components\flash.php"
type nul > "%PROJECT%\resources\views\components\pagination.php"

:: Public views
type nul > "%PROJECT%\resources\views\welcome\index.php"
type nul > "%PROJECT%\resources\views\home\index.php"
type nul > "%PROJECT%\resources\views\profile\index.php"
type nul > "%PROJECT%\resources\views\portfolio\index.php"
type nul > "%PROJECT%\resources\views\portfolio\show.php"
type nul > "%PROJECT%\resources\views\cv\index.php"

:: Admin views
type nul > "%PROJECT%\resources\views\admin\auth\login.php"
type nul > "%PROJECT%\resources\views\admin\dashboard.php"

type nul > "%PROJECT%\resources\views\admin\portfolio\index.php"
type nul > "%PROJECT%\resources\views\admin\portfolio\create.php"
type nul > "%PROJECT%\resources\views\admin\portfolio\edit.php"

type nul > "%PROJECT%\resources\views\admin\categories\index.php"
type nul > "%PROJECT%\resources\views\admin\technologies\index.php"
type nul > "%PROJECT%\resources\views\admin\profile\edit.php"
type nul > "%PROJECT%\resources\views\admin\settings\edit.php"
type nul > "%PROJECT%\resources\views\admin\cv\edit.php"


:: =========================================================
:: STORAGE
:: =========================================================

mkdir "%PROJECT%\storage\logs" 2>nul


:: =========================================================
:: ROOT FILES
:: =========================================================

type nul > "%PROJECT%\.env"
type nul > "%PROJECT%\.env.example"
type nul > "%PROJECT%\.gitignore"
type nul > "%PROJECT%\.htaccess"
type nul > "%PROJECT%\composer.json"
type nul > "%PROJECT%\README.md"


:: =========================================================
:: DONE
:: =========================================================

echo.
echo ==========================================
echo   Portfolio structure created!
echo ==========================================
echo.
echo Project:
echo %CD%\%PROJECT%
echo.

pause