<?php
$bot = new Bot($config);
$db = new DB($config, $bot);
if (preg_match('/^[\/\.,\$](?:bin)(?:\s+(.+))?$/i', $message, $matches)) {
    $bin = $matches[1];
    $lang = $db->getUserLanguage($userId);

    $messages = $bot->loadPlantillas();
    $reply = $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $bot->translateTemplate($messages['bin_loading'], 'en', $lang),
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);
    sleep(0.25);
    if (empty($bin)) {
        $bot->callApi('EditMessageText', [
            'chat_id' => $chat_id,
            'text' => sprintf($bot->translateTemplate($messages['bin_format'], 'en', $lang), '123456', '418914', '418914'),
            'parse_mode' => 'html',
            'message_id' => $reply
        ]);
        exit();
    }

    $starttime = microtime(true);
    $response = file_get_contents('https://bins.antipublic.cc/bins/' . $bin);
    $fim = json_decode($response, true);

    $bin1 = $fim["bin"] ?? null;

    if ($bin1 !== null) {
        $brand = $fim["brand"] ?? "<code>------</code>";
        $country = $fim["country"] ?? "<code>------</code>";
        $country_name = $fim["country_name"] ?? "<code>------</code>";
        $country_flag = $fim["country_flag"] ?? "<code>------</code>";
        $country_currencies = $fim["country_currencies"] ?? "<code>------</code>";
        $bank = $fim["bank"] ?? "<code>------</code>";
        $level = $fim["level"] ?? "<code>------</code>";
        $type = $fim["type"] ?? "<code>------</code>";

        $elapsedTime = round(microtime(true) - $starttime, 2);

        $bot->callApi('EditMessageText', [
            'chat_id' => $chat_id,
            'text' => sprintf($bot->translateTemplate($messages['Bin'], 'en', $lang),
            $bin1,
                $level,
                $brand,
                $type,
                $bank,
                $country_name,
                $country,
                $country_flag,
                $userId,
                $firstname,
                $Rank,
                $elapsedTime),
            'parse_mode' => 'html',
            'message_id' => $reply
        ]);
    } else {
        // No se encontró información para el BIN
        $bot->callApi('EditMessageText', [
            'chat_id' => $chat_id,
            'text' => sprintf($bot->translateTemplate($messages['bin_format'], 'en', $lang), '123456', '418914', '418914'),
            'parse_mode' => 'html',
            'message_id' => $reply
        ]);
    }
}
?>


