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

namespace nopenotdark\bettermoderation\entry;

class ModerationEntry {

    public function __construct(
        protected int $modType,
        protected string $target,
        protected string $reason,
        protected string $staff,
        protected int $duration,
        protected int $timeAt
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

    public function isActive(): bool {
        if($this->getDuration() == -20){
            return false;
        }
        return $this->getTimeLeft() > 0;
    }

    public function getTimeLeft(): int {
        return $this->getTimeAt() + $this->getDuration() - time();
    }

}