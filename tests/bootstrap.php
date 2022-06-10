<?php
/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2018 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

use Symfony\Component\Dotenv\Dotenv;

error_reporting(-1);
date_default_timezone_set('UTC');

// Include the composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Load environment
$dotenv = new Dotenv();
// usePutenv was not available in version 3.4 und early 4.x versions of symfony/dotenv, so make it optional here
if (method_exists($dotenv, 'usePutenv')) {
    $dotenv->usePutenv(true);
}

// loadEnv was not available in version 3.4 und early 4.x versions of symfony/dotenv, so make it optional here
if (method_exists($dotenv, 'loadEnv')) {
    $dotenv->loadEnv(dirname(__DIR__).'/.env');
} else {
    $files = [];
    foreach ([dirname(__DIR__).'/.env', dirname(__DIR__).'/.env.local'] as $file) {
        if (file_exists($file)) {
            $files[] = $file;
        }
    }

    $dotenv->load(...$files);
}

// Include custom test class
require_once __DIR__.'/TestCase.php';
