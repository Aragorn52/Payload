<?php

namespace Payload\Http\Responses;

use Payload\Facades\AccessTokenFacade;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BearerTokenResponse extends \League\OAuth2\Server\ResponseTypes\BearerTokenResponse
{
    public function generateHttpResponse(ResponseInterface $response): ResponseInterface
    {
        try {
            $newAccessToken = AccessTokenFacade::getNewAccessToken($this->accessToken);
            $this->setAccessToken($newAccessToken);
            return parent::generateHttpResponse($response);
        } catch (Throwable $exception) {
            return parent::generateHttpResponse($response);
        }
    }
}
