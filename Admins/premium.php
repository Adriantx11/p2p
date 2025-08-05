<?php

// Verificar si el comando comienza con "/user"
if (preg_match('/^[\/\.,\$](?:user)(?:\s+(.+))?$/i', $message, $matches)) {
    // Obtener el idioma preferido del usuario
    

    // Verificar si el usuario es admin o owner
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }

    // Obtener el ID del usuario al que se está respondiendo o al que se le va a cambiar el status
    if (isset($replyUserId)) {
        $userIdOrName = $replyUserId;
        $commandParts = explode('|', trim($matches[1]));
        $newStatus = trim($commandParts[0]);
        $newExpirationDays = trim($commandParts[1]);
    } else {
        $commandParts = explode('|', trim($matches[1]));
        if (count($commandParts) === 3) {
            $userIdOrName = trim($commandParts[0]);
            $newStatus = trim($commandParts[1]);
            $newExpirationDays = trim($commandParts[2]);
        } else {
            $bot->callApi('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Formato incorrecto. Usa /user id|nuevo_status|dias",
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'html',
            ]);
            exit;
        }
    }

    // Calcular la nueva fecha de expiración
    $expiryTimestamp = strtotime("+$newExpirationDays days");
    $expiryDate = date('Y-m-d', $expiryTimestamp);

    // Cambiar el estado del usuario
    $db->changeUserStatus($userIdOrName, $newStatus, $username, $newExpirationDays, 20);
    if ("diamante" == $newStatus) {
        $db->changeUserStatus($userIdOrName, $newStatus, $username, $newExpirationDays, 10);
    }

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

    // Traducir las secciones del mensaje
    $lang = $db->getUserLanguage($userIdOrName);
    $welcomeText = $bot->translateText("¡Bienvenido a Siesta Chk!", "es", $lang);
    $detailsText = $bot->translateText("Detalles de Membresía:", "es", $lang);
    $daysAddedText = $bot->translateText("Días Agregados:", "es", $lang);
    $nextBillingText = $bot->translateText("Próxima Facturación:", "es", $lang);
    $statusText = $bot->translateText("Estado:", "es", $lang);
    $benefitsText = $bot->translateText("Beneficios:", "es", $lang);
    $noteText = $bot->translateText("Mantén tu membresía activa 3 meses y desbloquea beneficios exclusivos.", "es", $lang);
    $joinGroupsText = $bot->translateText("Únete a nuestros Grupos Premium:", "es", $lang);

    // Construir el mensaje con las traducciones
    $message = <<<EOT
<b>🎉 $welcomeText</b>

<b>$detailsText</b>
➤ <b>$daysAddedText</b> <code>$newExpirationDays</code>
➤ <b>$nextBillingText</b> <code>$expiryDate</code>
➤ <b>$statusText</b> <code>$newStatus</code>

<b>$benefitsText</b>
<i>$noteText</i>

<b>$joinGroupsText</b>
EOT;

    // Botones de acceso a los grupos premium
    $buttons = [
        [
            ['text' => '📢 Premium Group', 'url' => 'https://t.me/+kG7G_WeZlNk3NDcx'],
        ]
    ];

    // Enviar mensaje con botones
    $bot->callApi('sendMessage', [
        'chat_id' => $userIdOrName,
        'text' => $message,
        'parse_mode' => 'html',
        'reply_markup' => json_encode(['inline_keyboard' => $buttons])
    ]);

    // Confirmación de éxito en el chat de administración
    $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "<i>Status cambiado correctamente para el usuario @$username. Nueva fecha de expiración: $expiryDate. ✅</i>",
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'html',
    ]);
}
