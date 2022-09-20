<?php

namespace Payload\Http\Responses;

use Illuminate\Support\Facades\Log;
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
            Log::error('Error!', [$exception->getMessage()]);
            return parent::generateHttpResponse($response);
        }
    }
}
