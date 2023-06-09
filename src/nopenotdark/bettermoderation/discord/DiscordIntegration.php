<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\discord;

use nopenotdark\bettermoderation\BetterModeration;
use pocketmine\Server;

final class DiscordIntegration {


    public static function sendToDiscord(string $content, array $embed = []): void {
        $data = [
            "username" => "BetterModeration",
            "content" => $content
        ];
        if(!empty($embed)){
            $data["embeds"] = $embed;
            unset($data["content"]);
        }else{
            $msg = $data["content"];
            $msg = str_replace("@everyone", "(@)everyone", $msg);
            $msg = str_replace("@here", "(@)here", $msg);
            $data["content"] = $msg;
        }
        $json = json_encode($data);
        $webhook = BetterModeration::getInstance()->getConfig()->get("discord-webhook");
        $post = new SendToDiscordTask($webhook, $json);
        Server::getInstance()->getAsyncPool()->submitTask($post);
    }

}