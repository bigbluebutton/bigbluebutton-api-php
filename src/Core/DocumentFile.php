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

namespace BigBlueButton\Core;

class DocumentFile extends Document
{
    private string $filepath;

    /** @deprecated only needed until DocumentTrait::addPresentation() has not been removed */
    private ?string $fileContent = null;

    public function __construct(string $filepath, string $name)
    {
        $this->setFilepath($filepath);
        $this->setName($name);
    }

    public function setFilepath(string $filepath): self
    {
        $this->filepath = $filepath;

        return $this;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @deprecated only needed until DocumentTrait::addPresentation() has not been removed
     */
    public function setFileContent(string $fileContent): self
    {
        $this->fileContent = $fileContent;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function getFileContent(): string
    {
        if ($this->fileContent) {
            return $this->fileContent;
        }

        $fileContent = file_get_contents($this->filepath);

        if (!$fileContent) {
            throw new \Exception("Unable to read file at {$this->filepath}");
        }

        return $fileContent;
    }

    public function isValid(): bool
    {
        return file_exists($this->getFilepath());
    }
}
