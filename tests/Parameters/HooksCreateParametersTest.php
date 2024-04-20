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

use BigBlueButton\TestCase;

/**
 * @internal
 */
class HooksCreateParametersTest extends TestCase
{
    public function testHooksCreateParameters(): void
    {
        // create string of eventIds
        $eventIds = [];
        for ($i = 0; $i < $this->faker->numberBetween(1, 5); ++$i) {
            $eventIds[] = $this->faker->uuid;
        }
        $eventIds = implode(',', $eventIds);

        $hooksCreateParameters = new HooksCreateParameters($callBackUrl = $this->faker->url);

        $this->assertEquals($callBackUrl, $hooksCreateParameters->getCallbackUrl());

        // Test setters that are ignored by the constructor
        $hooksCreateParameters->setMeetingId($meetingId = $this->faker->uuid);
        $hooksCreateParameters->setGetRaw($getRaw = $this->faker->boolean);
        $hooksCreateParameters->setEventId($eventIds);
        $this->assertEquals($meetingId, $hooksCreateParameters->getMeetingId());
        $this->assertEquals($getRaw, $hooksCreateParameters->getRaw());
        $this->assertEquals($eventIds, $hooksCreateParameters->getEventId());
    }
}
