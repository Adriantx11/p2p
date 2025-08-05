<?php

if (preg_match('/^[\/\.,\$](?:me)(?:\s+(.+))?$/i', $message, $matches)) {
    $lang = $db->getUserLanguage($userId);
    $messages = $bot->loadLanguage($lang ?: 'es');
    $userInfo = $db->obtenerInformacionUsuario($userId);

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

    // Enviar el mensaje con la información del usuario
    $bot->callApi('sendmessage', [
        'chat_id' => $chat_id,
        'text' => $accountMessage,
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['button_tap'],
            'resize_keyboard' => true
        ])
    ]);
}