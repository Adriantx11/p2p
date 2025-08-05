<?php

if (preg_match('/^[\/\.,\$](?:key)\s+(\d+)(?:\|(\d+))?$/i', $message, $matches)) {
    // Verificar permisos del usuario
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        // El usuario no tiene permiso
        exit;
    }

    $lang = $db->getUserLanguage($userId);
    $messages = $bot->loadPlantillas();

    // Obtener la duración (días) y el número de claves si existe
    $duration = $matches[1]; // Esto siempre tendrá la fecha (días)
    $keyNumber = isset($matches[2]) ? (int)$matches[2] : 1; // Esto será 1 si no se proporciona un número de clave

    if (empty($duration) || !ctype_digit($duration) || $duration <= 0) {
        // Si no se proporciona una duración válida, enviar mensaje de error
        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "❌ Formato inválido. Usa: /key días[|número]",
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
        exit;
    }

    try {
        // Convertir duración a segundos y calcular la fecha de expiración
        $duration = (int)$duration;
        $expiry = time() + ($duration * 86400);

        // Generar claves únicas
        $keyInfo = $db->generateUniqueKey($expiry, 'Premium', $username, $keyNumber);
        $selectedKey = $keyInfo['selectedKey'];
        // Obtener las claves generadas
        $keys = array_map(fn($key) => $key['key'], $keyInfo['keys']); // Array con todas las claves generadas
        $dias = $keyInfo['dias']; // Días de expiración comunes

        // Crear el texto con las claves generadas, cada clave rodeada por <code>
        $keyList = implode("\n", array_map(fn($key) => "<code>$key</code>", $keys));
        $keyList2 = implode("|", $keys); // Claves separadas por | sin espacios

        // Formatear el mensaje
        $expiry = date("Y-m-d H:i:s", $expiry);
        $messageText = sprintf(
            $bot->translateTemplate($messages['key_created'], 'en', $lang),
            $keyList, // Mostrar todas las claves generadas
            $matches[1], // Días
            $expiry,
            "/claim ". $selectedKey // Añadir links de /claim para todas las claves
        );

        // Preparar el teclado en línea solo si hay una sola clave
        $replyMarkup = null;
        if (count($keys) === 1) {
            $replyMarkup = json_encode([
                'inline_keyboard' => [
                    [['text' => $bot->translateTemplate("Get Claim Easy", 'en', $lang), 'url' => "https://t.me/siestavalidbot?start=".urlencode($keyList2).""]],
                ],
                'resize_keyboard' => true
            ]);
        }

        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => $messageText,
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html',
            'reply_markup' => $replyMarkup
        ]);

    } catch (Exception $e) {
        // Manejo de errores
        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "❌ Error al generar la clave: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
            'reply_to_message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
    }
}
