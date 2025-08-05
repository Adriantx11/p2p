<?php

require_once("require.php");


$bot = new Bot($config);

$db = new DB($config, $bot);

$resultado = $bot->obtenerMensaje("☀️","💤");

$hora = $resultado['mensaje'];
$date = $resultado['fecha'];

if (preg_match('/^[\/\.,\$]/', $message)) {
    // Verificar si el usuario no está registrado
    if (!$db->isUserRegistered($userId ?: $callback_user_id)) {
        // Agregar el usuario a la base de datos
        $db->addUser($userId ?: $callback_user_id, $username, $firstname);
    }
}



if (preg_match('/^[^\w\d\s]*start$/', $message)) {
    $userLang = $db->getUserLanguage2($userId);
    $messages = $bot->loadLanguage($userLang ?: 'es');
    $welcomeMsg = sprintf($messages['welcome'], $username, $date, 'Venezuela, Caracas', $hora);
    if (!$userLang) {
     
        $bot->sendLanguageSelectionMessage($chat_id, $message_id, "lang");
        
    } else {
    // Verifica si existe el nombre de usuario
    $greetingMsg = $username ? sprintf($messages['greeting'], "@".$username) : sprintf($messages['greeting'], ''); 

    $buttons = $messages['buttons_start'];

    $bot->callApi('SendMessage', [
        'chat_id' => $chat_id,
        'text' => "$welcomeMsg\n$greetingMsg\n━━━━━━━━━━━━━\n$statusMessage",
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => $buttons,
            'resize_keyboard' => true
        ])
    ]);
}}

if (preg_match('/^[^\w\d\s]*cmds$/', $message)) {
    $userLang = $db->getUserLanguage2($userId);
    $messages = $bot->loadLanguage($userLang ?: 'es');
    if (!$userLang) {
     
        $bot->sendLanguageSelectionMessage($chat_id, $message_id, "langcmd");
    } else {
        $welcomeMessagen = !empty($username) 
        ? sprintf($messages['extended_welcome'], "@".$username) 
        : sprintf($messages['extended_welcome'], "User");
        $greetingMessage = $messages['cmds_intro'];
        $buttons = $messages['buttons'];
        $statusMessage = $messages['status_online'];
        
        $bot->callApi('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "$welcomeMessagen\n━━━━━━━━━━━━━\n$greetingMessage\n━━━━━━━━━━━━━\n$statusMessage",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons,
                'resize_keyboard' => true
            ])
        ]);
    }
}

if (preg_match('/^lang(cmd_)?/', $callback_data)) {
    $bot->handleLanguageChange(
        $callback_data, 
        $callback_id, 
        $callback_from, 
        $callback_user_id, 
        $callback_chat_id, 
        $callback_message_id, 
        $callback_username, 
        $date, 
        $hora, 
        $statusMessage, 
        $db
    );
}

if (preg_match('/^cmd/', $callback_data)) {

    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        $welcomeMessagen = !empty($callback_username) 
        ? sprintf($messages['extended_welcome'], "@".$callback_username) 
        : sprintf($messages['extended_welcome'], "User");
        $greetingMessage = $messages['cmds_intro'];
        $buttons = $messages['buttons'];
        $statusMessage = $messages['status_online'];
        
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => "$welcomeMessagen\n━━━━━━━━━━━━━\n$greetingMessage\n━━━━━━━━━━━━━\n$statusMessage",
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons,
                'resize_keyboard' => true
            ])
        ]);
    }
}

if (preg_match('/^gateways/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        $summary = $db->getGatesSummary();
        $buttons = $messages['buttons_cmds'];
        $welcomeMessagen = sprintf(
            $messages['siesta_welcome_gates'],
            "@".$callback_username,
            $summary['total'],
            $summary['charged'],
            $summary['auth'],
            $summary['ccn'],
            $summary['mass']
        );

        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $welcomeMessagen,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons,
                'resize_keyboard' => true
            ])
        ]);
    }
}





