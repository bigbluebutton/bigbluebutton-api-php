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

namespace BigBlueButton\Http\Transport\Bridge\SymfonyHttpClient;

use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Http\Transport\Cookie;
use BigBlueButton\Http\Transport\TransportInterface;
use BigBlueButton\Http\Transport\TransportRequest;
use BigBlueButton\Http\Transport\TransportResponse;
use BigBlueButton\Util\ArrayHelper;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

// @codeCoverageIgnoreStart
if (!interface_exists(HttpClientInterface::class)) {
    throw new \LogicException(sprintf(
        'The "%s" interface was not found. '.
        'You cannot use "%s" without it.'.
        'Try running "composer require" for a package which provides symfony/http-client-implementation.',
        HttpClientInterface::class,
        SymfonyHttpClientTransport::class
    ));
}
// @codeCoverageIgnoreEnd

/**
 * Allows to send requests to the BBB server with a Symfony HTTP Client contract implementation.
 */
final class SymfonyHttpClientTransport implements TransportInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string[]
     */
    private $defaultHeaders;

    /**
     * @var mixed[]
     */
    private $defaultOptions;

    /**
     * @param string[] $defaultHeaders additional HTTP headers to pass on each request
     * @param mixed[]  $defaultOptions Options for Symfony HTTP client passed on every request. See {@link https://symfony.com/doc/current/http_client.html} for details.
     */
    public function __construct(HttpClientInterface $httpClient, array $defaultHeaders = [], array $defaultOptions = [])
    {
        $this->httpClient = $httpClient;
        $this->defaultHeaders = $defaultHeaders;
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * Creates an instance of SymfonyHttpClientTransport with default Symfony HttpClient.
     *
     * @param string[] $defaultHeaders additional HTTP headers to pass on each request
     * @param mixed[]  $defaultOptions Options for Symfony HTTP client passed on every request. See {@link https://symfony.com/doc/current/http_client.html} for details.
     */
    public static function create(array $defaultHeaders = [], array $defaultOptions = []): self
    {
        // @codeCoverageIgnoreStart
        if (!class_exists(HttpClient::class)) {
            throw new \LogicException(sprintf(
                'Cannot create an instance of "%s" when Symfony HttpClient is not installed. '.
                    'Either instantiate the class by yourself and pass a proper implementation or '.
                    'try to run "composer require symfony/http-client".',
                self::class
            ));
        }

        return new self(HttpClient::create(), $defaultHeaders, $defaultOptions);
        // @codeCoverageIgnoreEnd
    }

    /**
     * {@inheritDoc}
     */
    public function request(TransportRequest $request): TransportResponse
    {
        $headers = $this->defaultHeaders;
        $headers['Content-Type'] = $request->getContentType();

        try {
            if ('' !== $payload = $request->getPayload()) {
                $symfonyResponse = $this->httpClient->request(
                    'POST',
                    $request->getUrl(),
                    ArrayHelper::mergeRecursive(false, [
                        'body' => $payload,
                        'headers' => $headers,
                    ], $this->defaultOptions)
                );
            } else {
                $symfonyResponse = $this->httpClient->request(
                    'GET',
                    $request->getUrl(),
                    ArrayHelper::mergeRecursive(false, [
                        'headers' => $headers,
                    ], $this->defaultOptions)
                );
            }
            if ($symfonyResponse->getStatusCode() < 200 || $symfonyResponse->getStatusCode() >= 300) {
                throw new NetworkException('Bad response.', $symfonyResponse->getStatusCode());
            }

            return new TransportResponse($symfonyResponse->getContent(), self::extractJsessionCookie($symfonyResponse));
        } catch (TransportExceptionInterface $e) {
            throw new RuntimeException(sprintf('HTTP request failed: %s', $e->getMessage()), 0, $e);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            throw new NetworkException('Bad response.', $e->getCode(), $e);
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private static function extractJsessionCookie(ResponseInterface $symfonyResponse): ?string
    {
        $responseHeaders = $symfonyResponse->getHeaders();

        if (isset($responseHeaders['set-cookie'])) {
            return Cookie::extractJsessionId($responseHeaders['set-cookie']);
        }

        return null;
    }
}
