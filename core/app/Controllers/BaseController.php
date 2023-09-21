<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Activities;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ["core"];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * Lang
     */
    protected $lang;

    /**
     *  Format
     */
    protected $Format;

    /**
     * @var Activities
     */
    protected $Activitie;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->helpers = array_merge($this->helpers, ['setting', 'lang', "theme"]);

        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        $LANG = $this->session->lang;// ? $this->session->lang : setting('App.defaultLocale');
		$this->lang = \Config\Services::language();
        $this->lang->setLocale($LANG);

        $this->Format = [
            "Date" =>  setting('Config.DateFormat'),
            "DateTime" =>  setting('Config.DateTimeFormat'),
            "DateTimes" =>  setting('Config.DateTimeSegFormat'),
            "Time" =>  setting('Config.TimeFormat'),
            "Times" =>  setting('Config.TimeSegFormat'),
        ];

        $this->Activitie = new Activities();

    }

}
