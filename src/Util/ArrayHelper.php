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

namespace BigBlueButton\Util;

/**
 * @internal
 */
final class ArrayHelper
{
    /**
     * Replacement for the original array_merge_recursive which does not preserve keys when numeric (like the cURL options).
     *
     * @see https://www.php.net/manual/en/function.array-merge-recursive.php
     *
     * @param bool $reorderNested reorder nested array starting from the second level instead of merging them
     */
    public static function mergeRecursive(bool $reorderNested, array ...$arrays): array
    {
        $merged = [];

        foreach (array_reverse($arrays) as $array) {
            foreach ($array as $key => $value) {
                // On top level, we need to preserve keys to ensure the CURLOPT_* (int) are preserved. Below we classically need to append keys (for CURLOPT_HTTPHEADER).
                if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key])) {
                    $merged[$key] = $reorderNested ? self::mergeArrays($value, $merged[$key]) : self::mergeRecursive(false, $value, $merged[$key]);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }

    private static function mergeArrays(array $array1, array $array2): array
    {
        $newArray = [];

        foreach ($array1 as $innerValue) {
            $newArray[] = $innerValue;
        }

        foreach ($array2 as $innerValue) {
            $newArray[] = $innerValue;
        }

        return $newArray;
    }
}
