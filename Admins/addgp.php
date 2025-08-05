 <?php

if (preg_match('/^[\/\.,\$](?:addgp)(?:\s+(.+))?$/i', $message, $matches)) {
    // Verificar si el usuario es admin o owner
    if ($db->isAdmin($userId) || $db->isOwner($userId)) {
        // Obtener partes del comando (dividir por '|')
        $commandParts = explode('|', trim($matches[1]));

        // Verificar si se proporcionaron suficientes parámetros
        if (count($commandParts) >= 2) {
            // Obtener el ID del grupo, el nuevo estado y la fecha de vencimiento opcional
            $groupId = trim($commandParts[0]);
            $newStatus = trim($commandParts[1]);
            $newDate = isset($commandParts[2]) ? trim($commandParts[2]) : '';

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

            // Si el nuevo estado está vacío, informar error
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



if (preg_match('/^[\/\.,\$](?:rmgp)(?:\s+(.+))?$/i', $message, $matches)) {
    // Verificar si el usuario es admin o owner
    if ($db->isAdmin($userId) || $db->isOwner($userId)) {
        // Obtener partes del comando
        $commandParts = explode('|', substr($message, 6));

        // Verificar si se proporcionó el ID del grupo
        if (count($commandParts) >= 1) {
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

            // Llamar a la función para eliminar el grupo
            $result = $db->deleteGroup($groupId, $username);

            // Enviar el resultado al usuario
            $bot->callApi('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "<i>$result</i>",
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'html',
            ]);
        } else {
            // No se proporcionaron suficientes parámetros
            $responseText = "Formato incorrecto. Usa /rmgp id";
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