<?php

// Verificar si el comando comienza con "/addgp"
if (preg_match('/^[\/\.,\$](?:addgp)(?:\s+(.+))?$/i', $message, $matches)) {
    // Verificar si el usuario es admin o owner
    if ($db->isAdmin($userId) || $db->isOwner($userId)) {
        // Obtener partes del comando
        $commandParts = explode('|', substr($message, 7));

        // Verificar si se proporcionaron suficientes parámetros
        if (count($commandParts) >= 2) {
            // Obtener el ID y el nuevo status, con la fecha de vencimiento opcional
            $groupId = trim($commandParts[0]);

            // Llamada a la API de Telegram para obtener información del grupo
            $url = "https://api.telegram.org/bot" . $config['botToken'] . "/getChat?chat_id=$groupId";
            $response1 = file_get_contents($url);
            $response = json_decode($response1, true);

            if (!$response['ok'] || !isset($response['result']['title'])) {
                // Error al obtener información del grupo
                $bot->callApi('sendMessage', [
                    'chat_id' => $chat_id,
                    'reply_to_message_id' => $message_id,
                    'text' => "No se pudo obtener información del grupo. Asegúrate de proporcionar un ID de grupo válido: $groupId",
                ]);
                exit;
            }

            $groupName = $response['result']['username'] ?? $response['result']['title'];
            $newStatus = trim($commandParts[1]);
            $newDate = isset($commandParts[2]) ? trim($commandParts[2]) : '';

            if (empty($newStatus)) {
                $responseText = "Formato incorrecto. Debes proporcionar al menos el ID y el nuevo status. Usa /addgp id|[nuevo_status]|[nueva_fecha]";
                $bot->callApi('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $responseText,
                    'reply_to_message_id' => $message_id,
                    'parse_mode' => 'html',
                ]);
                exit;
            }

            // Llamar a la función para cambiar el nombre, el status y la fecha de vencimiento del grupo
            $result = $db->changeGroupStatus($groupId, $groupName, $newStatus, $newDate, $username);

            // Enviar el resultado al usuario
            $bot->callApi('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "<i>$result</i>",
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'html',
            ]);
        } else {
            // No se proporcionaron suficientes parámetros
            $responseText = "Formato incorrecto. Usa /addgp id|nuevo_status|nueva_fecha";
            $bot->callApi('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $responseText,
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'html',
            ]);
            exit;
        }
    } else {
        // El usuario no es admin ni owner, se detiene el script
        exit;
    }
}
