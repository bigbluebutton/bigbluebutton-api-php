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

interface DocumentInterface
{
    public function setName(string $name): Document;

    public function getName(): string;

    public function setCurrent(?bool $current): Document;

    public function isCurrent(): ?bool;

    public function setDownloadable(?bool $downloadable): Document;

    public function isDownloadable(): ?bool;

    public function setRemovable(?bool $removable): Document;

    public function isRemovable(): ?bool;

    public function isValid(): bool;

    public function setValidation(bool $validation): Document;

    public function getValidation(): bool;
}
