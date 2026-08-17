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

namespace BigBlueButton\Core;

use PHPUnit\Framework\TestCase;

/**
 * Class DocumentFileTest.
 *
 * @internal
 */
class DocumentFileTest extends TestCase
{
    public function testDocumentFileAccessorsAndValidation(): void
    {
        $filepath = tempnam(sys_get_temp_dir(), 'doc');
        $document = new DocumentFile($filepath, 'file.txt');

        $this->assertSame($filepath, $document->getFilepath());
        $this->assertSame('file.txt', $document->getName());
        $this->assertTrue($document->isValid());

        // validation is opt-in
        $this->assertFalse($document->getValidation());
        $document->setValidation(true);
        $this->assertTrue($document->getValidation());

        unlink($filepath);
    }

    public function testContentOfMissingFileThrowsException(): void
    {
        $document = new DocumentFile('/nonexistent/path/file.txt', 'file.txt');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to read file');

        $document->getFileContent();
    }

    public function testContentOfEmptyFileThrowsException(): void
    {
        $empty    = tempnam(sys_get_temp_dir(), 'empty');
        $document = new DocumentFile($empty, 'empty.txt');

        try {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Unable to read file');

            $document->getFileContent();
        } finally {
            unlink($empty);
        }
    }

    public function testFileContentIsReadAndCached(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'doc') . '.txt';
        file_put_contents($path, 'document content');
        $document = new DocumentFile($path, 'file.txt');

        $this->assertSame('document content', $document->getFileContent());

        // the content set explicitly takes precedence over the file
        $document->setFileContent('explicit content');
        $this->assertSame('explicit content', $document->getFileContent());

        unlink($path);
    }
}
