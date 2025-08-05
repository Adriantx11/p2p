<?php


$bot = new Bot($config);
$db = new DB($config, $bot);



// Añadir herramienta
if (preg_match('/^\/addtool(?: (.+))?$/', $message, $matches)) {
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }
    if (!isset($matches[1])) {
        $errorText = "Uso incorrecto del comando.\nCorrecto: /addtool [nombre_de_la_herramienta]";
    } else {
        $toolName = $matches[1];
        $result = $db->addTool($toolName);
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

// Eliminar herramienta
if (preg_match('/^\/rmtool(?: (.+))?$/', $message, $matches)) {
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }
    if (!isset($matches[1])) {
        $errorText = "Uso incorrecto del comando.\nCorrecto: /rmtool [nombre_de_la_herramienta]";
    } else {
        $toolName = $matches[1];
        $result = $db->removeTool($toolName);
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

// Consultar estado de la herramienta
if (preg_match('/^\/toolstatus(?: (.+))?$/', $message, $matches)) {
    if (!$db->isAdmin($userId) && !$db->isOwner($userId)) {
        exit;
    }
    if (!isset($matches[1])) {
        $errorText = "Uso incorrecto del comando.\nCorrecto: /toolstatus [nombre_de_la_herramienta]";
    } else {
        $toolName = $matches[1];
        $status = $db->getToolStatus($toolName);

        if ($status !== false) {
            $errorText = "La herramienta '$toolName' está actualmente " . ($status ? 'activa' : 'inactiva') . ".";
        } else {
            $errorText = "Error al consultar el estado de la herramienta '$toolName' o la herramienta no existe.";
        }
    }

    $bot->callApi('SendMessage', [
        'chat_id' => $chat_id,
        'text' => $errorText,
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);
    exit();
}
