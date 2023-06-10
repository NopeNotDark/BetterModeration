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

namespace nopenotdark\bettermoderation\cmdMap;

use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\cmdMap\cmds\BanCommand;
use nopenotdark\bettermoderation\cmdMap\cmds\BlacklistCommand;
use nopenotdark\bettermoderation\cmdMap\cmds\PardonCommand;
use nopenotdark\bettermoderation\cmdMap\cmds\MuteCommand;
use nopenotdark\bettermoderation\cmdMap\cmds\ReportCommand;
use nopenotdark\bettermoderation\cmdMap\cmds\HistoryCommand;
use pocketmine\command\CommandMap;

class ModerationCommandMap {

    public function __construct(
        protected BetterModeration $plugin
    ) {
        $this->init();
    }

    private function init(): void {
        $cmdMap = $this->getPlugin()->getServer()->getCommandMap();

        $this->unregisterCommands($cmdMap, [
            "ban",
            "ban-ip",
            "pardon",
            "pardon-ip"
        ]);

        $this->registerCommands($cmdMap, [
            "ban" => new BanCommand(),
            "blacklist" => new BlacklistCommand(),
            "pardon" => new PardonCommand(),
            "mute" => new MuteCommand(),
            "report" => new ReportCommand(),
            "history" => new HistoryCommand()
        ]);
    }

    private function unregisterCommands(CommandMap $commandMap, array $commandNames): void {
        foreach ($commandNames as $commandName) {
            $command = $commandMap->getCommand($commandName);
            if ($command !== null) {
                $commandMap->unregister($command);
            }
        }
    }

    private function registerCommands(CommandMap $commandMap, array $commands): void {
        foreach ($commands as $commandName => $command) {
            $commandMap->register($commandName, $command);
        }
    }

    public function getPlugin(): BetterModeration {
        return $this->plugin;
    }
}