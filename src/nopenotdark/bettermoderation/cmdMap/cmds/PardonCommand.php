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
use nopenotdark\bettermoderation\discord\DiscordIntegration;
use nopenotdark\bettermoderation\utils\BanType;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PardonCommand extends Command {

    public function __construct() {
        parent::__construct("pardon", "Pardon a player", "/pardon <player> <type>");
        $this->setPermission("bettermoderation.pardon");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $type] = $args;
        $typeString = strtolower($type);
        $type = match($typeString) {
            "mute" => BanType::MUTE,
            "ban" => BanType::BAN,
            "blacklist" => BanType::BLACKLIST,
            default => null
        };

        $plugin = BetterModeration::getInstance();
        $plugin->getDatabase()->remove($target, $type);
        $sender->sendMessage("§aSuccessfully pardoned §c$target §afor§c $typeString");
        DiscordIntegration::sendToDiscord("$target has been pardoned for $typeString by {$sender->getName()}");
    }

}