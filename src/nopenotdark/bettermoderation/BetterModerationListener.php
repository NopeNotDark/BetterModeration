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

namespace nopenotdark\bettermoderation;

use nopenotdark\bettermoderation\entry\ModerationEntry;
use nopenotdark\bettermoderation\utils\BanType;
use nopenotdark\bettermoderation\utils\BMUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\player\Player;

class BetterModerationListener implements Listener {

    public function onPlayerLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        $database = BetterModeration::getInstance()->getDatabaseHandler();

        $this->handlePlayerLogin($player, $name, $database);
        $this->handlePlayerLoginBlacklist($player, $name, $database);
    }

    public function onPlayerChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        $database = BetterModeration::getInstance()->getDatabaseHandler();

        $this->handlePlayerChat($player, $name, $database, $event);
    }

    private function handlePlayerLogin(Player $player, string $name, $database): void {
        $banEntry = $database->getActivePunishment(strtolower($name), BanType::BAN);

        if ($banEntry instanceof ModerationEntry && $banEntry->isActive()) {
            $timeLeft = BMUtils::parseString($banEntry->getTimeLeft());
            $message = "§cYou have been banned for §4{$banEntry->getReason()}§c. You will be unbanned in §4{$timeLeft}§c.";
            $player->kick($message);
        }
    }

    private function handlePlayerLoginBlacklist(Player $player, string $name, $database): void {
        $blackListEntry = $database->getActivePunishment(strtolower($name), BanType::BLACKLIST);
        if ($blackListEntry instanceof ModerationEntry && $blackListEntry->getDuration() === -1) {
            $message = "§cYou have been blacklisted for §c{$blackListEntry->getReason()}.";
            $player->kick($message);
        }
    }

    private function handlePlayerChat(Player $player, string $name, $database, PlayerChatEvent $event): void {
        $entry = $database->getActivePunishment(strtolower($name), BanType::MUTE);

        if ($entry instanceof ModerationEntry && $entry->isActive()) {
            $timeLeft = BMUtils::parseString($entry->getTimeLeft());
            $message = "§cYou have been muted for §4{$entry->getReason()}§c. You will be unmuted in §4{$timeLeft}§c.";
            $player->sendMessage($message);
            $event->cancel();
        }
    }

}