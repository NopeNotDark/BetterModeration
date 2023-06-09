<?php

/**
 * Written by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 *
 * @copyright 2023
 *
 * This file was refactored by PocketAI (A revolutionary AI for PocketMine-MP plugin developing)
 */

namespace nopenotdark\bettermoderation\discord;

use pocketmine\scheduler\AsyncTask;

final class SendToDiscordTask extends AsyncTask {

    public function __construct(
        protected string $webhook,
        protected string $json
    ) {}

    public function onRun(): void {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhook);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->json);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($curlError !== '') {
            var_dump('cURL error: ' . $curlError);
        } else if ($httpCode !== 204) {
            var_dump('Discord API error: ' . $response);
        } else {
            // var_dump('Successfully sent data to Discord.');
        }

        $this->setResult($response);
    }
}