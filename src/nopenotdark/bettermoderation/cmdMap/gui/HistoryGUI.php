<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\cmdMap\gui;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\utils\BanType;
use nopenotdark\bettermoderation\utils\BMUtils;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class HistoryGUI {

    public static function display(Player $player, string $target): void {
        $invMenu = InvMenu::create(InvMenuTypeIds::TYPE_HOPPER);
        $invMenu->setName("§9Punishments - $target");
        $inventory = $invMenu->getInventory();

        $mute = VanillaBlocks::WOOL()->setColor(DyeColor::ORANGE())->asItem();
        $mute->setCustomName("§r§cMute(s)");

        $ban = VanillaBlocks::WOOL()->setColor(DyeColor::RED())->asItem();
        $ban->setCustomName("§r§cBan(s)");

        $blacklist = VanillaBlocks::WOOL()->setColor(DyeColor::BLACK())->asItem();
        $blacklist->setCustomName("§r§cBlacklist(s)");

        $inventory->setItem(0, $mute);
        $inventory->setItem(2, $ban);
        $inventory->setItem(4, $blacklist);

        $invMenu->setListener(function (InvMenuTransaction $transaction) use ($target): InvMenuTransactionResult {
            switch ($transaction->getItemClicked()->getCustomName()) {
                case "§r§cMute(s)":
                    self::displayPunishment($transaction->getPlayer(), $target, BanType::MUTE);
                    break;
                case "§r§cBan(s)":
                    self::displayPunishment($transaction->getPlayer(), $target, BanType::BAN);
                    break;
                case "§r§cBlacklist(s)":
                    self::displayPunishment($transaction->getPlayer(), $target, BanType::BLACKLIST);
                    break;
            }
            return $transaction->discard();
        });
        $invMenu->send($player);
    }

    public static function displayPunishment(Player $player, string $target, int $type): void {
        $plugin = BetterModeration::getInstance();
        $data = $plugin->getDatabase()->getAll(strtolower($target), $type);

        $invMenuType = count($data) > 25 ? InvMenuTypeIds::TYPE_DOUBLE_CHEST : InvMenuTypeIds::TYPE_CHEST;
        $invMenu = InvMenu::create($invMenuType);
        $invMenu->setName("§9Bans - $target");
        $invMenu->setListener(InvMenu::readonly());

        foreach ($data as $datum) {
            $banned = $datum["banned"];
            $item = VanillaBlocks::WOOL()->setColor($banned ? DyeColor::RED() : DyeColor::LIME())->asItem();
            $date = date('Y-m-d H:i:s', $datum["timeAt"]);
            $item->setCustomName("§r§e$date");

            $status = $banned ? "§aActive" : "§cExpired";

            if($datum["duration"] == -1) {
                $duration = "Permanent";
            } else {
                $duration = BMUtils::parseString($datum["duration"]);
            }

            $item->setLore([
                "§r§7----------------------------",
                "§r§eBy:§c " . ucfirst($datum["staff"]),
                "§r§eAdded on:§c " . ucfirst($date),
                "§r§eReason:§c " . ucfirst($datum["reason"]),
                "§r§eDuration:§c " . $duration,
                "§r§7----------------------------",
                "§r§c$status"
            ]);

            $invMenu->getInventory()->addItem($item);
        }

        $invMenu->send($player);
    }
}
