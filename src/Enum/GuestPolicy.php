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

namespace BigBlueButton\Enum;

use MabeEnum\Enum;

// @ref : https://github.com/bigbluebutton/bigbluebutton/blob/5189abb225247290d1954e10827853d5fc022b66/bbb-common-web/src/main/java/org/bigbluebutton/api/domain/GuestPolicy.java
class GuestPolicy extends Enum
{
    public const ALWAYS_ACCEPT      = 'ALWAYS_ACCEPT';
    public const ALWAYS_DENY        = 'ALWAYS_DENY';
    public const ASK_MODERATOR      = 'ASK_MODERATOR';
    public const ALWAYS_ACCEPT_AUTH = 'ALWAYS_ACCEPT_AUTH';
}
