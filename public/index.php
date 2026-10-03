<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Session;
use App\Core\View;
use App\Core\Env;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Controllers\CvController;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\ProfileController;

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\CvController as AdminCvController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Controllers\Admin\ProfileController as AdminProfileController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\TechnologyController;

/*
|--------------------------------------------------------------------------
| Middleware
|--------------------------------------------------------------------------
*/

use App\Middleware\AuthMiddleware;

/*
|--------------------------------------------------------------------------
| Repositories
|--------------------------------------------------------------------------
*/

use App\Repositories\CategoryRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TechnologyRepository;
use App\Repositories\UserRepository;

/*
|--------------------------------------------------------------------------
| Services
|--------------------------------------------------------------------------
*/

use App\Services\AuthService;
use App\Services\CvService;
use App\Services\UploadService;
use App\Services\IframeService;
use App\Services\SettingsService;
use App\Services\ProfileService;

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

Env::load(__DIR__ . '/../.env');

$appUrl = Env::get('APP_URL', '');

$dbConfig = require __DIR__ . '/../config/database.php';

$uploadConfig = require __DIR__ . '/../config/upload.php';

/*
|--------------------------------------------------------------------------
| Core Objects
|--------------------------------------------------------------------------
*/

$request = new Request();

$response = new Response();

$router = new Router();

$session = new Session();

$auth = new Auth($session);

$csrf = new Csrf($session);

/*
|--------------------------------------------------------------------------
| Authentication Middleware
|--------------------------------------------------------------------------
*/

$authMiddleware = new AuthMiddleware(
    $auth,
    $response,
    $appUrl
);

$router->setMiddleware(
    'auth',
    $authMiddleware
);

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

$database = new Database(
    $dbConfig['host'],
    $dbConfig['port'],
    $dbConfig['database'],
    $dbConfig['username'],
    $dbConfig['password']
);

/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
*/

$view = new View(
    __DIR__ . '/../resources/views'
);

$router->setDependency(
    View::class,
    $view
);

/*
|--------------------------------------------------------------------------
| Repositories
|--------------------------------------------------------------------------
*/

$profileRepository = new ProfileRepository(
    $database->connection()
);

$categoryRepository = new CategoryRepository(
    $database->connection()
);

$technologyRepository = new TechnologyRepository(
    $database->connection()
);

$portfolioRepository = new PortfolioRepository(
    $database->connection()
);

$userRepository = new UserRepository(
    $database->connection()
);

$settingRepository = new SettingRepository(
    $database->connection()
);

/*
|--------------------------------------------------------------------------
| Repository Dependencies
|--------------------------------------------------------------------------
*/

$router->setDependency(
    ProfileRepository::class,
    $profileRepository
);

$router->setDependency(
    CategoryRepository::class,
    $categoryRepository
);

$router->setDependency(
    TechnologyRepository::class,
    $technologyRepository
);

$router->setDependency(
    PortfolioRepository::class,
    $portfolioRepository
);

$router->setDependency(
    UserRepository::class,
    $userRepository
);

$router->setDependency(
    SettingRepository::class,
    $settingRepository
);

/*
|--------------------------------------------------------------------------
| Services
|--------------------------------------------------------------------------
*/

$authService = new AuthService(
    $userRepository,
    $auth
);

$profileService = new ProfileService(
    $profileRepository
);

$settingsService = new SettingsService(
    $settingRepository
);

$uploadService = new UploadService(
    $uploadConfig['projects_path'],
    $uploadConfig['max_file_size']
);

$cvService = new CvService(
    $uploadConfig['cv_path'],
    $uploadConfig['cv_max_file_size']
);

/*
|--------------------------------------------------------------------------
| Public Controllers
|--------------------------------------------------------------------------
*/

/*
 * Profile
 */

$profileController = new ProfileController(
    $view,
    $profileRepository,
    $appUrl
);

$router->setDependency(
    ProfileController::class,
    $profileController
);

/*
 * Home
 */

$homeController = new HomeController(
    $view,
    $profileRepository,
    $settingRepository,
    $portfolioRepository,
    $appUrl
);

