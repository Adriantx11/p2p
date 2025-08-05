<?php
$bot = new Bot($config);
$db = new DB($config, $bot);

if (preg_match('/^[\/\.,\$](?:keywords)(?:\s+(.+))?$/i', $message, $matches)) {
    //==================[Inicialización de variables]======================//
    $messages = $bot->loadPlantillas();
    
    $lang = $db->getUserLanguage($userId);

    //==================[Manejo de los Usuarios]======================//
    $accessMessage = $db->verifyUserAccess1($userId, $chat_id);
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
            'text' => sprintf($bot->translateTemplate($messages['tool_sites'], 'en', $lang), '$keywords', '$keywords hola.com', '$keywords woocomerce.com'),
            'parse_mode' => 'html',
            'message_id' => $bolt
        ]);
        exit();
    }

   //==================[Manejando solicitud]======================//

$response = CurlX::Post(
    "https://thruuu-free-tools-server.herokuapp.com/extract-topics",
    '{"url":"'.$link.'","lang":"es"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://thruuu.com',
        'Referer: https://thruuu.com/',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: cross-site',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"'
    ],
    $cookie,
    $proxies
);

$data = json_decode($response->body, true);

// Verificar si el array de palabras clave está vacío
if (empty($data['kwBody'])) {
    $formattedJson = "_Error al obtener la respuesta\. Verifica la URL o el estado del servidor\._";}
else{
// Extraer solo las palabras clave en un array simple
$keywords = [];
if (isset($data['kwBody'])) {
    foreach ($data['kwBody'] as $keyword) {
        $keywords[] = $keyword['term'];
    }
}

// Limitar el número de palabras clave según tu necesidad, por ejemplo, a 100
$limitedKeywords = array_slice($keywords, 0, 100);

// Convertir a JSON sin formato adicional
$formattedJson = json_encode($limitedKeywords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$formattedJson = "```json\n" . $formattedJson . "\n```"; // Formato para MarkdownV2
}
//==================[Envío del Mensaje]======================//
$template = $bot->translateTemplate($messages['tool_fake'], 'en', $lang);

$bot->callApi('EditMessageText', [
    'chat_id' => $chat_id,
    'text' => $formattedJson,
    'message_id' => $bolt,
    'parse_mode' => 'MarkdownV2' // Usando HTML o sin parse_mode para JSON sin formato
]);
}