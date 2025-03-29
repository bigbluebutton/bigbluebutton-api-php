<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).
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
    public function getHTTPQuery(): string
    {
        // todo: remove items with NULL-value from $this->toApiDataArray() at this point to ensure
        //       that the request string will be as short as possible.

        return $this->buildHTTPQuery($this->toApiDataArray());
    }

    /**
     * @return array<string, mixed>
     *
     * @deprecated This function is replaced by getApiData()
     */
    abstract public function toArray(): array;

    /**
     * @return array<string, mixed>
     */
    public function toApiDataArray(): array
    {
        $result = [];

        $classReflection = new \ReflectionClass($this);

        // check the attributes of each method if ApiParameterMapper-Attribute is used. Take value into result.
        foreach ($classReflection->getMethods() as $method) {
            foreach ($method->getAttributes(ApiParameterMapper::class) as $attribute) {
                /** @var ApiParameterMapper $attributeObject */
                $attributeObject = $attribute->newInstance();

                // get key and value
                $key   = $attributeObject->getAttributeName(); // the value of the argument inside the attribute
                $value = $this->{$method->getName()}();        // the value of the property via the method with that attribute (typically the getter-function)

                // transform
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }

                if (is_array($value)) {
                    $value = join(',', $value);
                }

                // store
                $result[$key] = $value;
            }
        }

        return $result;
    }

    protected function buildHTTPQuery(mixed $array): string
    {
        return str_replace(['%20', '!', "'", '(', ')', '*'], ['+', '%21', '%27', '%28', '%29', '%2A'], http_build_query(array_filter($array), '', '&', \PHP_QUERY_RFC3986));
    }
}
