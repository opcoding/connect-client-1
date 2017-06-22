<?php

namespace Fei\Service\Connect\Client;

use Fei\ApiClient\AbstractApiClient;
use Fei\ApiClient\ApiClientException;
use Fei\ApiClient\RequestDescriptor;
use Fei\Service\Connect\Common\Token\Tokenizer;
use Guzzle\Http\Exception\BadResponseException;
use Lcobucci\JWT\Token;

/**
 * Class Token
 *
 * @package Fei\Service\Connect\Client
 */
class TokenValidator extends AbstractApiClient
{
    /**
     * Validate a JWT (JSON Web Token)
     *
     * @param string $token
     *
     * @return Token
     *
     * @throws ApiClientException
     */
    public function validate($token)
    {
        $request = (new RequestDescriptor())
            ->setUrl($this->buildUrl(sprintf('/api/token/validate?token=%s', (string) $token)))
            ->setMethod('GET');

        try {
            $token = json_decode($this->send($request)->getBody(), true)['token'];
        } catch (ApiClientException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof BadResponseException) {
                throw $previous;
            }
            throw $e;
        }

        return (new Tokenizer())->parseFromString($token);
    }
}
