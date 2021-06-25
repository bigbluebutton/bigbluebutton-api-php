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
namespace BigBlueButton\Http\Transport;

use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Util\ArrayHelper;

/**
 * Allows to send requests to the BBB server with the pure PHP cURL implementation.
 */
final class CurlTransport implements TransportInterface
{
    public const DEFAULT_CURL_OPTIONS = [
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_CONNECTTIMEOUT => self::DEFAULT_CONNECT_TIMEOUT,
        CURLOPT_TIMEOUT        => self::DEFAULT_TIMEOUT,
    ];

    /**
     * These options must be always set to the given value to ensure that the transport operates properly.
     */
    private const INTERNAL_CURL_OPTIONS = [
        CURLOPT_ENCODING       => 'UTF-8',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
    ];

    /**
     * Time to connect to BigBlueButton host.
     */
    private const DEFAULT_CONNECT_TIMEOUT = 10;

    /**
     * Time to for the whole request and response flow (inclusive connect, see above).
     */
    private const DEFAULT_TIMEOUT = 30;

    /**
     * @var mixed[]
     */
    private $curlOptions;

    /**
     * Allows to inject custom cURL options used on dispatching request to BBB.
     * Please note that you must ensure on your own that the usage of custom options does not break the transport.
     *
     * @see CurlTransport::createWithDefaultOptions() Please use this factory method to create the CurlTransport with the recommended defaults.
     *
     * @link https://www.php.net/manual/en/function.curl-setopt.php List of known cURL options
     * @param mixed[] $curlOptions A list of cURL options to pass to the cURL handle. Option name as key, option value as value.
     */
    public function __construct(array $curlOptions = [])
    {
        $this->curlOptions = $curlOptions;
    }

    /**
     * @param  string[] $additionalCurlOptions A list of additional cURL options to pass to the cURL handle. Option name as key, option value as value.
     * @return self
     */
    public static function createWithDefaultOptions(array $additionalCurlOptions = []): self
    {
        // @codeCoverageIgnoreStart
        return new self(self::mergeCurlOptions($additionalCurlOptions, self::DEFAULT_CURL_OPTIONS));
        // @codeCoverageIgnoreEnd
    }

    /**
     * {@inheritDoc}
     */
    public function request(TransportRequest $request): TransportResponse
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('curl')) {
            throw new RuntimeException('Curl PHP module is not installed or not enabled.');
        }
        // @codeCoverageIgnoreEnd

        $ch = curl_init();
        // @codeCoverageIgnoreStart
        if (!$ch) {
            throw new RuntimeException('Could not create curl instance. Error: ' . curl_error($ch));
        }
        // @codeCoverageIgnoreEnd

        // Needed to store the JSESSIONID
        $cookieFile = @tmpfile(); // Silenced as tmpfile can throw notices like "creating file in system temporary directory".
        // @codeCoverageIgnoreStart
        if (false === $cookieFile) {
            throw new RuntimeException('Could not create temporary file for cookies.');
        }
        // @codeCoverageIgnoreEnd
        $cookieFilePath = stream_get_meta_data($cookieFile)['uri'];

        $options = self::mergeCurlOptions(
            self::buildCookieOptions($cookieFilePath),
            self::buildUrlOptions($request),
            self::buildPostOptions($request),
            self::INTERNAL_CURL_OPTIONS,
            $this->curlOptions
        );

        foreach ($options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        $data = curl_exec($ch);
        // @codeCoverageIgnoreStart
        if ($data === false) {
            throw new NetworkException('Error during curl_exec. Error: ' . curl_error($ch));
        }
        // @codeCoverageIgnoreEnd

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new NetworkException('Bad response.', $httpCode);
        }

        curl_close($ch);

        $cookies   = file_get_contents($cookieFilePath);
        $sessionId = null;

        if (strpos($cookies, 'JSESSIONID') !== false) {
            preg_match('/(?:JSESSIONID\s*)(?<JSESSIONID>.*)/', $cookies, $output);
            $sessionId = $output['JSESSIONID'];
        }

        return new TransportResponse($data, $sessionId);
    }

    /**
     * @param  string  $cookieFilePath
     * @return mixed[]
     */
    private static function buildCookieOptions(string $cookieFilePath): array
    {
        return [
            CURLOPT_COOKIEFILE => $cookieFilePath,
            CURLOPT_COOKIEJAR  => $cookieFilePath,
        ];
    }

    /**
     * @param  TransportRequest $request
     * @return mixed[]
     */
    private static function buildPostOptions(TransportRequest $request): array
    {
        $options = [];

        if ('' !== $payload = $request->getPayload()) {
            $options[CURLOPT_HEADER]        = 0;
            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
            $options[CURLOPT_POST]          = 1;
            $options[CURLOPT_POSTFIELDS]    = $payload;
            $options[CURLOPT_HTTPHEADER]    = [
                'Content-type: ' . $request->getContentType(),
                'Content-length: ' . mb_strlen($payload),
            ];
        }

        return $options;
    }

    /**
     * @param  TransportRequest $request
     * @return array
     */
    private static function buildUrlOptions(TransportRequest $request): array
    {
        return [
            CURLOPT_URL => $request->getUrl(),
        ];
    }

    /**
     * @param  array<int,mixed> ...$options The first set value of an option will be preserved.
     *                                      Any later options the same key will be discarded.
     *                                      The CURLOPT_HTTPHEADER will be treated in a special
     *                                      way and merged instead, but on values with same header name
     *                                      only the header from the first option set will be preserved.
     * @return array
     */
    private static function mergeCurlOptions(array ...$options): array
    {
        // headers need special treatment regarding merging them to avoid duplicate fields
        $headerSets = [];
        foreach (array_reverse($options, true) as $setKey => $optionSet) {
            foreach ($optionSet as $key => $option) {
                switch ($key) {
                    case CURLOPT_HTTPHEADER:
                        unset($options[$setKey][$key]);
                        $headerSets[] = $option;

                        break;
                }
            }
        }
        $headerOptions = [
            CURLOPT_HTTPHEADER => Header::mergeCurlHeaders(...$headerSets),
        ];

        return ArrayHelper::mergeRecursive(true, $headerOptions, ...$options);
    }
}
