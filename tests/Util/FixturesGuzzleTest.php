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

namespace BigBlueButton\Util;

use BigBlueButton\BigBlueButton;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

/**
 * This test verifies that all the functionality that works with curl also works
 * with an injected http client. In this case, we use Guzzle.
 *
 * @internal
 */
class FixturesGuzzleTest extends FixturesTest
{
    public function setUp(): void
    {
        parent::setUp();

        $client    = new Client();
        $factory   = new HttpFactory();
        $this->bbb = BigBlueButton::createWithHttpClient(
            $client,
            $factory,
            $factory,
            getenv('BBB_SERVER_BASE_URL') ?: $this->fail(),
            getenv('BBB_SECRET') ?: getenv('BBB_SECURITY_SALT') ?: $this->fail(),
        );
    }
}