if (preg_match('/^tools/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Construye el mensaje con la cabecera traducida
        $tools_summary = $messages['messages']['vSiesta_tool_header'] . PHP_EOL . '━━━━━━━━━━' . PHP_EOL;

        // Añade cada herramienta de la base de datos con texto de estado traducido
        $tools = $db->getAllToolsStatus();
        foreach ($tools as $tool) {
            $status_label = $tool['status'] === 'active' ? $messages['messages']['status_enable'] : $messages['messages']['status_disable'];
            $tools_summary .= sprintf(
                $messages['messages']['vSiesta_tool_entry'],
                $tool['tool_name'],
                $bot->translateText($tool['comment'],"en",$lang),
                $status_label
            ) . PHP_EOL . '━━━━━━━━━━' . PHP_EOL;
        }

        // Añade el pie de página traducido
        $tools_summary .= $messages['vSiesta_tool_footer'];

        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $tools_summary,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [ [ $messages['button_tap'][0][1] ] ],
                'resize_keyboard' => true
            ])
        ]);
    }
}
if (preg_match('/^auth/', $callback_data) || preg_match('/^auth_page_\d+/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Extrae el número de página del callback_data si existe
        preg_match('/auth_page_(\d+)/', $callback_data, $matches);
        $page = isset($matches[1]) ? (int)$matches[1] : 1;

        // Calcula los índices de paginación
        $pagination = $bot->calculatePaginationIndices($page);
        $items_per_page = $pagination['items_per_page'];
        $start_index = $pagination['start_index'];
        $end_index = $pagination['end_index'];

        // Construye el mensaje con la cabecera traducida
        $gateway_summary = sprintf($messages['messages_gateway']['gateway_footer'] . PHP_EOL . '━━━━━━━━━━' . PHP_EOL,$page);

        // Consulta a la base de datos para obtener solo los gateways de categoría "auth"
        $gateways = $db->getGatewaysByCategory('auth');
        $total_gateways = count($gateways);
        $page_gateways = array_slice($gateways, $start_index, $items_per_page);

        // Agrega gateways de la página actual al mensaje
        foreach ($page_gateways as $gateway) {
            $status_label = $gateway['status'] === 'ON' ? $messages['messages_gateway']['gateway_status_enabled'] : $messages['messages_gateway']['gateway_status_disabled'];
            $gateway_summary .= sprintf(
                $messages['messages_gateway']['gateway_name'] . PHP_EOL .
                $messages['messages_gateway']['gateway_format'] . PHP_EOL .
                $status_label . PHP_EOL .
                $messages['messages_gateway']['gateway_comment'] . PHP_EOL . 
                '━━━━━━━━━━' . PHP_EOL,
                "<b>".$gateway['gatename']."</b>",
                $gateway['gatecmd'],
                $bot->translateText($gateway['comment'], "en", $lang)
            );
        }

        // Genera los botones de paginación para la categoría "auth"
        $buttons = $bot->createPaginationButtons($page, $total_gateways, $items_per_page, 'auth', $lang);

        // Define el botón adicional y ajusta su callback_data
        $button = $messages['button_tap'][0][1];
        $button['callback_data'] = "gateways";

        // Edita el mensaje en el chat con el resumen de gateways de la categoría "auth"
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $gateway_summary,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [$buttons, [$button]],  // Añade $button en una nueva fila
                'resize_keyboard' => true
            ])
        ]);
    }
}
if (preg_match('/^ccn/', $callback_data) || preg_match('/^ccn_page_\d+/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Extrae el número de página del callback_data si existe
        preg_match('/ccn_page_(\d+)/', $callback_data, $matches);
        $page = isset($matches[1]) ? (int)$matches[1] : 1;

        // Calcula los índices de paginación
        $pagination = $bot->calculatePaginationIndices($page);
        $items_per_page = $pagination['items_per_page'];
        $start_index = $pagination['start_index'];
        $end_index = $pagination['end_index'];

        // Construye el mensaje con la cabecera traducida
        $gateway_summary = sprintf($messages['messages_gateway']['gateway_footer'] . PHP_EOL . '━━━━━━━━━━' . PHP_EOL,$page);

        // Consulta a la base de datos para obtener solo los gateways de categoría "auth"
        $gateways = $db->getGatewaysByCategory('ccn');
        $total_gateways = count($gateways);
        $page_gateways = array_slice($gateways, $start_index, $items_per_page);

        // Agrega gateways de la página actual al mensaje
        foreach ($page_gateways as $gateway) {
            $status_label = $gateway['status'] === 'ON' ? $messages['messages_gateway']['gateway_status_enabled'] : $messages['messages_gateway']['gateway_status_disabled'];
            $gateway_summary .= sprintf(
                $messages['messages_gateway']['gateway_name'] . PHP_EOL .
                $messages['messages_gateway']['gateway_format'] . PHP_EOL .
                $status_label . PHP_EOL .
                $messages['messages_gateway']['gateway_comment'] . PHP_EOL . 
                '━━━━━━━━━━' . PHP_EOL,
                "<b>".$gateway['gatename']."</b>",
                $gateway['gatecmd'],
                $bot->translateText($gateway['comment'], "en", $lang)
            );
        }

        // Genera los botones de paginación para la categoría "auth"
        $buttons = $bot->createPaginationButtons($page, $total_gateways, $items_per_page, 'ccn', $lang);

        // Define el botón adicional y ajusta su callback_data
        $button = $messages['button_tap'][0][1];
        $button['callback_data'] = "gateways";

        // Edita el mensaje en el chat con el resumen de gateways de la categoría "auth"
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $gateway_summary,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [$buttons, [$button]],  // Añade $button en una nueva fila
                'resize_keyboard' => true
            ])
        ]);
    }
}
if (preg_match('/^3d/', $callback_data) || preg_match('/^3d_page_\d+/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Extrae el número de página del callback_data si existe
        preg_match('/3d_page_(\d+)/', $callback_data, $matches);
        $page = isset($matches[1]) ? (int)$matches[1] : 1;

        // Calcula los índices de paginación
        $pagination = $bot->calculatePaginationIndices($page);
        $items_per_page = $pagination['items_per_page'];
        $start_index = $pagination['start_index'];
        $end_index = $pagination['end_index'];

        // Construye el mensaje con la cabecera traducida
        $gateway_summary = sprintf($messages['messages_gateway']['gateway_footer'] . PHP_EOL . '━━━━━━━━━━' . PHP_EOL,$page);

        // Consulta a la base de datos para obtener solo los gateways de categoría "auth"
        $gateways = $db->getGatewaysByCategory('3d');
        $total_gateways = count($gateways);
        $page_gateways = array_slice($gateways, $start_index, $items_per_page);

        // Agrega gateways de la página actual al mensaje
        foreach ($page_gateways as $gateway) {
            $status_label = $gateway['status'] === 'ON' ? $messages['messages_gateway']['gateway_status_enabled'] : $messages['messages_gateway']['gateway_status_disabled'];
            $gateway_summary .= sprintf(
                $messages['messages_gateway']['gateway_name'] . PHP_EOL .
                $messages['messages_gateway']['gateway_format'] . PHP_EOL .
                $status_label . PHP_EOL .
                $messages['messages_gateway']['gateway_comment'] . PHP_EOL . 
                '━━━━━━━━━━' . PHP_EOL,
                "<b>".$gateway['gatename']."</b>",
                $gateway['gatecmd'],
                $bot->translateText($gateway['comment'], "en", $lang)
            );
        }

        // Genera los botones de paginación para la categoría "auth"
        $buttons = $bot->createPaginationButtons($page, $total_gateways, $items_per_page, '3d', $lang);

        // Define el botón adicional y ajusta su callback_data
        $button = $messages['button_tap'][0][1];
        $button['callback_data'] = "gateways";

        // Edita el mensaje en el chat con el resumen de gateways de la categoría "auth"
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $gateway_summary,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [$buttons, [$button]],  // Añade $button en una nueva fila
                'resize_keyboard' => true
            ])
        ]);
    }
}



