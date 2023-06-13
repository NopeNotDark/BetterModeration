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

namespace nopenotdark\bettermoderation\cmdMap\gui;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use nopenotdark\bettermoderation\BetterModeration;
use nopenotdark\bettermoderation\entry\ModerationEntry;
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

        $mute = VanillaBlocks::WOOL()->setColor(DyeColor::ORANGE())->asItem()->setCustomName("§r§cMute(s)");

        $ban = VanillaBlocks::WOOL()->setColor(DyeColor::RED())->asItem()->setCustomName("§r§cBan(s)");

        $blacklist = VanillaBlocks::WOOL()->setColor(DyeColor::BLACK())->asItem()->setCustomName("§r§cBlacklist(s)");

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
        $data = $plugin->getDatabaseHandler()->getAll(strtolower($target), $type);

        $invMenuType = count($data) > 25 ? InvMenuTypeIds::TYPE_DOUBLE_CHEST : InvMenuTypeIds::TYPE_CHEST;
        $invMenu = InvMenu::create($invMenuType);
        $invMenu->setName("§9Bans - $target");
        $invMenu->setListener(InvMenu::readonly());

        /** @var ModerationEntry $entry */
        foreach ($data as $entry) {
            $item = VanillaBlocks::WOOL()->setColor($entry->isActive() ? DyeColor::GREEN() : DyeColor::RED())->asItem();
            $date = date('Y-m-d H:i:s', $entry->getTimeAt());
            $item->setCustomName("§r§e$date");

            $duration = "§cUnknown";
            if ($entry->getDuration() == -20) {
                $duration = "§aUnbanned";
            } elseif ($entry->getDuration() == -1) {
                $duration = "§cPermanent";
            } elseif ($entry->getDuration() > 0) {
                $duration = BMUtils::parseString($entry->getDuration());
            }

            $item->setLore([
                "§r§7----------------------------",
                "§r§eBy:§c " . ucfirst($entry->getStaff()),
                "§r§eAdded on:§c " . ucfirst($date),
                "§r§eReason:§c " . ucfirst($entry->getReason()),
                "§r§eDuration:§c " . $duration,
                "§r§7----------------------------",
                "§r" . ($entry->isActive() ? "§aActive" : "§cExpired")
            ]);

            $invMenu->getInventory()->addItem($item);
        }

        $invMenu->send($player);
    }
}