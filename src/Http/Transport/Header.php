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

/**
 * HTTP header utils.
 *
 * @internal
 */
final class Header
{
    /**
     * Merges multiple arrays of HTTP headers as passed to CURLOPT_HTTPHEADER. Headers given in later arrays will
     * overwrite the previous one with the same name.
     *
     * @param string[] ...$headers
     *
     * @return string[]
     */
    public static function mergeCurlHeaders(array ...$headers): array
    {
        $mergedHeaders = [];

        foreach ($headers as $headerSet) {
            foreach ($headerSet as $header) {
                if (!\is_string($header)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Non-string header with type "%s" passed.',
                        \is_object($header) ? \get_class($header) : \gettype($header)
                    ));
                }

                $splitHeader = explode(': ', $header, 2);
                if (!isset($splitHeader[0], $splitHeader[1])) {
                    throw new \InvalidArgumentException(sprintf('Header value "%s" is invalid. Expected format is "Header-Name: value".', $header));
                }

                // Enforce lower case for header names to avoid duplicates in mixed case. The case of header names should
                // not matter at all.
                $mergedHeaders[strtolower($splitHeader[0])] = $splitHeader[1];
            }
        }

        $result = [];
        foreach ($mergedHeaders as $header => $value) {
            $result[] = sprintf('%s: %s', $header, $value);
        }

        return $result;
    }
}
