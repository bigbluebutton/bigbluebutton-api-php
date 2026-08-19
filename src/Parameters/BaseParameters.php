<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BigBlueButton; if not, see <https://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Parameters;

use BigBlueButton\Attribute\ApiParameterMapper;

/**
 * Class BaseParameters.
 */
abstract class BaseParameters
{
    /**
     * Cache of the API-parameter mapping per class: class-name => method-name => attribute-name.
     * The mapping is immutable per class, so it is computed only once.
     *
     * @var array<string, array<string, string>>
     */
    private static array $attributeCache = [];

    public function getHTTPQuery(): string
    {
        $apiData = $this->toApiDataArray();

        return $this->buildHTTPQuery($apiData);
    }

    /**
     * @return array<string, null|string> // Keys are strings, values are strings or null
     */
    public function toApiDataArray(): array
    {
        $result = [];

        foreach ($this->getAttributeMapping() as $methodName => $attributeName) {
            $value = $this->strictConvertToApiValue($this->{$methodName}());

            // Only include non-null values
            if (null !== $value) {
                $result[$attributeName] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array<string, null|string> $array // Keys and values are both strings
     */
    protected function buildHTTPQuery(array $array): string
    {
        return str_replace(
            ['%20', '!', "'", '(', ')', '*'],
            ['+', '%21', '%27', '%28', '%29', '%2A'],
            http_build_query($array, '', '&', \PHP_QUERY_RFC3986)
        );
    }

    /**
     * @return array<string, string> // method-name => attribute-name
     */
    private function getAttributeMapping(): array
    {
        $class = static::class;

        if (!isset(self::$attributeCache[$class])) {
            $mapping = [];

            foreach ((new \ReflectionClass($this))->getMethods() as $method) {
                foreach ($method->getAttributes(ApiParameterMapper::class) as $attribute) {
                    /** @var ApiParameterMapper $attributeObject */
                    $attributeObject = $attribute->newInstance();

                    $mapping[$method->getName()] = $attributeObject->getAttributeName();
                }
            }

            self::$attributeCache[$class] = $mapping;
        }

        return self::$attributeCache[$class];
    }

    /**
     * Converts any value to API string format with strict type enforcement.
     *
     * @param mixed $value
     */
    private function strictConvertToApiValue($value): ?string
    {
        if (null === $value) {
            return null;
        }

        // Handle BackedEnum cases
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        // Handle arrays: an empty array means "not set" and is omitted, like null
        if (is_array($value)) {
            return [] === $value ? null : $this->convertArrayToApiString($value);
        }

        // Handle all other cases with strict string conversion
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_object($value)) {
            throw new \RuntimeException(sprintf('Cannot convert object of type %s to API value', get_class($value)));
        }

        // Force string conversion for all scalar values
        return (string) $value;
    }

    /**
     * Converts array values to comma-separated string with strict typing.
     *
     * @param array<mixed> $values // Array of mixed values that will be converted
     */
    private function convertArrayToApiString(array $values): string
    {
        $converted = [];
        foreach ($values as $item) {
            $convertedItem = $this->strictConvertToApiValue($item);

            if (null !== $convertedItem) {
                $converted[] = $convertedItem;
            }
        }

        return implode(',', $converted);
    }
}
