<?php

declare(strict_types=1);

/**
 * This file is part of littleredbutton/bigbluebutton-api-php.
 *
 * littleredbutton/bigbluebutton-api-php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * littleredbutton/bigbluebutton-api-php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with littleredbutton/bigbluebutton-api-php. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Http\Transport\Bridge\PsrHttpClient;

use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Http\Transport\Cookie;
use BigBlueButton\Http\Transport\TransportInterface;
use BigBlueButton\Http\Transport\TransportRequest;
use BigBlueButton\Http\Transport\TransportResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

// @codeCoverageIgnoreStart
if (!interface_exists(ClientInterface::class)) {
    throw new \LogicException(sprintf(
        'The "%s" interface was not found. '.
            'You cannot use "%s" without it.'.
            'Try running "composer require" for a package which provides psr/http-client-implementation.',
        ClientInterface::class,
        PsrHttpClientTransport::class
    ));
}

if (!interface_exists(RequestFactoryInterface::class)) {
    throw new \LogicException(sprintf(
        'The "%s" interface was not found. '.
            'You cannot use "%s" without it.'.
            'Try running "composer require" for a package which provides psr/http-factory-implementation.',
        RequestFactoryInterface::class,
        PsrHttpClientTransport::class
    ));
}

if (!interface_exists(StreamFactoryInterface::class)) {
    throw new \LogicException(sprintf(
        'The "%s" interface was not found. '.
        'You cannot use "%s" without it.'.
        'Try running "composer require" for a package which provides psr/http-factory-implementation.',
        StreamFactoryInterface::class,
        PsrHttpClientTransport::class
    ));
}
// @codeCoverageIgnoreEnd

/**
 * Allows to send requests to the BBB server with a {@link https://www.php-fig.org/psr/psr-18/} implementation.
 */
final class PsrHttpClientTransport implements TransportInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var string[]
     */
    private $defaultHeaders;

    /**
     * @param string[] $defaultHeaders additional headers to pass on each request
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        array $defaultHeaders = []
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * {@inheritDoc}
     */
    public function request(TransportRequest $request): TransportResponse
    {
        if ('' !== $payload = $request->getPayload()) {
            $psrRequest = $this->requestFactory->createRequest('POST', $request->getUrl());
            $psrRequest = $psrRequest->withBody($this->streamFactory->createStream($payload));
        } else {
            $psrRequest = $this->requestFactory->createRequest('GET', $request->getUrl());
        }
        foreach ($this->defaultHeaders as $header => $value) {
            $psrRequest = $psrRequest->withHeader($header, $value);
        }

        $psrRequest = $psrRequest->withHeader('Content-Type', $request->getContentType());

        try {
            $psrResponse = $this->httpClient->sendRequest($psrRequest);
        } catch (ClientExceptionInterface $e) {
            throw new RuntimeException(sprintf('HTTP request failed: %s', $e->getMessage()), 0, $e);
        }

        if ($psrResponse->getStatusCode() < 200 || $psrResponse->getStatusCode() >= 300) {
            throw new NetworkException('Bad response.', $psrResponse->getStatusCode());
        }

        $headerValues = $psrResponse->getHeader('Set-Cookie');
        $jsessionCookie = Cookie::extractJsessionId($headerValues);

        return new TransportResponse($psrResponse->getBody()->getContents(), $jsessionCookie);
    }
}
