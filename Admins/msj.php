<?php

if (preg_match('/^[\/\.,\$](?:ref)(?:\s+(.+))?$/i', $message, $matches)) {

    // Obtén el ID del canal al que deseas reenviar el mensaje
   


    if (isset($replyMessageId)) {
        $message_id = $replyMessageId;
        // Reenvía el mensaje original con su ID al canal especificado
        $bot->callApi('forwardMessage', [
            'chat_id' => $config['ChatID'],
            'from_chat_id' => $chat_id, // El ID del chat actual
            'message_id' => $message_id,
        ]);
        $firstName = $firstname ?:'Usuario'; // Nombre del usuario

        // Construye la mención del perfil usando Markdown
        $profileMention = "[{$firstName}](tg://user?id={$userId})";

        // Envía un mensaje confirmando el reenvío e incluye la mención del perfil
        $bot->callApi('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "<b>Gracias por enviar la información. El equipo de moderadores verificará que se trate de una referencia válida antes de reenviarla al canal correspondiente.</b>",
            'parse_mode' => 'html', // Usa Markdown para habilitar menciones
        ]);
        $bot->callApi('sendMessage', [
            'chat_id' => $config['ChatID'],
            'text' => "Perfil del usuario que usó el comando: $profileMention",
            'parse_mode' => 'Markdown', // Usa Markdown para habilitar menciones
        ]);
    }
}
    
