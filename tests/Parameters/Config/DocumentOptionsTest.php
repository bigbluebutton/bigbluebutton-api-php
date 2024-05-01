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

namespace BigBlueButton\Parameters\Config;

use BigBlueButton\Enum\DocumentOption;
use BigBlueButton\TestCase;

/**
 * @internal
 */
class DocumentOptionsTest extends TestCase
{
    public function testAddOption(): void
    {
        $documentOptions = new DocumentOptions();
        $documentOptions
            ->addOption(DocumentOption::DOWNLOADABLE, false)
            ->addOption(DocumentOption::REMOVABLE, true)
            ->addOption(DocumentOption::CURRENT, true)
        ;

        self::assertInstanceOf(DocumentOptions::class, $documentOptions);
    }

    public function testOptions(): void
    {
        $documentOptions = new DocumentOptions();
        $documentOptions
            ->addOption(DocumentOption::DOWNLOADABLE, false)
            ->addOption(DocumentOption::REMOVABLE, true)
            ->addOption(DocumentOption::CURRENT, true)
        ;

        $optionsToBe = [
            'downloadable' => false,
            'removable'    => true,
            'current'      => true,
        ];

        self::assertIsArray($documentOptions->getOptions());
        self::assertEquals($optionsToBe, $documentOptions->getOptions());
    }
}
