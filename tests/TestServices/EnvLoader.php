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

use Dotenv\Dotenv;

/**
 * @see https://github.com/vlucas/phpdotenv
 */
class EnvLoader
{
    public static function loadEnvironmentVariables(): void
    {
        $envPath      = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $envFileMain  = '.env';
        $envFileLocal = '.env.local';

        if (file_exists($envPath . $envFileLocal)) {
            $envFile = $envFileLocal;
        } elseif (file_exists($envPath . $envFileMain)) {
            $envFile = $envFileMain;
        } else {
            $envPath = realpath($envPath);

            throw new \RuntimeException("Environment file ('{$envFileMain}' nor '{$envFileLocal}') not found in directory '{$envPath}'!");
        }

        $dotenv = Dotenv::createUnsafeImmutable($envPath, $envFile);
        $dotenv->load();
        $dotenv->required(['BBB_SECRET', 'BBB_SERVER_BASE_URL']);
    }
}
