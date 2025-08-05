<?php

$bot = new Bot($config);

$db = new DB($config, $bot);

// Lista de emojis de espadas
$swordEmojis = ["⚔️", "🗡️", "🛡️", "🏹", "🤺", "🔪"];
// Lista de emojis de relojes
$clockEmojis = ["⏰", "⌚", "🕰️", "🕛", "🕧", "⌛"];

$cartaEmojis = ["🌆", "🎑", "🏙️", "🏞️", "🎴", "🃏"];


$randomSwordEmoji = $swordEmojis[array_rand($swordEmojis)];
$randomClockEmoji = $clockEmojis[array_rand($clockEmojis)];
$randomcartaEmoji = $cartaEmojis[array_rand($cartaEmojis)];

if (preg_match('/^[\/\.,\$](?:gen|craft)(?:\s+(.+))?$/i', $message, $matches)) {
    $bolt= $bot->callApi('sendMessage', [
        'chat_id' => $chat_id,
        'text' => '
<code>Forging Cards in Progress..⚡</code>',
'parse_mode' => 'html',
                'reply_to_message_id' => $message_id
            ]);
           
(sleep(0.1000));
    if (!isset($matches[1])) {
        $bot->callApi('editMessageText', [
            'chat_id' => $chat_id,
            'text' => '
<b><u>SiestaChk CardForge</u></b> [⚡]
<b>Usage:</b> <code>$craft 418914xxxx</code>
<i>Examples:</i>
<code>$craft 418914xxxx |05/25|xxx</code>
<code>$craft 418914xxxx |mm/yy|rnd</code>',
'parse_mode' => 'html',
                    'message_id' => $bolt
                ]);
                exit();
    } else {

   
        $datos_tarjeta = $matches[1];
        if (preg_match_all("/([0-9x]{6,20})(?:[\/| ](\d{1,2}|xx))?(?:[\/| ](\d{2,4}|xx))?(?:[\/| ](\d{3,4}|xxx))?/", $datos_tarjeta, $matches)) {
            // Extrae el número completo
            $numero = $matches[1][0];
        
            // Si el número tiene exactamente 16 dígitos, corta a 12 dígitos
            if (preg_match("/^\d{16}$/", $numero)) {
                $numero = substr($numero, 0, 12);
            }
        
            // Extrae mes, año, y cvv con condiciones de valor por defecto
            $mes = ($matches[2][0] === 'rnd' || strpos($matches[2][0], 'x') !== false) ? '' : ($matches[2][0] ?? '');
            $anio = ($matches[3][0] === 'rnd' || strpos($matches[3][0], 'x') !== false) ? '' : ($matches[3][0] ?? '');
            $cvv = ($matches[4][0] === 'rnd' || strpos($matches[4][0], 'x') !== false) ? '' : ($matches[4][0] ?? '');
        
            // URL para obtener las tarjetas de crédito
            $url = "https://bytefear.alwaysdata.net/gen.php?numero=$numero&numCards=10&expiryMonth=$mes&expiryYear=$anio&cvv=$cvv";
        
            // Obtener el contenido de la URL
            $response = file_get_contents($url);
            $cardsData = json_decode($response, true);
        
            // Proceso adicional de $cardsData...
        
        
            
            if (empty($cardsData) || $response === FALSE || (strlen($numero) > 16)) {
                $bot->callApi('editMessageText', [
                    'chat_id' => $chat_id,
                    'text' => '
<b><u>SiestaChk CardForge</u></b> [⚡]
<b>Usage:</b> <code>$craft 418914xxxx</code>
<i>Examples:</i>
<code>$craft 418914xxxx |05/25|xxx</code>
<code>$craft 418914xxxx |mm/yy|rnd</code>

',
                    'parse_mode' => 'html',
                    'message_id' => $bolt
                ]);
                exit();
            }

            // Proceso del bin
            $fim = json_decode(file_get_contents('https://bins.antipublic.cc/bins/'.$numero), true);
            $bin = $fim["bin"] ?? null;
            $bin2 = substr($numero,0,6)?? null;

            if ($bin !== null) {
                $brand = $fim["brand"] ?? "Not Found";
                $country = $fim["country"] ?? "Not Found";
                $country_name = $fim["country_name"] ?? "Not Found";
                $country_flag = $fim["country_flag"] ?? "Not Found";
                $country_currencies = $fim["country_currencies"] ?? "Not Found";
                $bank = $fim["bank"] ?? "Not Found";
                $level = $fim["level"] ?? "Not Found";
                $type = $fim["type"] ?? "Not Found";

                // Seleccionar emoji aleatorio para espadas y relojes
                

                // Procesar y enviar los resultados
                $output = "{$randomSwordEmoji} <u><b>SiestaChk CardForge</b></u>\n";
                $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
               
                $output .= "<u><b>$randomcartaEmoji Forge Cards:</b></u>\n";
                
                foreach ($cardsData as $cardInfo) {
                    // Separa el número de tarjeta, mes, año y CVV
                    list($number, $expiryMonth, $expiryYear, $cvv) = explode('|', $cardInfo);
                    $output .= sprintf("<code>%s|%s|%s|%s</code>\n", $number, $expiryMonth, $expiryYear, $cvv);
                }
                
                $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
                $output .= "<b>🪙 <u>BIN :</u></b><code> ".$bin2."xxx </code>\n"; // Obtener el BIN del primer número
                $output .= "<b><u>📎Info Bin:</u></b> <code>$type</code> - <code>$level</code>\n";
                $output .= "-\n";
                $output .= "<b>[ァ] <u>Issuer:</u></b> <i>$bank</i>\n"; // Esto debe ser dinámico si tienes esa información
                $output .= "<b>[ァ]<u> Type:</u></b> <i>CREDIT</i>\n"; // Esto debe ser dinámico si tienes esa información
                $output .= "<b>[ァ]<u> Country:</u></b><i> $country [$country_flag]</i>\n"; // Esto debe ser dinámico si tienes esa información
                $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
                $output .= "<code>Bot By: @ByteFear</code>";

                // Enviar mensaje con las tarjetas generadas
                $bot->callApi('editMessageText', [
                    'chat_id' => $chat_id,
                    'text' => $output,
                    'parse_mode' => 'html',
                    'message_id' => $bolt,
                    'reply_markup' => json_encode([
                    'inline_keyboard' => [
                    [
                     ['text' => "Regen", 'callback_data' => "Regenerador"],
                    ],

                    ]])
                    ]);
                
            } else {
                $bot->callApi('editMessageText', [
                    'chat_id' => $chat_id,
                    'text' => "<i>❌ BIN inválido. No se encontró información para el BIN:</i> <code>$bin2</code>",
                    'parse_mode' => 'html',
                    'message_id' => $bolt
                ]);
            }
        }
    }
}

