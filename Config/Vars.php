<?php

$update = json_decode(file_get_contents("php://input"));

/**
 * A workaround for the PHP script timeout. 
 * @return Async.
 */
while (ob_get_level()) ob_end_clean();
header('Connection: close');
ignore_user_abort(true);
ob_start();

$size = ob_get_length();
header("Content-Length: $size");
header('Connection: close');
ob_end_flush();
flush();
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

// Safe variable extraction with null checks
$chat_id = $update->message->chat->id ?? null;
$userId = $update->message->from->id ?? null;
$firstname = htmlspecialchars($update->message->from->first_name ?? '', ENT_QUOTES, 'UTF-8');
$lastname = $update->message->from->last_name ?? null;
$username = $update->message->from->username ?? null;
$chattype = $update->message->chat->type ?? null;
$message = $update->message->text ?? null;
$message_id = $update->message->message_id ?? null;
$caption = $update->message->caption ?? null;
$My_ID = "5850715014";

// Variables relacionadas con mensajes de respuesta
$replyUserId = $update->message->reply_to_message->from->id ?? null;
$replyUserFirstname = $update->message->reply_to_message->from->first_name ?? null;
$replyMessageText = $update->message->reply_to_message->text ?? null;
$replyMessageId = $update->message->reply_to_message->message_id ?? null;

// Callback query variables
$callback_user_id = $update->callback_query->message->reply_to_message->from->id ?? null;
$callback_data = $update->callback_query->data ?? null;
$data = $update->callback_query->data ?? null;
$callback_from = $update->callback_query->from->id ?? null;
$callback_fname = htmlspecialchars($update->callback_query->from->first_name ?? '', ENT_QUOTES, 'UTF-8');
$callback_lname = $update->callback_query->from->last_name ?? null;
$callback_username = $update->callback_query->from->username ?? null;
$callback_chat_id = $update->callback_query->message->chat->id ?? null;
$callback_message_id = $update->callback_query->message->message_id ?? null;
$callback_message_text = $update->callback_query->message->text ?? null;
$callback_message_reply_text = $update->callback_query->message->reply_to_message->text ?? null;
$callback_id = $update->callback_query->id ?? null; 