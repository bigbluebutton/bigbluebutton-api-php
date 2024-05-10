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

use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class UpdateRecordingsParametersTest extends ParameterTestCase
{
    public function testUpdateRecordingsParameters(): void
    {
        $params                 = Fixtures::generateUpdateRecordingsParams();
        $updateRecordingsParams = Fixtures::getUpdateRecordingsParamsMock($params);

        $this->assertEquals($params['recordingId'], $updateRecordingsParams->getRecordingId());
        $this->assertEquals($params['meta_presenter'], $updateRecordingsParams->getMeta('presenter'));

        // Test setters that are ignored by the constructor
        $updateRecordingsParams->setRecordingId($newId = $this->faker->uuid);
        $this->assertEquals($newId, $updateRecordingsParams->getRecordingId());
    }

    public function testParameterArray(): void
    {
        $params                 = Fixtures::generateUpdateRecordingsParams();
        $updateRecordingsParams = Fixtures::getUpdateRecordingsParamsMock($params);

        $this->assertEquals($updateRecordingsParams->toApiDataArray(), $updateRecordingsParams->toArray());
    }
}
