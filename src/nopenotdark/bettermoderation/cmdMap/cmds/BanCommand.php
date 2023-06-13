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
use nopenotdark\bettermoderation\entry\ModerationEntry;
use nopenotdark\bettermoderation\utils\BanType;
use nopenotdark\bettermoderation\utils\BMUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwnedTrait;

class BanCommand extends Command {
    use PluginOwnedTrait;

    public function __construct() {
        parent::__construct("ban", "Root Ban Command", "/ban <player> <reason> <time>", ["b"]);
        $this->setPermission("bettermoderation.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission("bettermoderation.ban")) {
            $sender->sendMessage("§cYou do not have permission to use this command.");
            return;
        }

        if (count($args) < 3) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $reason, $duration] = $args;

        if (!is_numeric($duration)) {
            $parsedDuration = BMUtils::parseTime($duration);
            if ($parsedDuration === false) {
                $sender->sendMessage("§cInvalid duration format. Please use a valid duration (e.g., 1d, 2h, 30m).");
                return;
            }
            $duration = $parsedDuration;
        }

        $player = $this->getPlayerByName($target);
        if ($player instanceof Player) {
            $player->kick("§cYou have been banned for $reason, by $sender\n§cDuration: $duration");
        }

        $entry = new ModerationEntry(BanType::BAN, strtolower($target), $reason, strtolower($sender->getName()), $duration, time());
        $database = $this->getOwningPlugin()->getDatabaseHandler();
        if ($database !== null) {
            try {
                $database->add($entry);
            } catch (\Exception $e) {
                $sender->sendMessage("§cAn error occurred while adding the ban entry: " . $e->getMessage());
                return;
            }
        } else {
            $sender->sendMessage("§cDatabase connection not available. Please check your configuration.");
            return;
        }

        $stringDuration = BMUtils::parseString($duration);
        $sender->sendMessage("§aSuccessfully banned §c$target §afor§c $reason, §aduration:§c $stringDuration");
        DiscordIntegration::sendToDiscord("$target has been banned for $reason by {$sender->getName()}, duration: $stringDuration");
    }

    public function getOwningPlugin(): BetterModeration {
        return BetterModeration::getInstance();
    }

    private function getPlayerByName(string $name): ?Player {
        $server = $this->getOwningPlugin()->getServer();
        return $server->getPlayerExact($name) ?? $server->getPlayerByPrefix($name);
    }
}