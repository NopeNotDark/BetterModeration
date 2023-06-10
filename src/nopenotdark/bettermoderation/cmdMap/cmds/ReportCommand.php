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

namespace nopenotdark\bettermoderation\cmdMap\cmds;

use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\discord\DiscordIntegration;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwnedTrait;

class ReportCommand extends Command {
    use PluginOwnedTrait;

    protected array $cooldown = [];

    public function __construct() {
        parent::__construct("report", "Report a player", "/report <player> <reason>");
        $this->setPermission("bettermoderation.report");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission("bettermoderation.report")) {
            $sender->sendMessage("§cYou do not have permission to use this command.");
            return;
        }

        if (count($args) < 2) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $reason] = $args;

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] > time()) {
            $remainingTime = $this->cooldown[$sender->getName()] - time();
            $remainingMinutes = ceil($remainingTime / 60);
            $sender->sendMessage("§cYou are on cooldown for $remainingMinutes minute(s).");
            return;
        }

        $sender->sendMessage("§aSuccessfully reported §c$target §afor §c$reason");
        $this->cooldown[$sender->getName()] = time() + 300;

        foreach ($sender->getServer()->getOnlinePlayers() as $player) {
            if ($player->hasPermission("bettermoderation.report.view")) {
                $player->sendMessage("§c$sender §ahas reported §c$target §afor §c$reason");
            }
        }

        DiscordIntegration::sendToDiscord("$target has been reported for $reason by {$sender->getName()}");
    }

    public function getOwningPlugin(): BetterModeration {
        return BetterModeration::getInstance();
    }

}