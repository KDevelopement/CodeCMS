<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Shield\Filters\AbstractAuthFilter AS AbsAuthFilter;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

use App\Exceptions\PermissionException AS Permission;

/**
 * Group Authorization Filter.
 */
abstract class AbstractAuthFilter extends AbsAuthFilter implements FilterInterface
{
    /**
     * Ensures the user is logged in and a member of one or
     * more groups as specified in the filter.
     *
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (empty($arguments)) {
            return;
        }

        if (! auth()->loggedIn()) {
            session()->set('redirect_url', current_url());
            return redirect()->route('login');
        }

        if ($this->isAuthorized($arguments)) {
            return;
        } else {
            //  Added no app essa opção (risco de sumir com composer update)
            throw new Permission(lang('Auth.notEnoughPrivilege'));
        }

        // Otherwise, we'll just send them to the home page.
        return redirect()->to('/')->with('error', lang('Auth.notEnoughPrivilege'));
    }

    /**
     * We don't have anything to do here.
     *
     * @param Response|ResponseInterface $response
     * @param array|null                 $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
        // Nothing required
    }

    abstract protected function isAuthorized(array $arguments): bool;
}
