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
use PDO;
use PDOException;

class MySQLDatabase {

    private PDO $pdo;

    public function __construct(BetterModeration $plugin) {
        $config = $plugin->getConfig();
        $host = $config->get("host");
        $port = $config->get("port");
        $database = $config->get("name");
        $username = $config->get("username");
        $password = $config->get("password");

        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initTable();
        } catch (PDOException $e) {
            $plugin->getLogger()->error("Connection failed: " . $e->getMessage());
            $plugin->getServer()->getPluginManager()->disablePlugin($plugin);
        }
    }

    private function initTable(): void {
        $table = <<<SQL
        CREATE TABLE IF NOT EXISTS punishments (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            modType INT NOT NULL,
            target TEXT NOT NULL,
            reason TEXT NOT NULL,
            staff TEXT NOT NULL,
            duration TEXT NOT NULL,
            timeAt INT NOT NULL,
            banned INT NOT NULL
        )
        SQL;
        $this->pdo->exec($table);
    }

    public function add(ModerationEntry $entry): void {
        $statement = $this->pdo->prepare("INSERT INTO punishments (modType, target, reason, staff, duration, timeAt, banned) VALUES (:modType, :target, :reason, :staff, :duration, :timeAt, :banned)");
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
        $statement = $this->pdo->prepare("UPDATE punishments SET banned = 0 WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();

        if ($type === BanType::MUTE) {
            $statement = $this->pdo->prepare("UPDATE punishments SET duration = 0 WHERE target = :target AND modType = :modType");
            $statement->bindValue(":target", $target);
            $statement->bindValue(":modType", $type);
            $statement->execute();
        }
    }

    public function getActivePunishment(string $target, int $type): ?ModerationEntry {
        $statement = $this->pdo->prepare("SELECT * FROM punishments WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row && $row["banned"] == 1) {
            return new ModerationEntry($row["modType"], $row["target"], $row["reason"], $row["staff"], $row["duration"], $row["timeAt"], $row["banned"]);
        }
        return null;
    }

    public function getAll(string $target, int $type): array {
        $statement = $this->pdo->prepare("SELECT * FROM punishments WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();
        $data = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }

}