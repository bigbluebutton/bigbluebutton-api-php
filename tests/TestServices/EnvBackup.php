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

namespace BigBlueButton\TestServices;

/**
 * Saves and restores environment variables around tests that modify them.
 */
class EnvBackup
{
    /**
     * @param array<int, string> $names
     *
     * @return array<string, false|string>
     */
    public static function save(array $names): array
    {
        $backup = [];
        foreach ($names as $name) {
            $backup[$name] = getenv($name);
        }

        return $backup;
    }

    /**
     * @param array<string, false|string> $backup
     */
    public static function restore(array $backup): void
    {
        foreach ($backup as $name => $value) {
            if (false === $value) {
                putenv($name);
            } else {
                putenv($name . '=' . $value);
            }
        }
    }
}
