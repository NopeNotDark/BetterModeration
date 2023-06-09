<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation;

use nopenotdark\bettermoderation\entry\ModerationEntry;
use nopenotdark\bettermoderation\utils\BanType;
use nopenotdark\bettermoderation\utils\BMUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;

class BetterModerationListener implements Listener {

    public function onPlayerLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        $database = BetterModeration::getInstance()->getDatabase();
        $banEntry = $database->getActivePunishment(strtolower($name), BanType::BAN);

        if ($banEntry instanceof ModerationEntry && $banEntry->getTimeLeft() > 0 && $banEntry->isBanned()) {
            $timeLeft = BMUtils::parseString($banEntry->getTimeLeft());
            $message = "§cYou have been banned for §4{$banEntry->getReason()}§c. You will be unbanned in §4{$timeLeft}§c.";
            $player->kick($message);
        }

        $blackListEntry = $database->getActivePunishment(strtolower($name), BanType::BLACKLIST);
        if ($blackListEntry instanceof ModerationEntry && $blackListEntry->getDuration() == -1) {
            $message = "§cYou have been blacklisted for §c{$blackListEntry->getReason()}.";
            $player->kick($message);
        }
    }

    public function onPlayerChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        $database = BetterModeration::getInstance()->getDatabase();
        $entry = $database->getActivePunishment(strtolower($name), BanType::MUTE);

        if ($entry instanceof ModerationEntry && $entry->getTimeLeft() > 0) {
            $timeLeft = BMUtils::parseString($entry->getTimeLeft());
            $message = "§cYou have been muted for §4{$entry->getReason()}§c. You will be unmuted in §4{$timeLeft}§c.";
            $player->sendMessage($message);
            $event->cancel();
        }
    }


    // TODO: Check bugs and finish this

}