if (preg_match('/^charged/', $callback_data) || preg_match('/^charged_page_\d+/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Extrae el número de página del callback_data si existe
        preg_match('/charged_page_(\d+)/', $callback_data, $matches);
        $page = isset($matches[1]) ? (int)$matches[1] : 1;

        // Configuración de paginación
        $pagination = $bot->calculatePaginationIndices($page);
        $items_per_page = $pagination['items_per_page'];
        $start_index = $pagination['start_index'];
        $end_index = $pagination['end_index'];

        // Construye el mensaje con la cabecera traducida
        $gateway_summary = sprintf($messages['messages_gateway']['gateway_footer'] . PHP_EOL . '━━━━━━━━━━' . PHP_EOL,$page);

        // Consulta a la base de datos para obtener solo los gateways de categoría "charged"
        $gateways = $db->getGatewaysByCategory('charged');
        $total_gateways = count($gateways);
        $page_gateways = array_slice($gateways, $start_index, $items_per_page);

        // Agrega gateways de la página actual al mensaje
        foreach ($page_gateways as $gateway) {
            $status_label = $gateway['status'] === 'ON' ? $messages['messages_gateway']['gateway_status_enabled'] : $messages['messages_gateway']['gateway_status_disabled'];
            $gateway_summary .= sprintf(
                $messages['messages_gateway']['gateway_name'] . PHP_EOL .
                $messages['messages_gateway']['gateway_format'] . PHP_EOL .
                $status_label . PHP_EOL .
                $messages['messages_gateway']['gateway_comment'] . PHP_EOL . 
                '━━━━━━━━━━' . PHP_EOL,
                
                "<b>".$gateway['gatename']."</b>",
                $gateway['gatecmd'],
                $bot->translateText($gateway['comment'], "en", $lang)
            );
        }

        // Genera los botones de paginación para la categoría "auth"
        $buttons = $bot->createPaginationButtons($page, $total_gateways, $items_per_page, 'charged', $lang);

        
        // Define el botón adicional y ajusta su callback_data
        $button = $messages['button_tap'][0][1];
        $button['callback_data'] = "gateways";
        
        // Edita el mensaje en el chat con el resumen de gateways de la categoría "charged"
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $gateway_summary,
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [$buttons, [$button]],  // Añade $button en una nueva fila
                'resize_keyboard' => true
            ])
        ]);
    }
}



