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
use nopenotdark\bettermoderation\utils\BMUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MuteCommand extends Command {

    public function __construct() {
        parent::__construct("mute", "Mute a player", "/mute <player> <reason> <time>");
        $this->setPermission("bettermoderation.mute");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (count($args) < 3) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $reason, $duration] = $args;

        if (is_string($duration)) {
            $duration = BMUtils::parseTime($duration);
        }

        $entry = new ModerationEntry(BanType::MUTE, strtolower($target), $reason, strtolower($sender->getName()), $duration, time());
        $plugin = BetterModeration::getInstance();
        $plugin->getDatabase()->add($entry);

        $stringDuration = BMUtils::parseString($duration);
        $sender->sendMessage("§aSuccessfully muted §c$target §afor§c $reason, §aduration:§c $stringDuration");
        DiscordIntegration::sendToDiscord("$target has been muted for $reason by {$sender->getName()}, duration: $stringDuration");
    }

}