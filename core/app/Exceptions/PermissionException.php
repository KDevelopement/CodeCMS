<?php

declare(strict_types=1);

namespace App\Exceptions;

//use CodeIgniter\Shield\Authorization\AuthorizationException;
use CodeIgniter\Shield\Exceptions\GroupException AS AuthorizationException;

class PermissionException extends AuthorizationException
{
}
