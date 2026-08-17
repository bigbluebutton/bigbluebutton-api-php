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

/**
 * @internal
 */
class HooksDestroyParametersTest extends ParameterTestCase
{
    public function testHooksDestroyParameters(): void
    {
        $hookId = (string) $this->faker->numberBetween(1, 50);

        $hooksDestroyParameters = new HooksDestroyParameters($hookId);

        $this->assertEquals($hookId, $hooksDestroyParameters->getHookId());
    }

    public function testSetHookId(): void
    {
        $originalHookId = 'original-hook-123';
        $newHookId      = 'new-hook-456';

        $hooksDestroyParameters = new HooksDestroyParameters($originalHookId);

        // Test initial value
        $this->assertEquals($originalHookId, $hooksDestroyParameters->getHookId());

        // Test setting new value
        $result = $hooksDestroyParameters->setHookId($newHookId);

        $this->assertEquals($newHookId, $hooksDestroyParameters->getHookId());
        $this->assertSame($hooksDestroyParameters, $result); // Test fluent interface
    }

    public function testHooksDestroyParametersWithEmptyHookId(): void
    {
        $hooksDestroyParameters = new HooksDestroyParameters('');

        $this->assertEquals('', $hooksDestroyParameters->getHookId());
    }

    public function testHooksDestroyParametersWithLongHookId(): void
    {
        $longHookId             = str_repeat('a', 100);
        $hooksDestroyParameters = new HooksDestroyParameters($longHookId);

        $this->assertEquals($longHookId, $hooksDestroyParameters->getHookId());
    }

    public function testHooksDestroyParametersWithSpecialCharacters(): void
    {
        $specialHookId          = 'hook-123_abc.xyz';
        $hooksDestroyParameters = new HooksDestroyParameters($specialHookId);

        $this->assertEquals($specialHookId, $hooksDestroyParameters->getHookId());
    }

    public function testHooksDestroyParametersFluentInterface(): void
    {
        $hooksDestroyParameters = new HooksDestroyParameters('initial-hook');

        $result = $hooksDestroyParameters
            ->setHookId('updated-hook-1')
            ->setHookId('updated-hook-2')
        ;

        $this->assertSame($hooksDestroyParameters, $result);
        $this->assertEquals('updated-hook-2', $hooksDestroyParameters->getHookId());
    }

    public function testHooksDestroyParametersToApiDataArray(): void
    {
        $hookId                 = 'test-hook-789';
        $hooksDestroyParameters = new HooksDestroyParameters($hookId);

        $apiData = $hooksDestroyParameters->toApiDataArray();

        $this->assertIsArray($apiData);
        $this->assertEquals($hookId, $apiData['hookID']);
    }

    public function testHooksDestroyParametersGetHTTPQuery(): void
    {
        $hookId                 = 'test-hook-456';
        $hooksDestroyParameters = new HooksDestroyParameters($hookId);

        $query = $hooksDestroyParameters->getHTTPQuery();

        $this->assertStringContainsString("hookID={$hookId}", $query);
    }
}
