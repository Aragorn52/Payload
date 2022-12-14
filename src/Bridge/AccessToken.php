<?php

namespace Payload\Bridge;

use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use Payload\Services\AbstractClaimService;
use Webmozart\Assert\Assert;

class AccessToken extends PassportAccessToken
{
    use AccessTokenTrait;

    /**
     * Generate a JWT from the access token
     *
     * @return Token
     */
    private function convertToJWT(): Token
    {
        $this->initJwtConfiguration();
        $builder = $this->build();
        try {
            $this->addClaims($builder);
        } catch (\Throwable $e) {
            Log::error('Error!', [$e->getMessage()]);
        }

        return $builder->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }

    private function build(): Builder
    {
        return $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string) $this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes());
    }

    private function addClaims(Builder $builder): Builder
    {
        $service = app()->make(AbstractClaimService::class);
        Assert::isInstanceOf($service, AbstractClaimService::class, "Not instanceof " . AbstractClaimService::class);
        $builder->withClaim('payload', $service($this->getUserIdentifier())->asArray());
        return $builder;
    }
}
