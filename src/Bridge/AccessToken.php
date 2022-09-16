<?php

namespace Payload\Bridge;

use DateTimeImmutable;
use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
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
        $this->addClaims($builder);
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
        $customClaims = app()->make(AbstractClaim::class);
        Assert::isInstanceOf($customClaims, AbstractClaim::class, 'This class not extends AbstractClaim');
        foreach ($customClaims() as $key => $claim) {
            $builder->withClaim($key, $claim);
        }
        return $builder;
    }
}
