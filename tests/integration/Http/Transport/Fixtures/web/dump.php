<?php

declare(strict_types=1);

/**
 * Major portions of this code:.
 *
 * Copyright (c) 2018-2021 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
// Inspired by: https://github.com/symfony/http-client-contracts/blob/main/Test/Fixtures/web/index.php

if ('cli-server' !== \PHP_SAPI) {
    // safe guard against unwanted execution
    throw new \Exception("You cannot run this script directly, it's a fixture for TestHttpServer.");
}

$vars = [];
$input = file_get_contents('php://input');

foreach ($_SERVER as $k => $v) {
    switch ($k) {
        default:
            if (0 !== strpos($k, 'HTTP_')) {
                continue 2;
            }
            // no break
        case 'SERVER_NAME':
        case 'SERVER_PROTOCOL':
        case 'REQUEST_URI':
        case 'REQUEST_METHOD':
        case 'PHP_AUTH_USER':
        case 'PHP_AUTH_PW':
            $vars[$k] = $v;
    }
}

/* @noinspection ForgottenDebugOutputInspection */
var_export(['input' => $input, 'vars' => $vars]);
