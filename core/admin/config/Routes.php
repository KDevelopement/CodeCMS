<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//service('auth')->routes($routes);

$routes->group('/', static function ($routes) {

    helper("setting");
    $RouteNamePanel = setting("Config.AdminPath");

    $routes->group($RouteNamePanel, static function ($routes) {

        $routes->get('/', 'Home::index', ["as" => "admin.home"]);


        $routes->group('register', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
            $routes->get('/', 'RegisterController::registerView', ["as" => "register"]);
            $routes->post('/', 'RegisterController::registerAction');
        });

        $routes->group('login', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
            $routes->get('/', 'LoginController::loginView', ["as" => "login"]);
            $routes->post('/', 'LoginController::loginAjaxAction');
            $routes->group('magic-link', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
                $routes->get('/', 'MagicLinkController::loginView', ["as" => "magic-link"]);
                $routes->post('/', 'MagicLinkController::loginAction');
            });
            $routes->group('verify-magic-link', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
                $routes->get('/', 'MagicLinkController::verify', ["as" => "verify-magic-link"]);
            });
        });

        $routes->group('logout', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
            $routes->get('/', 'LoginController::logoutActionAjax', ["as" => "logout"]);
        });

        $routes->group('auth', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
            $routes->group('a', ['namespace' => 'Admin\Controllers\Auth'], static function ($routes) {
                $routes->get('show', 'ActionController::show', ["as" => "auth-action-show"]);
                $routes->get('handle', 'ActionController::handle', ["as" => "auth-action-handle"]);
                $routes->get('verify', 'ActionController::verify', ["as" => "auth-action-verify"]);
            });
        });
    });
});