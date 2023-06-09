<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\cmdMap;

use nopenotdark\bettermoderation\BetterModeration;

class ModerationCommandMap {

    private BetterModeration $plugin;

    public function __construct(BetterModeration $plugin) {
        $this->plugin = $plugin;

        $this->init();
    }

    private function init(): void {
        $cmdMap = $this->getPlugin()->getServer()->getCommandMap();

        $cmdMap->unregister($cmdMap->getCommand("ban"));
        $cmdMap->unregister($cmdMap->getCommand("ban-ip"));
        $cmdMap->unregister($cmdMap->getCommand("pardon"));
        $cmdMap->unregister($cmdMap->getCommand("pardon-ip"));

        $cmdMap->register("ban", new cmds\BanCommand());
        $cmdMap->register("blacklist", new cmds\BlacklistCommand());
        $cmdMap->register("pardon", new cmds\PardonCommand());
        $cmdMap->register("mute", new cmds\MuteCommand());
        $cmdMap->register("report", new cmds\ReportCommand());
        $cmdMap->register("history", new cmds\HistoryCommand());
    }

    public function getPlugin(): BetterModeration {
        return $this->plugin;
    }

}