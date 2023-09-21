<?php

declare(strict_types=1);

namespace App\Filters;

use App\Filters\AbstractAuthFilter;
use CodeIgniter\Shield\Filters\PermissionFilter AS ShieldPermissionFilter;

/**
 * Permission Authorization Filter.
 */
class PermissionFilter extends AbstractAuthFilter
{
    /**
     * Ensures the user is logged in and has one or more
     * of the permissions as specified in the filter.
     */
    protected function isAuthorized(array $arguments): bool
    {
        foreach ($arguments as $permission) {
            if (auth()->user()->can($permission)) {
                return true;
            }
        }

        return false;
    }
}
