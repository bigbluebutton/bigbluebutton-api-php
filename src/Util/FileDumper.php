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

namespace BigBlueButton\Util;

class FileDumper
{
    private const LINE   = "------------------------------------------------------------------------------------------------------------------------\n";
    private const FOLDER = './var/dump/';

    /**
     * @param mixed $var
     */
    public static function DUMP($var): void
    {
        // Define contents
        $calledFrom = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $headline1  = "{$calledFrom['file']} (Line: {$calledFrom['line']})"; // @phpstan-ignore-line
        $headline2  = "{$calledFrom['class']}::{$calledFrom['function']}()"; // @phpstan-ignore-line
        $dateTime   = (new \DateTime())->format('Y_m_d_\T_H_i_s.u');
        $filename   = "dump_{$dateTime}.txt";

        // Create folder if not exist
        if (!file_exists(self::FOLDER)) {
            mkdir(self::FOLDER, 0755, true);
        }

        // dump content in file
        ob_start();
        echo self::LINE;
        echo "File: {$headline1}\n";
        echo "Func: {$headline2}\n";
        echo "Date: {$dateTime}\n";
        echo self::LINE;
        var_dump($var);
        echo self::LINE;
        file_put_contents(self::FOLDER . $filename, ob_get_contents());
        ob_end_clean();
    }
}
