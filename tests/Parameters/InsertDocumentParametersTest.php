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

use BigBlueButton\Core\DocumentFile;
use BigBlueButton\Core\DocumentUrl;
use BigBlueButton\Enum\DocumentOption;
use BigBlueButton\Parameters\Config\DocumentOptions;
use BigBlueButton\TestCase;

/**
 * @internal
 */
final class InsertDocumentParametersTest extends TestCase
{
    public function testInsertDocumentParametersWithMultiPresentationsWithoutOptions(): void
    {
        $meetingId = $this->faker->uuid;
        $params    = new InsertDocumentParameters($meetingId);

        $params->addPresentation('https://demo.bigbluebutton.org/bigbluebutton.png');
        $params->addPresentation('https://demo.bigbluebutton.org/bigbluebutton.pdf');
        $params->addPresentation('https://demo.bigbluebutton.org/bigbluebutton.svg');

        $this->assertEquals($meetingId, $params->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations.xml', $params->getPresentationsAsXML());
    }

    public function testInsertDocumentParametersWithOnePresentationAndWithOptions(): void
    {
        $meetingId = $this->faker->uuid;

        $documentOptions = new DocumentOptions();
        $documentOptions
            ->addOption(DocumentOption::DOWNLOADABLE, false)
            ->addOption(DocumentOption::REMOVABLE, true)
            ->addOption(DocumentOption::CURRENT, true)
        ;
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);

        $insertDocumentParameters->addPresentation('https://demo.bigbluebutton.org/bigbluebutton.png', null, null, $documentOptions);

        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());

        $file = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations_with_options.xml';
        $this->assertXmlStringEqualsXmlFile($file, $insertDocumentParameters->getPresentationsAsXML());
    }

    public function testInsertDocumentParametersWithDocumentUrlMultiWithoutOptions(): void
    {
        // ARRANGE
        $meetingId    = $this->faker->uuid;
        $filepath     = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations_with_filenames.xml';
        $documentUrl1 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.png', 'bigbluebutton.png');
        $documentUrl2 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.pdf', 'bigbluebutton.pdf');
        $documentUrl3 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.svg', 'bigbluebutton.svg');

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters
            ->addDocument($documentUrl1)
            ->addDocument($documentUrl2)
            ->addDocument($documentUrl3)
        ;
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(3, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepath, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentUrlOneWithOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations_with_filenames_and_options.xml';
        $documentUrl = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.png', 'bigbluebutton.png');
        $documentUrl->setDownloadable(false)->setRemovable(true)->setCurrent(true);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepath, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentFileOneWithoutOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png';
        $filename    = 'picture.png';
        $filepathXml = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_case_1.xml';
        $documentUrl = new DocumentFile($filepath, $filename);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepathXml, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentFileOneWithOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png';
        $filename    = 'picture.png';
        $filepathXml = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_case_2.xml';
        $documentUrl = new DocumentFile($filepath, $filename);
        $documentUrl->setDownloadable(false)->setRemovable(true)->setCurrent(true);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepathXml, $xmlAsIs);
    }
}