$router->setDependency(
    HomeController::class,
    $homeController
);

/*
 * Portfolio
 */

$portfolioController = new PortfolioController(
    $view,
    $portfolioRepository,
    $categoryRepository,
    $technologyRepository,
    $appUrl,
    new IframeService(
        $appUrl
    )
);

$router->setDependency(
    PortfolioController::class,
    $portfolioController
);

/*
 * CV
 */

$cvController = new CvController(
    $view,
    $response,
    $settingRepository,
    $cvService,
    $appUrl
);

$router->setDependency(
    CvController::class,
    $cvController
);

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/

/*
 * Authentication
 */

$adminAuthController = new AuthController(
    $view,
    $request,
    $response,
    $auth,
    $csrf,
    $authService,
    $appUrl
);

$router->setDependency(
    AuthController::class,
    $adminAuthController
);

/*
 * Dashboard
 */

$dashboardController = new DashboardController(
    $view,
    $auth,
    $csrf,
    $appUrl
);

$router->setDependency(
    DashboardController::class,
    $dashboardController
);

/*
 * Categories
 */

$categoryController = new CategoryController(
    $view,
    $request,
    $response,
    $csrf,
    $categoryRepository,
    $appUrl
);

$router->setDependency(
    CategoryController::class,
    $categoryController
);

/*
 * Technologies
 */

$technologyController = new TechnologyController(
    $view,
    $request,
    $response,
    $csrf,
    $technologyRepository,
    $appUrl
);

$router->setDependency(
    TechnologyController::class,
    $technologyController
);

/*
 * Portfolio
 */

$adminPortfolioController = new AdminPortfolioController(
    $view,
    $request,
    $response,
    $csrf,
    $portfolioRepository,
    $categoryRepository,
    $technologyRepository,
    $uploadService,
    $appUrl
);

$router->setDependency(
    AdminPortfolioController::class,
    $adminPortfolioController
);

/*
 * CV
 */

$adminCvController = new AdminCvController(
    $view,
    $request,
    $response,
    $csrf,
    $cvService,
    $settingRepository,
    $appUrl
);

$router->setDependency(
    AdminCvController::class,
    $adminCvController
);

/*
 * Profile
 */

$adminProfileController =
    new AdminProfileController(
        $view,
        $request,
        $response,
        $csrf,
        $profileService,
        $uploadService,
        $uploadConfig['profile_image_path'],
        $uploadConfig['profile_image_max_file_size'],
        $appUrl
    );

$router->setDependency(
    AdminProfileController::class,
    $adminProfileController
);

/*
 * Settings
 */

$settingsController = new SettingsController(
    $view,
    $request,
    $response,
    $csrf,
    $settingsService,
    $appUrl
);

$router->setDependency(
    SettingsController::class,
    $settingsController
);

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

/*
 * Welcome
 */

$router->get(
    '/',
    function () use ($view, $appUrl) {

        return $view->render(
            'welcome.index',
            [
                'title' => 'Welcome - My Portfolio',
                'appUrl' => $appUrl,
            ]
        );
    }
);

/*
 * Home
 */

/*
 * Home
 */

$router->get(
    '/home',
    [
        HomeController::class,
        'index',
    ]
);

/*
 * Profile
 */

$router->get(
    '/profile',
    [
        ProfileController::class,
        'index',
    ]
);

/*
 * CV
 */

$router->get(
    '/cv',
    [
        CvController::class,
        'index',
    ]
);

$router->get(
    '/cv/preview',
    [
        CvController::class,
        'preview',
    ]
);

$router->get(
    '/cv/download',
    [
        CvController::class,
        'download',
    ]
);

/*
 * Portfolio
 */

$router->get(
    '/portfolio',
    [
        PortfolioController::class,
        'index',
    ]
);

$router->get(
    '/portfolio/{slug}',
    [
        PortfolioController::class,
        'show',
    ]
);

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/login',
    [
        AuthController::class,
        'showLogin',
    ]
);

$router->post(
    '/admin/login',
    [
        AuthController::class,
        'login',
    ]
);

