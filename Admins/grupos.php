<?php
if (preg_match('/^[\/\.,\$](?:grupo)(?:\s+(.+))?$/i', $message, $matches)) {
        if ($db->isAdmin($userId) ||$db->isOwner($userId)) {
            // Continúa con el código
        } else {
            // El usuario no es admin ni owner, se detiene el script
            exit;
        }
$groupInfo = $db->getGroupInfo($chat_id);

// Verificar si está registrado

    $groupName = $groupInfo['name']?: "null";
    $fechaVencimiento = $groupInfo['fecha_vencimiento']?: "null";
    $groupStatus = $groupInfo['status'] ?: "null";

    $bot->callApi('sendmessage', [
        'chat_id' => $chat_id,
'text' => "
<b><i>↳ ID:</i></b> <code> $chat_id</code>
<b><i>↳ Rango: $groupStatus</i></b> 
<b><i>↳ Plan: $fechaVencimiento</i></b>",
'reply_to_message_id' => $message_id,
        'parse_mode' => 'html', // Puedes ajustar esto según tus necesidades
                   
                ]);
  
 
    // Enviar el mensaje al usuario, por ejemplo, a través de la API de Telegram

   }
