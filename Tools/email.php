<?php
$bot = new Bot($config);

$db = new DB($config, $bot);

if (preg_match('/^[\/\.,\$](?:email)(?:\s+(.+))?$/i', $message, $matches)) {

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
    $response = CurlX::Post(
      "https://api.internal.temp-mail.io/api/v3/email/new",
      '{"min_name_length":10,"max_name_length":10}',
      [
          'accept: application/json, text/plain, */*',
          'priority: u=1, i',
          'referer: https://temp-mail.io/',
          'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
      ],
      $cookie,
      $proxies
  );
  
    
     $email=json_decode($response->body,true)["email"];


 $bot->callApi('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"
<b><i><u>SiestaTools</u></i></b> ➳ <code>Email Gen</code>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
<b><i>Email </i></b> ➳ <code>$email</code>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
<b><i><u>No messages.</u></i></b>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
",
'reply_to_message_id' => $message_id,
'parse_mode' => 'html',
'reply_markup' => json_encode([
    'inline_keyboard' => [
        [['text' => "New Messages📥", 'callback_data' => "New_Messages"]],
    ],
])
]);

       
}

if (preg_match('/^New_Messages/', $callback_data)) {

    

  $lang = $db->getUserLanguage($callback_chat_id);
  $messages = $bot->loadLanguage($lang ?: 'es');

  if ($callback_from != $callback_user_id) {
      $bot->callApi('answerCallbackQuery', [
          'callback_query_id' => $callback_id,
          'text' => $messages['message_footer'],
          "show_alert" => true
      ]);
  
    } else {
        $pattern = "/Email  ➳ ([^\n]+)/";
    preg_match($pattern, $callback_message_text , $matches);
    $email = trim($matches[1]);
    
    $response = CurlX::Get(
      'https://api.internal.temp-mail.io/api/v3/email/' . $email . '/messages',
      [
          'accept: application/json, text/plain, */*',
          'priority: u=1, i',
          'referer: https://temp-mail.io/',
          'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
      ],
      $cookie,
      $proxies
  );
    
    // Verificar si hubo algún error en la solicitud CURL
    if (!$response->body) {
        $response = "Error en la solicitud cURL: ";
    } else {
        // Decodificar la respuesta JSON
        $responseData = json_decode($response->body, true);
    
        // Verificar si el array resultante está vacío
        if (empty($responseData)) {
            $response = "No messages..";
        } else {
            // Obtener la posición del último mensaje
            $ultimoNumeroEncontrado = count($responseData) - 1;
    
            // Verificar si se encontró algún número
            if ($ultimoNumeroEncontrado >= 0) {
                $response = $responseData[$ultimoNumeroEncontrado]["body_text"];
            } else {
                $response = "Error: No hay mensajes disponibles.";
            }
        }
    }
    
    $response = trim(strip_tags($response));
    

    $bot->callApi('editMessageText', [
      'chat_id' => $callback_chat_id,
      'text' => " 
<b><i><u>SiestaTools</u></i></b> ➳ <code>Email Gen</code>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
<b><i>Email </i></b> ➳ <code>$email</code>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
<b><i><u>$response</u></i></b>
━━ ━ ━ ━ ━ ━ ━ ━ ━━",
      'message_id'=>$callback_message_id,
      'parse_mode' => 'html',
      'reply_markup' => json_encode([
        'inline_keyboard' => [
            [['text' => "New Messages📥", 'callback_data' => "New_Messages"]],
        ],
        ])
        ]);
    }
  }

