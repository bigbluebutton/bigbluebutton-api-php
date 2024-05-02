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

abstract class Document implements DocumentInterface
{
    private string $name;
    private ?bool $current      = null;
    private ?bool $downloadable = null;
    private ?bool $removable    = null;
    private bool $validate      = false;

    final public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function setCurrent(?bool $current): self
    {
        $this->current = $current;

        return $this;
    }

    final public function isCurrent(): ?bool
    {
        return $this->current;
    }

    final public function setDownloadable(?bool $downloadable): Document
    {
        $this->downloadable = $downloadable;

        return $this;
    }

    final public function isDownloadable(): ?bool
    {
        return $this->downloadable;
    }

    final public function setRemovable(?bool $removable): Document
    {
        $this->removable = $removable;

        return $this;
    }

    final public function isRemovable(): ?bool
    {
        return $this->removable;
    }

    public function setValidation(bool $validation): Document
    {
        $this->validate = $validation;

        return $this;
    }

    public function getValidation(): bool
    {
        return $this->validate;
    }
}