$router->post(
    '/admin/logout',
    [
        AuthController::class,
        'logout',
    ]
);

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin',
    [
        DashboardController::class,
        'index',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Admin CV Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/cv',
    [
        AdminCvController::class,
        'index',
    ],
    ['auth']
);

$router->post(
    '/admin/cv',
    [
        AdminCvController::class,
        'upload',
    ],
    ['auth']
);

$router->get(
    '/admin/cv/download',
    [
        AdminCvController::class,
        'download',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Admin Profile Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/profile',
    [
        AdminProfileController::class,
        'index',
    ],
    ['auth']
);

$router->post(
    '/admin/profile',
    [
        AdminProfileController::class,
        'update',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Admin Settings Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/settings',
    [
        SettingsController::class,
        'index',
    ],
    ['auth']
);

$router->post(
    '/admin/settings',
    [
        SettingsController::class,
        'update',
    ],
    ['auth']
);


/*
|--------------------------------------------------------------------------
| Admin Category Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/categories',
    [
        CategoryController::class,
        'index',
    ],
    ['auth']
);

$router->get(
    '/admin/categories/create',
    [
        CategoryController::class,
        'create',
    ],
    ['auth']
);

$router->post(
    '/admin/categories',
    [
        CategoryController::class,
        'store',
    ],
    ['auth']
);

$router->get(
    '/admin/categories/{id}/edit',
    [
        CategoryController::class,
        'edit',
    ],
    ['auth']
);

$router->post(
    '/admin/categories/{id}',
    [
        CategoryController::class,
        'update',
    ],
    ['auth']
);

$router->post(
    '/admin/categories/{id}/delete',
    [
        CategoryController::class,
        'delete',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Admin Technology Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/technologies',
    [
        TechnologyController::class,
        'index',
    ],
    ['auth']
);

$router->get(
    '/admin/technologies/create',
    [
        TechnologyController::class,
        'create',
    ],
    ['auth']
);

$router->post(
    '/admin/technologies',
    [
        TechnologyController::class,
        'store',
    ],
    ['auth']
);

$router->get(
    '/admin/technologies/{id}/edit',
    [
        TechnologyController::class,
        'edit',
    ],
    ['auth']
);

$router->post(
    '/admin/technologies/{id}',
    [
        TechnologyController::class,
        'update',
    ],
    ['auth']
);

$router->post(
    '/admin/technologies/{id}/delete',
    [
        TechnologyController::class,
        'delete',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Admin Portfolio Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/portfolio',
    [
        AdminPortfolioController::class,
        'index',
    ],
    ['auth']
);

$router->get(
    '/admin/portfolio/create',
    [
        AdminPortfolioController::class,
        'create',
    ],
    ['auth']
);

$router->post(
    '/admin/portfolio',
    [
        AdminPortfolioController::class,
        'store',
    ],
    ['auth']
);

$router->get(
    '/admin/portfolio/{id}/edit',
    [
        AdminPortfolioController::class,
        'edit',
    ],
    ['auth']
);

$router->post(
    '/admin/portfolio/{id}',
    [
        AdminPortfolioController::class,
        'update',
    ],
    ['auth']
);

$router->post(
    '/admin/portfolio/{id}/delete',
    [
        AdminPortfolioController::class,
        'delete',
    ],
    ['auth']
);

/*
|--------------------------------------------------------------------------
| Request Path
|--------------------------------------------------------------------------
*/

$requestPath = $request->path();

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

$basePath = str_replace(
    '\\',
    '/',
    dirname($scriptName)
);

$basePath = rtrim(
    $basePath,
    '/'
);

if (
    $basePath !== '' &&
    strncasecmp(
        $requestPath,
        $basePath,
        strlen($basePath)
    ) === 0
) {
    $requestPath = substr(
        $requestPath,
        strlen($basePath)
    );
}

if ($requestPath === '') {
    $requestPath = '/';
}

/*
|--------------------------------------------------------------------------
| Dispatch Request — ONCE
|--------------------------------------------------------------------------
*/

$result = $router->dispatch(
    $request->method(),
    $requestPath
);

if ($result !== null) {
    $response->send($result);
}
