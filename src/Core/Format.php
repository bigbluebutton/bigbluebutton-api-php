<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Core;

class Format
{
    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    private $type;
    private $url;
    private $processingTime;
    private $length;
    private $size;
    private $images;

    /**
     * Record constructor.
     *
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        $this->rawXml         = $xml;
        $this->type           = $xml->type->__toString();
        $this->url            = $xml->url->__toString();
        $this->processingTime = (int) $xml->processingTime->__toString();
        $this->length         = (int) $xml->length->__toString();
        $this->size           = (int) $xml->size->__toString();
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        if (null === $this->images) {
            $this->images = [];
            foreach ($this->rawXml->preview->images->image as $imageXml) {
                $this->images[] = new Image($imageXml);
            }
        }

        return $this->images;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getProcessingTime(): int
    {
        return $this->processingTime;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
