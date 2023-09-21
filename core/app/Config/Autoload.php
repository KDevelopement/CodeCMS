<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 *       the values in this file will overwrite the framework's values.
 *
 * NOTE: This class is required prior to Autoloader instantiation,
 *       and does not extend BaseConfig.
 *
 * @immutable
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'         => APPPATH
     *   ];
     *
     * @var array<string, array<int, string>|string>
     * @phpstan-var array<string, string|list<string>>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'Admin'       => APPPATH . '../admin',
    ];


    /**
     * Get all Plugins
     */
    public function __construct()
    {
        $Plugins = self::pluginsPaths();
        foreach ($Plugins as $Plugin=>$Path) {
            $this->psr4 = array_merge($this->psr4, [
                "$Plugin" => $Path,
            ]);
        }
        parent::__construct();
    }

    /**
     * Feth all plugins paths
     *
     * @return array
     */
    public static function pluginsPaths(): array
    {
        $PluginPath = ROOTPATH . "../plugins/";
        if (!is_dir($PluginPath)) {
            return [];
        }
        $Paths = [];
        $Dir = scandir($PluginPath);
        unset($Dir[0]);
        unset($Dir[1]);
        foreach ($Dir as $Plugin) {
            if (preg_match('~([^/]+)\.[A-Za-z0-9]+~', $Plugin)) {
                continue;
            }
            $PluginInfo = $PluginPath . $Plugin . "/info.json";
            $PluginInstalled = $PluginPath . $Plugin . "/Config/.installed";
            if (file_exists($PluginInfo) AND file_exists($PluginInstalled)) {
                $Paths[$Plugin] = $PluginPath . $Plugin;// . "/";
            }
        }
        return $Paths;
    }

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $helpers = [];
}
