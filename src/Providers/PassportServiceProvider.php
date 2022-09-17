<?php

namespace Payload\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Passport\Bridge;
use League\OAuth2\Server\AuthorizationServer;
use Payload\Domain\Collections\ClaimCollection;
use Payload\Http\Responses\BearerTokenResponse;
use Payload\Services\AccessTokenService;

use function app;

class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{
    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     * @throws BindingResolutionException
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(Bridge\ClientRepository::class),
            $this->app->make(Bridge\AccessTokenRepository::class),
            $this->app->make(Bridge\ScopeRepository::class),
            $this->makeCryptKey('private'),
            app('encrypter')->getKey(),
            new BearerTokenResponse() // <-- The class you created above
        );
    }

    public function register()
    {
        parent::register();
        $this->app->bind(AccessTokenService::class, fn () => new AccessTokenService());
    }
}
