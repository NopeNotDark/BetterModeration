<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\cmdMap\cmds;

use nopenotdark\bettermoderation\discord\DiscordIntegration;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReportCommand extends Command {

    protected array $cooldown = [];

    public function __construct() {
        parent::__construct("report", "Report a player", "/report <player> <reason>");
        $this->setPermission("bettermoderation.report");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(count($args) < 2) {
            $sender->sendMessage("§cUsage: " . $this->getUsage());
            return;
        }

        [$target, $reason] = $args;

        if(isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] > time()) {
            $sender->sendMessage("§cYou are on cooldown for 5 minutes.");
            return;
        }
        $sender->sendMessage("§aSuccessfully reported §c$target §afor§c $reason");
        $this->cooldown[$sender->getName()] = time() + 60 * 5;

        foreach ($sender->getServer()->getOnlinePlayers() as $player) {
            if($player->hasPermission("bettermoderation.report.view")) {
                $player->sendMessage("§c$sender §ahas reported §c$target §afor§c $reason");
            }
        }
        DiscordIntegration::sendToDiscord("$target has been reported for $reason by {$sender->getName()}");
    }

}