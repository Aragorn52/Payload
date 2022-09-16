<?php

namespace PayloadLumenPassport\Services;

use Closure;
use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Passport\Bridge\Client;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use Payload\Bridge\AccessToken;
use Webmozart\Assert\Assert;

class AccessTokenService
{
    private const USER_IDENTIFIER = 'userIdentifier';
    private const SCOPES = 'scopes';
    private const CLIENT = 'client';
    private const PRIVATE_KEY = 'privateKey';
    private const IDENTIFIER = 'identifier';
    private const EXPIRY_DATE_TIME = 'expiryDateTime';

    private AccessTokenEntityInterface $accessToken;

    public function getNewAccessToken(AccessTokenEntityInterface $accessToken): AccessTokenEntityInterface
    {
        $this->accessToken = $accessToken;
        $accessTokenParams = $this->getAccessTokenParams();

        $newAccessToken = new AccessToken(
            $accessTokenParams['userId'],
            $accessTokenParams['scopes'],
            $accessTokenParams['client'],
        );

        $newAccessToken->setPrivateKey($accessTokenParams['privateKey']);
        $newAccessToken->setIdentifier($accessTokenParams['identifier']);
        $newAccessToken->setExpiryDateTime($accessTokenParams['expiryDateTime']);

        return $newAccessToken;
    }

    #[ArrayShape([
        'userId' => 'string|int',
        'scopes' => 'array',
        'client' => Client::class,
        'privateKey' => CryptKey::class,
        'identifier' => 'string',
        'expiryDateTime' => DateTimeImmutable::class
    ])]
    private function getAccessTokenParams(): array
    {
        $userId = Closure::bind($this->getPropertyCallback(self::USER_IDENTIFIER), null, $this->accessToken);
        $scopes = Closure::bind($this->getPropertyCallback(self::SCOPES), null, $this->accessToken);
        $client = Closure::bind($this->getPropertyCallback(self::CLIENT), null, $this->accessToken);
        $privateKey = Closure::bind($this->getPropertyCallback(self::PRIVATE_KEY), null, $this->accessToken);
        $identifier = Closure::bind($this->getPropertyCallback(self::IDENTIFIER), null, $this->accessToken);
        $expiryDateTime = Closure::bind($this->getPropertyCallback(self::EXPIRY_DATE_TIME), null, $this->accessToken);

        $userId = $userId($this->accessToken);
        Assert::integerish($userId, 'Error! This user id is not numeric.');
        $scopes = $scopes($this->accessToken);
        Assert::isArray($scopes, 'Error! This scope is not array.');
        $client = $client($this->accessToken);
        Assert::isInstanceOf($client, Client::class, 'Error! This client not instanceof entity Client.');
        $privateKey = $privateKey($this->accessToken);
        Assert::isInstanceOf($privateKey, CryptKey::class, 'Error! This privateKey not instanceof entity CryptKey.');
        $identifier = $identifier($this->accessToken);
        Assert::string($identifier, 'Error! This identifier is not string.');
        $expiryDateTime = $expiryDateTime($this->accessToken);
        Assert::isInstanceOf(
            $expiryDateTime,
            DateTimeImmutable::class,
            'Error! This expiryDateTime not instanceof entity DateTimeImmutable.'
        );

        return [
            'userId' => $userId,
            'scopes' => $scopes,
            'client' => $client,
            'privateKey' => $privateKey,
            'identifier' => $identifier,
            'expiryDateTime' => $expiryDateTime,
        ];
    }

    private function getPropertyCallback(string $propertyName): Closure
    {
        Assert::true(property_exists($this->accessToken, $propertyName), 'Error! Property dont exists');
        return function (AccessTokenEntityInterface $accessToken) use ($propertyName) {
            return $accessToken->{$propertyName};
        };
    }
}
