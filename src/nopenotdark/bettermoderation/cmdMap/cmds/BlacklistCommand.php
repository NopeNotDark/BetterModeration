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
use nopenotdark\bettermoderation\entry\ModerationEntry;
use nopenotdark\bettermoderation\utils\BanType;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class BlacklistCommand extends Command {

    public function __construct() {
        parent::__construct("blacklist", "Blacklist a player", "/blacklist <player> <reason>", ["bl"]);
        $this->setPermission("bettermoderation.blacklist");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (count($args) < 2) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $reason] = $args;

        $player = BetterModeration::getInstance()->getServer()->getPlayerByPrefix($target);
        $player?->kick("§cYou have been blacklisted for $reason, by $sender\n§cDuration: Permanent");

        $entry = new ModerationEntry(BanType::BLACKLIST, strtolower($target), $reason, strtolower($sender->getName()), -1, time());
        $plugin = BetterModeration::getInstance();
        $plugin->getDatabase()->add($entry);

        $sender->sendMessage("§aSuccessfully blacklisted §c$target §afor§c $reason");
        DiscordIntegration::sendToDiscord("$target has been blacklisted for $reason by {$sender->getName()}");
    }

}