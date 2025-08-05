<?php

if (preg_match('/^[\/\.,\$](?:info)(?:\s+(.+))?$/i', $message, $matches)) {
    $lang = $db->getUserLanguage($userId);
    $messages = $bot->loadLanguage($lang ?: 'es');

    // Verificar si el mensaje es una respuesta a otro mensaje
    if (($matches[1])) {
        $userInfo = $db->obtenerInformacionUsuario(trim($matches[1]));

        if ($userInfo !== null) {
            // Generar el mensaje con la información del usuario
            $accountMessage = sprintf(
                $messages['vSiesta_user'][0],
                ($username ? '@' . $username : $firstname),
                $userInfo['language'],
                $userInfo['antispam'],
                $userInfo['creditos'],
                $userInfo['user_id'],
                $userInfo['status'],
                $userInfo['days_expiration']
            );
        } else {
            $accountMessage = $messages['user_not_found'];
        }
    } else {
        // Mensaje de advertencia si no se responde a otro mensaje
        $accountMessage = "<code>".$bot->translateTemplate("Format:[user_id]", 'en', $lang)."</code>";
    }

    // Enviar el mensaje correspondiente
    $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $accountMessage,
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_start'],
            'resize_keyboard' => true
        ])
    ]);
}