if (preg_match('/^cerrar/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
       
        $sayornara=$messages["sayonara"];
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => "<b>$sayornara</b>",
            'parse_mode' => 'html',
            'message_id' => $callback_message_id,
           
        ]);
    }
}
if (preg_match('/^descripcion/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener la información de Siesta desde la base de datos
        $siestaInfo = $db->getSiestaInfo();

        if ($siestaInfo) {
            // Crear la plantilla con los datos
            $descripcion = sprintf(
                $messages["description"],
                $siestaInfo['formatted_team'], // equipo formateado
                $siestaInfo['version_number'], // versión
                $siestaInfo['update_date'] // fecha de actualización
            );

            // Enviar el mensaje con la descripción y los datos
            $bot->callApi('editMessageText', [
                'chat_id' => $callback_chat_id,
                'text' => "$descripcion",
                'parse_mode' => 'html',
                'message_id' => $callback_message_id,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [ [ $messages['button_tap'][0][1] ] ],
                    'resize_keyboard' => true
                ])
            ]);
        } else {
            // Si no hay información en la base de datos, mostrar un mensaje
            $bot->callApi('editMessageText', [
                'chat_id' => $callback_chat_id,
                'text' => "<i><b>No hay información disponible de Siesta en este momento.</b></i>",
                'parse_mode' => 'html',
                'message_id' => $callback_message_id
            ]);
        }
    }
}

