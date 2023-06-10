<?php

/**
 * `7MM"""Mq.                 `7MM              mm        db     `7MMF'
 *   MM   `MM.                  MM              MM       ;MM:      MM
 *   MM   ,M9 ,pW"Wq.   ,p6"bo  MM  ,MP.gP"Ya mmMMmm    ,V^MM.     MM
 *   MMmmdM9 6W'   `Wb 6M'  OO  MM ;Y ,M'   Yb  MM     ,M  `MM     MM
 *   MM      8M     M8 8M       MM;Mm 8M""""""  MM     AbmmmqMA    MM
 *   MM      YA.   ,A9 YM.    , MM `MbYM.    ,  MM    A'     VML   MM
 * .JMML.     `Ybmd9'   YMbmd'.JMML. YA`Mbmmd'  `Mbm.AMA.   .AMMA.JMML.
 *
 * This file was generated using PocketAI, Branch Stable, V6.20.1
 *
 * PocketAI is private software: You can redistribute the files under
 * the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this file.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @ai-profile: NopeNotDark
 * @copyright 2023
 * @authors NopeNotDark, SantanasWrld
 * @link https://thedarkproject.net/pocketai
 *
 */

namespace nopenotdark\bettermoderation\database;

use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\entry\ModerationEntry;
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
            timeAt INT NOT NULL
        )
        SQL;
        $this->pdo->exec($table);
    }

    public function add(ModerationEntry $entry): void {
        $statement = $this->pdo->prepare("INSERT INTO punishments (modType, target, reason, staff, duration, timeAt) VALUES (:modType, :target, :reason, :staff, :duration, :timeAt)");
        $statement->bindValue(":modType", $entry->getModType());
        $statement->bindValue(":target", $entry->getTarget());
        $statement->bindValue(":reason", $entry->getReason());
        $statement->bindValue(":staff", $entry->getStaff());
        $statement->bindValue(":duration", $entry->getDuration());
        $statement->bindValue(":timeAt", $entry->getTimeAt());
        $statement->execute();
    }

    public function remove(string $target, int $type): void {
        $statement = $this->pdo->prepare("UPDATE punishments SET duration = duration - 20 WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();
    }

    public function getActivePunishment(string $target, int $type): ?ModerationEntry {
        foreach ($this->getAll($target, $type) as $entry) {
            if ($entry->isActive()) {
                return $entry;
            }
        }
        return null;
    }

    public function getAll(string $target, int $type): ?array {
        $statement = $this->pdo->prepare("SELECT * FROM punishments WHERE target = :target AND modType = :modType");
        $statement->bindValue(":target", $target);
        $statement->bindValue(":modType", $type);
        $statement->execute();
        $data = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = new ModerationEntry($row["modType"], $row["target"], $row["reason"], $row["staff"], $row["duration"], $row["timeAt"]);
        }
        return $data;
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}