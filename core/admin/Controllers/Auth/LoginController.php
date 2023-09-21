<?php

declare(strict_types=1);

namespace Admin\Controllers\Auth;

use CodeIgniter\Shield\Controllers\LoginController AS BaseController;
//use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Traits\Viewable;

class LoginController extends BaseController
{
    use Viewable;

    protected $helpers = ['setting'];

    /**
     * Displays the form the login to the site.
     *
     * @return RedirectResponse|string
     */
    public function loginView()
    {
        
        if (auth()->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect());
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // If an action has been defined, start it up.
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show');
        }

        //return $this->view(setting('Auth.views')['login']);
        $Data = [
            "Title" => lang('Auth.login'),
        ];
        return $this->Theme('auth\\login', $Data, "App");
    }
    /**
     * Attempts to log the user in. (AJAX)
     */
    public function loginAjaxAction() //: RedirectResponse
    {

        if (!$this->request->isAJAX()) {
            $Result = [
                'date' => null,
                'message' => "ATENÇÃO: Se o error persistir entre em contato com suporte.",
                'status' => false, 
            ];
            return $this->response->setJSON($Result);
        }

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = $this->getValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules)) {
            $Result = [
                'redirect' => url_to("auth-action-show"),
                'message' => "Ocorreu algum erro, verefique o formulario.",
                'validation' => $this->validator->getErrors(),
                'invalid' => true,
                'status' => false, 
            ];
            return $this->response->setJSON($Result);
            //return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentials             = $this->request->getPost(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // Attempt to login
        $result = $authenticator->remember($remember)->attempt($credentials);
        if (! $result->isOK()) {
            $Result = [
                'redirect' => url_to("auth-action-show"),
                'message' => $result->reason(),
                'validation' => null,
                'invalid' => true,
                'status' => false, 
            ];
            return $this->response->setJSON($Result);
            //return redirect()->route('login')->withInput()->with('error', $result->reason());
        }

        // If an action has been defined for login, start it up.
        if ($authenticator->hasAction()) {
            $Result = [
                'redirect' => url_to("auth-action-show"),
                'message' => "Você fez login com sucesso!",
                'validation' => null,
                'invalid' => false,
                'status' => true, 
            ];
            return $this->response->setJSON($Result);
            //return redirect()->route('auth-action-show')->withCookies();
        }

        if (session("redirect_url")) {
            $UrlRedirect = session("redirect_url");
        } else {
            $UrlRedirect = config('Auth')->loginRedirect();
        }

        $Result = [
            'redirect' => $UrlRedirect,
            'message' => "Você fez login com sucesso!",
            'validation' => null,
            'invalid' => false,
            'status' => true, 
        ];
        return $this->response->setJSON($Result);

        //return redirect()->to(config('Auth')->loginRedirect())->withCookies();
    }

    /**
     * Attempts to log the user in.
     */
    public function loginAction(): RedirectResponse
    {
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = $this->getValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentials             = $this->request->getPost(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // Attempt to login
        $result = $authenticator->remember($remember)->attempt($credentials);
        if (! $result->isOK()) {
            return redirect()->route('login')->withInput()->with('error', $result->reason());
        }

        // If an action has been defined for login, start it up.
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show')->withCookies();
        }

        return redirect()->to(config('Auth')->loginRedirect())->withCookies();
    }

    /**
     * Returns the rules that should be used for validation.
     *
     * @return array<string, array<string, array<string>|string>>
     * @phpstan-return array<string, array<string, string|list<string>>>
     */
    protected function getValidationRules(): array
    {
        return setting('Validation.login') ?? [
            // 'username' => [
            //     'label' => 'Auth.username',
            //     'rules' => config('AuthSession')->usernameValidationRules,
            // ],
            'email' => [
                'label' => 'Auth.email',
                'rules' => config('AuthSession')->emailValidationRules,
            ],
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'required|' . Passwords::getMaxLenghtRule(),
                'errors' => [
                    'max_byte' => 'Auth.errorPasswordTooLongBytes',
                ],
            ],
        ];
    }

    /**
     * Logs the current user out.
     */
    public function logoutActionAjax() //: RedirectResponse
    {
        if (!$this->request->isAJAX()) {
            $Result = [
                'date' => null,
                'redirect' => null,
                'message' => "ATENÇÃO: Se o error persistir entre em contato com suporte.",
                'status' => false, 
            ];
            return $this->response->setJSON($Result);
        }
        $url = config('Auth')->logoutRedirect();
        auth()->logout();
        $Result = [
            'date' => null,
            'redirect' => $url,
            'message' => lang('Auth.successLogout'),
            'status' => true, 
        ];
        return $this->response->setJSON($Result);
    }

    /**
     * Logs the current user out.
     */
    public function logoutAction(): RedirectResponse
    {
        // Capture logout redirect URL before auth logout,
        // otherwise you cannot check the user in `logoutRedirect()`.
        $url = config('Auth')->logoutRedirect();

        auth()->logout();

        return redirect()->to($url)->with('message', lang('Auth.successLogout'));
    }
}
