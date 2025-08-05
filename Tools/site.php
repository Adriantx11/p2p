<?php
$bot = new Bot($config);
$db = new DB($config, $bot);

if (preg_match('/^[\/\.,\$](?:site)(?:\s+(.+))?$/i', $message, $matches)) {
    if ($db->isAdmin($userId) ||$db->isOwner($userId)) {
        // Continúa con el código
    } else {
        // El usuario no es admin ni owner, se detiene el script
        exit;
    }
    //==================[Inicialización de variables]======================//
    $messages = $bot->loadPlantillas();
    
    $lang = $db->getUserLanguage($userId);

    //==================[Manejo de los Usuarios]======================//

    $bolt = $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $bot->translateTemplate($messages['site_loading'], 'en', $lang),
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);

    //==================[Obtención de Datos de Usuario]======================//
    $link = isset($matches[1]) ? trim($matches[1]) : null;
    
    // Verificar y agregar 'https://' si falta
    if ($link && !preg_match('/^https?:\/\//i', $link)) {
        $link = 'https://' . $link;
    }


    // Verificar si se obtuvieron datos de usuario o si el enlace es inválido
    if (!$link) {
        $bot->callApi('editMessageText', [
            'chat_id' => $chat_id,
            'text' => sprintf($bot->translateTemplate($messages['tool_sites'], 'en', $lang), '$site', '$site hola.com', '$site woocomerce.com'),
            'parse_mode' => 'html',
            'message_id' => $bolt
        ]);
        exit();
    }

    //==================[Manejando solicitud]======================//
   
    try {
        $response = CurlX::Get(
            $link,
            [
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'sec-ch-ua-platform: "Windows"',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
            ],
            null,
            null
        );
    
        // Llamada a la función detectCDNAndSecurity
        $formattedJson = AdvancedCDNDetector::analyze($response, $link);
    
    
    } catch (Exception $e) {
        // Manejo de errores
        $formattedJson = json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
        
    }
    
    
    //==================[Envío del Mensaje]======================//
    $template = $bot->translateTemplate($messages['tool_fake'], 'en', $lang);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => "```json
$formattedJson
```",
        'message_id' => $bolt,
        'parse_mode' => 'MarkdownV2',
    ]);
}
