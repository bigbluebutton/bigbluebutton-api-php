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

use BigBlueButton\Enum\WebHookEvent;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class HooksCreateParametersTest extends ParameterTestCase
{
    public function testHooksCreateParameters(): void
    {
        $hooksCreateParameters = new HooksCreateParameters($callBackUrl = $this->faker->url);

        // Get raw values from fixtures and ensure we have an array
        $rawEvents = (array) Fixtures::randomEnumValues($this->faker, WebHookEvent::class, null, 'array');

        // Convert to WebHookEvent instances with proper type handling
        $eventIds = [];
        foreach ($rawEvents as $event) {
            if ($event instanceof WebHookEvent) {
                $eventIds[] = $event;

                continue;
            }

            if (is_string($event)) {
                $eventIds[] = WebHookEvent::from($event);

                continue;
            }

            if (is_object($event) && method_exists($event, '__toString')) {
                $eventIds[] = WebHookEvent::from((string) $event);

                continue;
            }

            throw new \InvalidArgumentException('Invalid event type provided');
        }

        $this->assertEquals($callBackUrl, $hooksCreateParameters->getCallbackUrl());

        // Test setters
        $hooksCreateParameters->setMeetingId($meetingId = $this->faker->uuid);
        $hooksCreateParameters->setGetRaw($getRaw = $this->faker->boolean);
        $hooksCreateParameters->setEventId($eventIds);

        $this->assertEquals($meetingId, $hooksCreateParameters->getMeetingId());
        $this->assertEquals($getRaw, $hooksCreateParameters->getRaw());
        $this->assertEquals($eventIds, $hooksCreateParameters->getEventId());
    }

    public function testHooksCreateSetters(): void
    {
        $hooksCreateParams = new HooksCreateParameters('https://hook.example.com/old');
        $hooksCreateParams->setCallbackUrl('https://hook.example.com/new');

        $this->assertSame('https://hook.example.com/new', $hooksCreateParams->getCallbackUrl());
    }
}
