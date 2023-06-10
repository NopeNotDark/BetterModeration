<?php

/**
 * `7MM"""Mq.                 `7MM              mm        db     `7MMF'
 *   MM   `MM.                  MM              MM       ;MM:      MM
 *   MM   ,M9 ,pW"Wq.   ,p6"bo  MM  ,MP.gP"Ya mmMMmm    ,V^MM.     MM
 *   MMmmdM9 6W'   `Wb 6M'  OO  MM ;Y ,M'   Yb  MM     ,M  `MM     MM
 *   MM      8M     M8 8M       MM;Mm 8M""""""  MM     AbmmmqMA    MM
 *   MM      YA.   ,A9 YM.    , MM `MbYM.    ,  MM    A'     VML   MM
 * .JMML.     `Ybmd9'   YMbmd'.JMML. YA`Mbmmd'  `Mbm.AMA.   .AMMA.JMML.
 *
 * This file was generated using PocketAI, Branch Stable, V6.20.1
 *
 * PocketAI is private software: You can redistribute the files under
 * the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this file.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @ai-profile: NopeNotDark
 * @copyright 2023
 * @authors NopeNotDark, SantanasWrld
 * @link https://thedarkproject.net/pocketai
 *
 */

namespace nopenotdark\bettermoderation\utils;

use InvalidArgumentException;

class BMUtils {

    public static function parseTime(string $time): int {
        $time = strtolower($time);
        $multipliers = [
            's' => 1,
            'm' => 60,
            'h' => 3600,
            'd' => 86400,
            'w' => 604800,
            'mo' => 2592000,
            'y' => 31536000
        ];
        preg_match('/(\d+)(\D+)/', $time, $matches);
        $amount = intval($matches[1]);
        $unit = $matches[2];
        if (!isset($multipliers[$unit])) {
            throw new InvalidArgumentException('Invalid time unit');
        }
        return $amount * $multipliers[$unit];
    }

    public static function parseString(int $time): string {
        $units = [
            "y" => 31536000,
            "mo" => 2592000,
            "w" => 604800,
            "d" => 86400,
            "h" => 3600,
            "m" => 60,
        ];

        foreach ($units as $unit => $value) {
            if ($time >= $value) {
                $quantity = floor($time / $value);
                return $quantity . $unit;
            }
        }

        return $time . "s";
    }
}