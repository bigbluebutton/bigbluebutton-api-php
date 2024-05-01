<?php

declare(strict_types=1);

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

use BigBlueButton\Enum\DocumentOption;
use BigBlueButton\Parameters\Config\DocumentOptions;
use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
final class InsertDocumentParametersTest extends TestCase
{
    public function testInsertDocumentParameters(): void
    {
        $meetingId                = $this->faker->uuid;
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);

        $insertDocumentParameters
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_PDF.pdf')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2022/02/Free_Test_Data_117KB_JPG.jpg')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/500kb.png')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/1.svg')
        ;

        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(
            Fixtures::REQUEST_PATH . 'insert_document_presentations.xml',
            $insertDocumentParameters->getPresentationsAsXML()
        );
    }

    public function testInsertDocumentWithOptions(): void
    {
        $meetingId = $this->faker->uuid;

        $documentOptions = new DocumentOptions();
        $documentOptions
            ->addOption(DocumentOption::DOWNLOADABLE, false)
            ->addOption(DocumentOption::REMOVABLE, true)
            ->addOption(DocumentOption::CURRENT, true)
        ;
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);

        $insertDocumentParameters->addPresentation('https://demo.bigbluebutton.org/biglbuebutton.png', null, null, $documentOptions);

        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(
            Fixtures::REQUEST_PATH . 'insert_document_presentations_with_options.xml',
            $insertDocumentParameters->getPresentationsAsXML()
        );
    }
}
