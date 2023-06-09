<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */


namespace nopenotdark\bettermoderation\cmdMap\cmds;

use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\cmdMap\gui\HistoryGUI;
use nopenotdark\bettermoderation\utils\BanType;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class HistoryCommand extends Command {

    public function __construct() {
        parent::__construct("history", "Check a player's history", "/history <player>");
        $this->setPermission("bettermoderation.history");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(count($args) < 1) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can only be used in-game");
            return;
        }

        $target = $args[0];
        HistoryGUI::display($sender, $target);
    }

}