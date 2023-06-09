<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\database;

use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\entry\ModerationEntry;
use nopenotdark\bettermoderation\utils\BanType;

class SqliteDatabase {

    private \SQLite3 $sqliteDB;

    public function __construct(BetterModeration $plugin) {
        $this->sqliteDB = new \SQLite3($plugin->getDataFolder() . "database.db");
        $this->initTable();
    }

    private function initTable(): void {
        $table = <<<SQL
        CREATE TABLE IF NOT EXISTS punishments (
            id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            modType INTEGER NOT NULL,
            target TEXT NOT NULL,
            reason TEXT NOT NULL,
            staff TEXT NOT NULL,
            duration TEXT NOT NULL,
            timeAt INTEGER NOT NULL,
            banned INTEGER NOT NULL
        )
        SQL;
        $this->getSqliteDB()->exec($table);
    }

    public function add(ModerationEntry $entry): void {
        $statement = $this->getSqliteDB()->prepare("INSERT INTO punishments (modType, target, reason, staff, duration, timeAt, banned) VALUES (:modType, :target, :reason, :staff, :duration, :timeAt, :banned)");
        $statement->bindValue(":modType", $entry->getModType());
        $statement->bindValue(":target", $entry->getTarget());
        $statement->bindValue(":reason", $entry->getReason());
        $statement->bindValue(":staff", $entry->getStaff());
        $statement->bindValue(":duration", $entry->getDuration());
        $statement->bindValue(":timeAt", $entry->getTimeAt());
        $statement->bindValue(":banned", $entry->getBanned());
        $statement->execute();
    }

    public function remove(string $target, int $type): void {
        $statement = $this->getSqliteDB()->prepare("UPDATE punishments SET banned = 0 WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();

        if ($type === BanType::MUTE) {
            $statement = $this->getSqliteDB()->prepare("UPDATE punishments SET duration = 0 WHERE target = :target AND modType = :modType");
            $statement->bindValue(":target", $target);
            $statement->bindValue(":modType", $type);
            $statement->execute();
        }
    }

    public function getActivePunishment(string $target, int $type): ?ModerationEntry {
        $statement = $this->getSqliteDB()->prepare("SELECT * FROM punishments WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $result = $statement->execute();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if($row["banned"] == 1) {
                return new ModerationEntry($row["modType"], $row["target"], $row["reason"], $row["staff"], $row["duration"], $row["timeAt"], $row["banned"]);
            }
        }
        return null;
    }

    public function getAll(string $target, int $type): array {
        $statement = $this->getSqliteDB()->prepare("SELECT * FROM punishments WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $result = $statement->execute();
        $data = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getSqliteDB(): \SQLite3 {
        return $this->sqliteDB;
    }

}