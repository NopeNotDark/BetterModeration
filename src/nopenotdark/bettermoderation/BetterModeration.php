<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation;

use muqsit\invmenu\InvMenuHandler;
use nopenotdark\bettermoderation\cmdMap\ModerationCommandMap;
use nopenotdark\bettermoderation\database\MySQLDatabase;
use nopenotdark\bettermoderation\database\SqliteDatabase;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class BetterModeration extends PluginBase {
    use SingletonTrait;

    private ModerationCommandMap $moderationCommandMap;
    private SqliteDatabase|MySQLDatabase $database;

    public function onLoad() : void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        InvMenuHandler::register($this);
        $this->getServer()->getPluginManager()->registerEvents(new BetterModerationListener(), $this);

        $this->moderationCommandMap = new ModerationCommandMap($this);

        $this->saveDefaultConfig();
        $databaseType = $this->getConfig()->get("database", "sqlite");
        $this->database = match (strtolower($databaseType)) {
            "mysql" => new MySQLDatabase($this),
            default => new SqliteDatabase($this),
        };
    }

    public function getDatabase(): SqliteDatabase|MySQLDatabase {
        return $this->database;
    }

    public function getModerationCommandMap(): ModerationCommandMap {
        return $this->moderationCommandMap;
    }

}