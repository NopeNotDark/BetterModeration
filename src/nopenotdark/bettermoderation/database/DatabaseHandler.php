<?php

namespace nopenotdark\bettermoderation\database;

use nopenotdark\bettermoderation\entry\ModerationEntry;
use poggit\libasynql\DataConnector;

class DatabaseHandler {

    public function __construct(
        protected DataConnector $database
    ) {
    }

    public function add(ModerationEntry $entry): void {
        $this->database->executeInsert("database.add", [
            ":modType" => $entry->getModType(),
            ":target" => $entry->getTarget(),
            ":reason" => $entry->getReason(),
            ":staff" => $entry->getStaff(),
            ":duration" => $entry->getDuration(),
            ":timeAt" => $entry->getTimeAt()
        ]);
    }

    public function remove(string $target, int $type): void {
        $this->database->executeChange("database.remove", [
            ":target" => $target,
            ":modType" => $type
        ]);
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
        $data = [];
        $this->database->executeSelect("database.getAll", [
            ":target" => $target,
            ":modType" => $type
        ], function (array $rows) use (&$data) {
            foreach ($rows as $row) {
                $data[] = new ModerationEntry(
                    $row["modType"],
                    $row["target"],
                    $row["reason"],
                    $row["staff"],
                    $row["duration"],
                    $row["timeAt"]
                );
            }
        });
        return $data;
    }
}