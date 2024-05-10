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

namespace BigBlueButton\TestServices;

use BigBlueButton\TestCase;

/**
 * @internal
 */
class ParamsIterator extends TestCase
{
    /**
     * @param array<string, mixed> $params
     */
    public function iterate(array $params, string $url): void
    {
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if ($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            if (!is_array($value)) {
                $this->assertStringContainsString($value, urldecode($url));
            } else {
                $this->iterate($value, $url);
            }
        }
    }
}
