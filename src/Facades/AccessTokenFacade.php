<?php

namespace Payload\Facades;

use Illuminate\Support\Facades\Facade;
use Payload\Services\AccessTokenService;

class AccessTokenFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AccessTokenService::class;
    }
}
