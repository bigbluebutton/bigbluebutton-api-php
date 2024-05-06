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

abstract class UserDataParameters extends BaseParameters
{
    /** @var array<string, mixed> */
    private array $userData = [];

    public function getUserData(string $key): mixed
    {
        return $this->userData[$key];
    }

    public function addUserData(string $key, mixed $value): static
    {
        $this->userData[$key] = $value;

        return $this;
    }

    /**
     * @param array<string, mixed> $array
     *
     * @return array<string, mixed>
     */
    protected function buildUserData(array $array): array
    {
        foreach ($this->userData as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $array['userdata-' . $key] = $value;
        }

        return $array;
    }
}
