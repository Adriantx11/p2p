<?php
$bot = new Bot($config);
$db = new DB($config, $bot);

if (preg_match('/^[\/\.,\$](?:fake)(?:\s+(.+))?$/i', $message, $matches)) {
    //==================[Inicialización de variables]======================//
    $messages = $bot->loadPlantillas();
    $lang = $db->getUserLanguage($userId);

    //==================[Manejo de los Usuarios]======================//
    $accessMessage = $db->verifyUserAccess($userId, $chat_id,'$pf');
    if ($accessMessage !== null) {
        $bot->callApi('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $accessMessage,
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $messages['buttons_gateways'],
                'resize_keyboard' => true
            ])
        ]);
        return;
    }
    $bolt = $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' =>$bot->translateTemplate( $messages['tool_loading'], 'en', $lang),
        'parse_mode' => 'html',
                'reply_to_message_id' => $message_id
            ]);
    //==================[Obtención de Datos de Usuario]======================//
    $countryCode = isset($matches[1]) ? strtoupper(trim($matches[1])) : null;
    $userData = Fake::GetUser($countryCode);
    $country = !empty($userData['country']) ? $userData['country'] : 'N/A';

    // Verificar si se obtuvieron datos de usuario o si el país no existe
    if (!$userData || $country === "N/A") {
        $bot->callApi('editMessageText', [
            'chat_id' => $chat_id,
            'text' =>sprintf($bot->translateTemplate( $messages['tool_fakeUsage'], 'en', $lang),'$fake','$fake us','$fake ca'),
            'parse_mode' => 'html',
            'message_id' => $bolt // Reemplaza $bolt con el ID del mensaje que estás editando si es necesario
        ]);
        exit();
    }

    // Asignación de variables, verificando que existan y no sean 'null'
    $firstName = !empty($userData['first_name']) ? $userData['first_name'] : 'N/A';
    $lastName = !empty($userData['last_name']) ? $userData['last_name'] : 'N/A';
    $username1 = !empty($userData['username']) ? $userData['username'] : 'N/A';
    $email = !empty($userData['email']) ? $userData['email'] : 'N/A';
    $password = !empty($userData['password']) ? $userData['password'] : 'N/A';
    $phone = !empty($userData['phone']['format2']) ? $userData['phone']['format2'] : 'N/A';
    $userAgent = !empty($userData['userAgent']) ? $userData['userAgent'] : 'N/A';
    $street = !empty($userData['street']) ? $userData['street'] : 'N/A';
    
    $iso2 = !empty($userData['iso2']) ? $userData['iso2'] : 'N/A';
    $state = !empty($userData['state']) ? $userData['state'] : 'N/A';
    $state_id = !empty($userData['state_id']) ? $userData['state_id'] : 'N/A';
    $city = !empty($userData['city']) ? $userData['city'] : 'N/A';
    $zip = !empty($userData['zip']) ? $userData['zip'] : 'N/A';

    //==================[Envío del Mensaje]======================//
    $template = $bot->translateTemplate($messages['tool_fake'], 'en', $lang);

    $messageText = sprintf($template,
        $firstName,
        $lastName,
        $street,
        $city,
        $state,
        $zip,
        $phone,
        $country,
        $username1,
        $password
    );

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => $messageText,
        'message_id' => $bolt ,
        'parse_mode' => 'html',
    ]);
}
