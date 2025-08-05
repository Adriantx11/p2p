<?php
$bot = new Bot($config);
$db = new DB($config, $bot);

// Combinar las condiciones para /start y /claim
if (preg_match('/^(?:\/start\s+)(SiestaValidBot.+)$/i', $message, $matches) || preg_match('/^[\/\.,\$](?:claim)(?:\s+(.+))?$/i', $message, $matches)) {
    // Determinar la clave a reclamar
    $find = $matches[1] ?? null;

    // Verificar que la clave no esté vacía
    if (empty($find)) {
        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "❌ Por favor ingresa una clave para reclamar.",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
        exit;
    }

    // Llamar a la función consulta_claim con los parámetros necesarios
    try {
        $lang = $db->getUserLanguage($userId);
        $messages = $bot->loadPlantillas();
        $consulta_result = $db->consultaClaim($username, $userId, $find);

        // Manejar el resultado y enviar mensajes según sea necesario
        if (is_array($consulta_result)) {
            // Mensaje de éxito con el formato deseado
            $message2 = sprintf($bot->translateTemplate($messages['key_claim'], 'en', $lang), $find, $consulta_result['plan'], $consulta_result['daysRemaining'], $consulta_result['planexpiry'], $username);

            $bot->callApi('sendmessage', [
                'chat_id' => $chat_id,
                'text' => $message2,
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'HTML'
            ]);
        } else {
            // Enviar mensaje de error según el motivo
            if ($consulta_result >= 4) {
                $bot->callApi('sendmessage', [
                    'chat_id' => $chat_id,
                    'text' => "❌ Has alcanzado el límite de intentos fallidos. No puedes usar el comando /claim.",
                    'reply_to_message_id' => $message_id
                ]);
            } else {
                $bot->callApi('sendmessage', [
                    'chat_id' => $chat_id,
                    'text' => "❌ La clave que ingresaste no es válida.",
                    'reply_to_message_id' => $message_id
                ]);
            }
        }
    } catch (Exception $e) {
        // Manejo de errores
        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "❌ Error al reclamar la clave: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
    }
}
?>
