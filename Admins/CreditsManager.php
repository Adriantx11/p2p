<?php


// Añadir créditos
if (preg_match('/^\/addcredits (\d+) (\d+)$/', $message, $matches)) {
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }
    if (!isset($matches[1]) || !isset($matches[2])) {
        $errorText = "Uso incorrecto del comando.\nCorrecto: /addcredits [user_id] [cantidad]";
    } else {
        $targetUserId = (int)$matches[1];
        $creditsToAdd = (int)$matches[2];

        $result = $db->addCredits($targetUserId, $creditsToAdd); // Llama a la función addCredits del objeto DB
        $errorText = $result;
    }

    $bot->callApi('SendMessage', [
        'chat_id' => $chat_id,
        'text' => $errorText,
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);
    exit();
}
if (preg_match('/^\/rmcredits (\d+) (\d+)$/', $message, $matches)) {
    // Verificar permisos
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }
    
    // Validar argumentos
    if (!isset($matches[1]) || !isset($matches[2])) {
        $errorText = "Uso incorrecto del comando.\nCorrecto: /rmcredits [user_id] [cantidad]";
    } else {
        $targetUserId = (int)$matches[1];
        $creditsToDeduct = (int)$matches[2];

        // Llama a la función deductCredits del objeto DB
        $result = $db->deductCredits($targetUserId, $creditsToDeduct);
        $errorText = $result;
    }

    // Enviar mensaje con el resultado
    $bot->callApi('SendMessage', [
        'chat_id' => $chat_id,
        'text' => $errorText,
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);
    exit();
}
