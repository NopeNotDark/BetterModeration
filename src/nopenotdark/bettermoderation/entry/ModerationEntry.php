<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\entry;

use nopenotdark\bettermoderation\utils\BMUtils;

class ModerationEntry {

    public function __construct(
        protected int $modType,
        protected string $target,
        protected string $reason,
        protected string $staff,
        protected int $duration,
        protected int $timeAt,
        protected int $banned = 1
    ) {}

    public function getModType(): int {
        return $this->modType;
    }

    public function getTarget(): string {
        return $this->target;
    }

    public function getReason(): string {
        return $this->reason;
    }

    public function getStaff(): string {
        return $this->staff;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getTimeAt(): int {
        return $this->timeAt;
    }

    public function getBanned(): int {
        return $this->banned;
    }

    public function isBanned(): bool {
        return $this->getBanned() === 1;
    }

    public function getTimeLeft(): int {
        return $this->getTimeAt() + $this->getDuration() - time();
    }

}