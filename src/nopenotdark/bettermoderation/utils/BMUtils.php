<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
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