<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::show', ["as" => "web.home"]);
$routes->get('about', 'About::show', ["as" => "web.about"]);
$routes->get('services', 'Services::show', ["as" => "web.services"]);
$routes->get('portfolios', 'Portfolios::show', ["as" => "web.portfolios"]);
$routes->get('contact', 'Contact::show', ["as" => "web.contact"]);
$routes->get('privacy-policy', 'Privacy::show', ["as" => "web.privacy"]);
$routes->get('terms-of-use', 'Terms::show', ["as" => "web.terms"]);

$routes->group('app', static function ($routes) {
    $routes->group('register', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->get('/', 'RegisterController::registerView', ["as" => "register"]);
        $routes->post('/', 'RegisterController::registerAction');
    });

    $routes->group('login', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->get('/', 'LoginController::loginView', ["as" => "login"]);
        $routes->post('/', 'LoginController::loginAjaxAction');
        $routes->group('magic-link', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
            $routes->get('/', 'MagicLinkController::loginView', ["as" => "magic-link"]);
            $routes->post('/', 'MagicLinkController::loginAction');
        });
        $routes->group('verify-magic-link', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
            $routes->get('/', 'MagicLinkController::verify', ["as" => "verify-magic-link"]);
        });
    });

    $routes->group('logout', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->get('/', 'LoginController::logoutActionAjax', ["as" => "logout"]);
    });

    $routes->group('auth', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->group('a', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
            $routes->get('show', 'ActionController::show', ["as" => "auth-action-show"]);
            $routes->get('handle', 'ActionController::handle', ["as" => "auth-action-handle"]);
            $routes->get('verify', 'ActionController::verify', ["as" => "auth-action-verify"]);
        });
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
