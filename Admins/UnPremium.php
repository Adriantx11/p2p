<?php

// Verificar si el comando comienza con "/unpremium"
if (preg_match('/^[\/\.,\$](?:unpremium)(?:\s+(.+))?$/i', $message, $matches)) {
    // Verificar si el usuario es admin o owner
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        // El usuario no es admin ni owner, se detiene el script
        exit;
    }

    // Obtener el ID del usuario al que se va a cambiar el status
    $userIdOrName = trim($matches[1]);

    if (empty($userIdOrName)) {
        // Formato incorrecto, enviar mensaje de error
        $bot->callApi('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "Formato incorrecto. Usa /unpremium id",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
        ]);
        exit;
    }

    // Llama a la función para cambiar el status del usuario
    $db->changeUserToFree($userIdOrName);

    // Obtener información del usuario desde la API de Telegram
    $apiUrl = "https://api.telegram.org/bot" . $config['botToken'] . "/getChat?chat_id=" . $userIdOrName;
    $apiResponse = file_get_contents($apiUrl);
    $apiResponse = json_decode($apiResponse, true);

    if (!$apiResponse["ok"]) {
        $bot->callApi('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "No se pudo obtener información del usuario",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
        ]);
        return;
    }

    // Obtener el nombre de usuario del API de respuesta
    $username = $apiResponse["result"]["username"] ?? "Usuario desconocido";

    // Enviar mensaje de éxito
    $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "<i>Status cambiado a Free User para el usuario @$username. Antispam ajustado a 60. ✅</i>",
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'html',
    ]);
}

