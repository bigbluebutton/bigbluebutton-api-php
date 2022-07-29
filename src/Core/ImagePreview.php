<?php

declare(strict_types=1);

/**
 * This file is part of littleredbutton/bigbluebutton-api-php.
 *
 * littleredbutton/bigbluebutton-api-php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * littleredbutton/bigbluebutton-api-php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with littleredbutton/bigbluebutton-api-php. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Core;

class ImagePreview
{
    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var string */
    private $alt;

    /** @var string */
    private $url;

    public function __construct(int $width, int $height, string $alt, string $url)
    {
        $this->width = $width;
        $this->height = $height;
        $this->alt = $alt;
        $this->url = $url;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