if (preg_match('/^Regenerador/', $callback_data)) {
    if ($callback_from != $callback_user_id) {
        $bot->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            "show_alert" => true
        ]);
    } else {
    $datos_tarjeta = $callback_message_reply_text;
    if (preg_match_all("/([0-9x]{6,20})(?:[\/| ](\d{1,2}|xx))?(?:[\/| ](\d{2,4}|xx))?(?:[\/| ](\d{3,4}|xxx))?/", $datos_tarjeta, $matches)) {
        // Extrae el número completo
        $numero = $matches[1][0];
    
        // Si el número tiene exactamente 16 dígitos, corta a 12 dígitos
        if (preg_match("/^\d{16}$/", $numero)) {
            $numero = substr($numero, 0, 12);
        }
    
        // Extrae mes, año, y cvv con condiciones de valor por defecto
        $mes = ($matches[2][0] === 'rnd' || strpos($matches[2][0], 'x') !== false) ? '' : ($matches[2][0] ?? '');
        $anio = ($matches[3][0] === 'rnd' || strpos($matches[3][0], 'x') !== false) ? '' : ($matches[3][0] ?? '');
        $cvv = ($matches[4][0] === 'rnd' || strpos($matches[4][0], 'x') !== false) ? '' : ($matches[4][0] ?? '');
    
        // URL para obtener las tarjetas de crédito
        $url = "https://bytefear.alwaysdata.net/gen.php?numero=$numero&numCards=10&expiryMonth=$mes&expiryYear=$anio&cvv=$cvv";
    
        // Obtener el contenido de la URL
        $response = file_get_contents($url);
        $cardsData = json_decode($response, true);
        // Proceso del bin
        $fim = json_decode(file_get_contents('https://bins.antipublic.cc/bins/'.$numero), true);
        $bin = $fim["bin"] ?? null;

        
            $brand = $fim["brand"] ?? "Not Found";
            $country = $fim["country"] ?? "Not Found";
            $country_name = $fim["country_name"] ?? "Not Found";
            $country_flag = $fim["country_flag"] ?? "Not Found";
            $country_currencies = $fim["country_currencies"] ?? "Not Found";
            $bank = $fim["bank"] ?? "Not Found";
            $level = $fim["level"] ?? "Not Found";
            $type = $fim["type"] ?? "Not Found";

            // Seleccionar emoji aleatorio para espadas y relojes
            

            // Procesar y enviar los resultados
            $output = "{$randomSwordEmoji} <u><b>SiestaChk CardForge</b></u>\n";
            $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
           
            $output .= "<u><b>$randomcartaEmoji Forge Cards:</b></u>\n";
            
            foreach ($cardsData as $cardInfo) {
                // Separa el número de tarjeta, mes, año y CVV
                list($number, $expiryMonth, $expiryYear, $cvv) = explode('|', $cardInfo);
                $output .= sprintf("<code>%s|%s|%s|%s</code>\n", $number, $expiryMonth, $expiryYear, $cvv);
            }
            
            $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
            $output .= "<b>🪙 <u>BIN :</u></b><code> $numero </code>\n"; // Obtener el BIN del primer número
            $output .= "<b><u>📎Info Bin:</u></b> <code>$type</code> - <code>$level</code>\n";
            $output .= "-\n";
            $output .= "<b>[ァ] <u>Issuer:</u></b> <i>$bank</i>\n"; // Esto debe ser dinámico si tienes esa información
            $output .= "<b>[ァ]<u> Type:</u></b> <i>CREDIT</i>\n"; // Esto debe ser dinámico si tienes esa información
            $output .= "<b>[ァ]<u> Country:</u></b><i> $country [$country_flag]</i>\n"; // Esto debe ser dinámico si tienes esa información
            $output .= "<b>━━━━━━━━━━━━━━━━━━━━</b>\n";
            $output .= "<b>Bot By: ByteFear</b>";

            // Enviar mensaje con las tarjetas generadas
            $bot->callApi('editMessageText', [
                'chat_id' => $callback_chat_id,
                'text' => $output,
                'parse_mode' => 'html',
                'message_id' => $callback_message_id,
                'reply_markup' => json_encode([
                'inline_keyboard' => [
                [
                 ['text' => "Regen", 'callback_data' => "Regenerador"],
                ],

                ]])
                ]);
            
        
    }
}




}