if (preg_match('/^siesta/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_from);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener la información de Siesta desde la base de datos
        

            // Enviar el mensaje con la descripción y los datos
            $bot->callApi('editMessageText', [
                'chat_id' => $callback_chat_id,
                'text' => $messages['vSiesta'],
                'parse_mode' => 'html',
                'message_id' => $callback_message_id,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $messages['buttons_vSiesta'],
                    'resize_keyboard' => true
                ])
            ]);
    }
}

if (preg_match('/^lenguaje/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_user_id);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener la información de Siesta desde la base de datos
        

            // Enviar el mensaje con la descripción y los datos
            $bot->callApi('editmessagetext', [
                'chat_id' => $callback_chat_id,
                'text' => $messages['vLenguaje'],
                'message_id' => $callback_message_id,
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $messages["buttons_lag"],
                    'resize_keyboard' => true
                    ])
            ]);
    }
}
if (preg_match('/^Actualizaciones/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_chat_id);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener las actualizaciones recientes desde la base de datos
        $updates = $db->getRecentUpdates(); // Función que obtiene los datos necesarios

        // Generar mensaje con las actualizaciones
        if (empty($updates)) {
            $updateMessage = $messages['updateMessage']['no_updates'];
        } else {
            $updateMessage = "";
          
            foreach ($updates as $update) {
                
                $updateMessage .= sprintf(
                    $messages['updateMessage']['recent_updates'],
                    $update['version_number'],
                    $update['update_date'],
                    $bot->translateText($update['description'],"es",$lang)
                );
            }
        }

        // Enviar el mensaje con la descripción y los datos
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $updateMessage,
            'message_id' => $callback_message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' =>  $messages['button_tap'],
                'resize_keyboard' => true
            ])
        ]);
    }
}
if (preg_match('/^recompensas/', $callback_data)) {
    $lang = $db->getUserLanguage($callback_chat_id);
    $messages = $bot->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener las recompensas recientes desde la base de datos
        $rewards = $db->getRecentRewards(); // Función que obtiene los datos necesarios

        // Generar mensaje con las recompensas
        if (empty($rewards)) {
            $rewardMessage = $messages['rewardMessage']['no_rewards'];
        } else {
            $rewardMessage = "";

            foreach ($rewards as $reward) {
                $rewardMessage .= sprintf(
                    $messages['rewardMessage']['available_rewards'],
                    $reward['title'],
                    $reward['availability_date'],
                    $reward['description']
                );
            }
        }

        // Enviar el mensaje con la descripción y los datos
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $rewardMessage,
            'message_id' => $callback_message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' =>  $messages['button_tap'],
                'resize_keyboard' => true
            ])
        ]);
    }
}
if (preg_match('/^account_info/', $callback_data)) {
    // Obtener el idioma del usuario
    $lang = $db->getUserLanguage($callback_chat_id);
    $messages = $bot->loadLanguage($lang ?: 'es');

    // Verificar si el callback proviene del usuario correcto
    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
        // Obtener la información del usuario desde la base de datos
        $userInfo = $db->obtenerInformacionUsuario($callback_user_id);

        if ($userInfo !== null) {
            // Generar el mensaje con la información del usuario
            $accountMessage = sprintf(
                $messages['vSiesta_user'][0],
                $userInfo['username'],
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
        $bot->callApi('editMessageText', [
            'chat_id' => $callback_chat_id,
            'text' => $accountMessage,
            'message_id' => $callback_message_id,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => $messages['button_tap'],
                'resize_keyboard' => true
            ])
        ]);
    }
}

// Cargar dinámicamente archivos de Tools cuando sea necesario
if (isset($message) && !empty($message)) {
    foreach (glob("Tools/*.php") as $filename) {
        require_once $filename;
    }
}

// Cargar dinámicamente archivos de Gates cuando sea necesario
if (isset($callback_data) && !empty($callback_data)) {
    foreach (glob("Gates/*.php") as $filename) {
        require_once $filename;
    }
}

// Cargar dinámicamente archivos de Admins cuando sea necesario
if (isset($callback_data) && !empty($callback_data)) {
    foreach (glob("Admins/*.php") as $filename) {
        require_once $filename;
    }
}
