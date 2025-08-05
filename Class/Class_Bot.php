<?php

class Bot {
    private $config;
    private $language;

    public function __construct($config, $language = 'es') {
        $this->config = $config;
        $this->language = $language;
    }

    public function mysqlcon() {
        return mysqli_connect(
            $this->config['db']['hostname'],
            $this->config['db']['username'],
            $this->config['db']['password'],
            $this->config['db']['database']
        );
    }
    public function formatCreditCardNumber($cc) {
        // Verificamos si es una tarjeta Amex (15 dígitos)
        if (strlen($cc) == 15) {
            // Dividimos la cadena en segmentos de 4, 6 y 5 caracteres
            $segment1 = substr($cc, 0, 4);
            $segment2 = substr($cc, 4, 6);
            $segment3 = substr($cc, 10, 5);
    
            // Unimos los segmentos con el signo '+'
            $formatted_cc = $segment1 . '+' . $segment2 . '+' . $segment3;
        } else {
            // Para otras tarjetas, dividimos la cadena en segmentos de 4 caracteres
            $segments = str_split($cc, 4);
    
            // Unimos los segmentos con el signo '+'
            $formatted_cc = implode('+', $segments);
        }
    
        return $formatted_cc;
    }
    public function generateRandomName() {
        $names = [
            'Liam', 'Emma', 'Noah', 'Olivia', 'Ava', 'Isabella', 'Sophia', 'Mason', 
            'Mia', 'James', 'Ethan', 'Amelia', 'Lucas', 'Harper', 'Benjamin', 
            'Elijah', 'Emily', 'Michael', 'Charlotte', 'Alexander', 'Daniel', 'Aiden'
        ];
        
        $randomIndex = array_rand($names); // Selecciona un índice aleatorio de la lista
        return $names[$randomIndex];       // Retorna el nombre aleatorio
    }
    public function generateToken($length = 32) {
        $randomBytes = bin2hex(random_bytes($length / 2));
        return 'callback_json' . $randomBytes;
    }
    public function encrypt_data($Data, $fieldKey)
    {
        $i = explode("|", $Data);
        $cc = $i[0];
        $mes = $i[1];
        
        $ano = $i[2];
        $cvv = $i[3];
    
        $ipRan = rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255);
        $fieldToEncrypt = "#$ipRan#$cc#$cvv#$mes#$ano";
        $formattedPublicKey = "-----BEGIN PUBLIC KEY-----\n$fieldKey\n-----END PUBLIC KEY-----";
    
        $base64EncodedData = base64_encode($fieldToEncrypt);
    
        $publicKey = openssl_pkey_get_public($formattedPublicKey);
        if ($publicKey === false) {
            throw new Exception('Invalid public key.');
        }
    
        if (!openssl_public_encrypt($base64EncodedData, $encryptedData, $publicKey)) {
            throw new Exception('Encryption failed: ' . openssl_error_string());
        }
    
        $base64EncryptedData = base64_encode($encryptedData);
    
        openssl_free_key($publicKey);
    
        return $base64EncryptedData;
    }
    public function generateUUID() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

   public function verificarBin($cc) {
        $bin = substr($cc, 0, 6);
        $fim = json_decode(file_get_contents('https://bins.antipublic.cc/bins/' . $bin), true);
        
        if (isset($fim["bin"])) {
            $brand = isset($fim["brand"]) ? $fim["brand"] : "N/A";
            $country = isset($fim["country"]) ? $fim["country"] : "N/A";
            $country_name = isset($fim["country_name"]) ? $fim["country_name"] : "N/A";
            $country_flag = isset($fim["country_flag"]) ? $fim["country_flag"] : "N/A";
            $country_currencies = isset($fim["country_currencies"]) ? $fim["country_currencies"] : "N/A";
            $bank = isset($fim["bank"]) ? $fim["bank"] : "N/A";
            $level = isset($fim["level"]) ? $fim["level"] : "N/A";
            $type = isset($fim["type"]) ? $fim["type"] : "N/A";
            
            // Verifica si el nivel de la tarjeta es 'PREPAID'
            if (strpos($level, 'PREPAID') !== false) {
                return '<i>PREPAID cards are banned.</i>';
            }
    
            // Aquí puedes retornar los datos si deseas continuar con el procesamiento
            return [
                'brand' => $brand,
                'country' => $country,
                'country_name' => $country_name,
                'country_flag' => $country_flag,
                'country_currencies' => $country_currencies,
                'bank' => $bank,
                'level' => $level,
                'type' => $type,
            ];
            
        } else {
            // Retorna el error si no se encontró información para el BIN
            return "<i>❌ Invalid BIN. No information was found for the BIN:</i> <code>$bin</code>";
        }
    }
    function calculatePaginationIndices($page, $items_per_page = 5) {
        $start_index = ($page - 1) * $items_per_page;
        $end_index = $start_index + $items_per_page;
        return [
            'items_per_page' => $items_per_page,
            'start_index' => $start_index,
            'end_index' => $end_index
        ];
    }
    
    public function createPaginationButtons($page, $total_items, $items_per_page, $category,$lang) {
        $buttons = [];
        $total_pages = ceil($total_items / $items_per_page);
        
        // Botón de página anterior, si no estamos en la primera página
        if ($page > 1) {
            $buttons[] = [
                'text' => "⬅️  ".$this->translateText("Página", "es", $lang). "" . ($page - 1),
                'callback_data' => "{$category}_page_" . ($page - 1)
            ];
        }
        
        // Botón de página siguiente, si no estamos en la última página
        if ($page < $total_pages) {
            $buttons[] = [
                'text' => "".$this->translateText("Página", "es", $lang). "" . ($page + 1) . " ➡️",
                'callback_data' => "{$category}_page_" . ($page + 1)
            ];
        }
    
        return $buttons;
    }
    
    
    public function callApi($method, $datas = []) {
        
        $url = "https://api.telegram.org/bot" . $this->config['botToken'] . "/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, value: $url);
        curl_setopt($ch, CURLOPT_HEADER, value: false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, value: 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 0); // Sin límite de tiempo de espera
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); // Sin límite de tiempo de conexión
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            var_dump(curl_error($ch));
        } else {
            return json_decode($res, true)['result']['message_id'] ?? null;
            
        }
    }
    public function formatearMes($mes) {
        // Convertir el mes a un entero
        $mes = intval($mes);
    
        // Si el mes es menor que 10, devolverlo como un solo dígito
        if ($mes < 10) {
            return $mes;
        }
    
        // Si el mes es 10 o mayor, devolverlo como está
        return $mes;
    }
    public function sendMessage($chatId, $message, $keyboard = null, $message_id = null) {
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        if ($keyboard) {
            $data['reply_markup'] = $keyboard;
        }
        
        if (!is_null($message_id)) {
            $data['reply_to_message_id'] = $message_id;
        }
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.$this->config['botToken'].'/SendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION , 1,);
        curl_setopt($ch, CURLOPT_AUTOREFERER    , 1,);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 0,);
        curl_setopt($ch, CURLOPT_TIMEOUT        , 0,);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , 0,);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
    
        if (curl_error($ch)) {
            error_log(curl_error($ch)); 
            return false;
        } else {
            return true;
        }
    }
    public function translateText($texto, $origen, $destino) {
        // Obtener contenido traducido desde Google Translate API
        $trad = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&sl=$origen&tl=$destino&dt=t&q=".urlencode($texto));
        
        // Decodificar el JSON de respuesta
        $trad = json_decode($trad, true);
    
        // Concatenar todas las partes de la traducción
        $traduccion_completa = '';
        foreach ($trad[0] as $parte) {
            $traduccion_completa .= $parte[0];
        }
    
        return $traduccion_completa;
    }
    
    public function translateTemplate($template, $sourceLang = 'en', $targetLang) {
        // Reemplazar marcadores de posición (%s) con tokens temporales
        $placeholderPattern = '/%s/';
        $placeholders = [];
        $i = 0;
        $template = preg_replace_callback($placeholderPattern, function($matches) use (&$placeholders, &$i) {
            $token = "__PLACEHOLDER{$i}__";
            $placeholders[$token] = $matches[0];
            $i++;
            return $token;
        }, $template);
    
        // Reemplazar etiquetas HTML con tokens temporales
        $htmlTags = [];
        $template = preg_replace_callback('/<[^>]+>/', function($matches) use (&$htmlTags) {
            $token = "__HTMLTAG" . count($htmlTags) . "__";
            $htmlTags[$token] = $matches[0];
            return $token;
        }, $template);
    
        // Traducir el template sin marcadores de posición ni etiquetas HTML
        $translatedTemplate = $this->translateText($template, $sourceLang, $targetLang);
    
        // Restaurar las etiquetas HTML
        $translatedTemplate = str_replace(array_keys($htmlTags), array_values($htmlTags), $translatedTemplate);
    
        // Restaurar los marcadores de posición
        $translatedTemplate = str_replace(array_keys($placeholders), array_values($placeholders), $translatedTemplate);
    
        return $translatedTemplate;
    }
    
    public function capture($string, $start, $end) {
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }

    public function multiexplode($delimiters, $string) {
        $one = str_replace($delimiters, $delimiters[0], $string);
        $two = explode($delimiters[0], $one);
        return $two;
    }

    public function array_in_string($str, array $arr) {
        foreach($arr as $arr_value) { 
            if (stripos($str,$arr_value) !== false) return true; 
        }
        return false;
    }

    public function GetStr($string, $start, $end) {
        $str = explode($start, $string);
        $str = explode($end, $str[1]);  
        return $str[0];
    }

    public function cleann($string) {
        $text = preg_replace("/\r|\n/", " ", $string);
        $str1 = preg_replace('/\s+/', ' ', $text);
        $str = preg_replace("/[^0-9]/", " ", $str1);
        $string = trim($str, " ");
        $lista = preg_replace('/\s+/', ' ', $string);
        return $lista;
    }

    public function cleann2($string) {
        $lines = explode("\n", $string);

        foreach ($lines as &$line) {
            $line = preg_replace("/\r|\n/", " ", $line);
            $line = preg_replace('/\s+/', ' ', $line);
            $line = preg_replace("/[^0-9]/", " ", $line);
            $line = trim($line, " ");
            $line .= "\n";
        }

        return $lines;
    }
    public function formatMessage($template, ...$params) {
        return sprintf($template, ...$params);
    }

    public function getRandomFirstName() {
        $firstNames = array('John', 'Jane', 'Alice', 'Bob', 'Eva', 'David');
        return $firstNames[array_rand($firstNames)];
    }

    public function getRandomLastName() {
        $lastNames = array('Doe', 'Smith', 'Johnson', 'Brown', 'Taylor', 'Anderson');
        return $lastNames[array_rand($lastNames)];
    }

    public function value($str, $find_start, $find_end) {
        $start = @strpos($str, $find_start);
        if ($start === false) {
            return "";
        }
        $length = strlen($find_start);
        $end = strpos(substr($str, $start + $length), $find_end);
        return trim(substr($str, $start + $length, $end));
    }

    public function RandomString($length = 4) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $length > $i; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
   
public function sendLanguageSelectionMessage($chat_id, $message_id, $lang) {
    $this->callApi('sendmessage', [
        'chat_id' => $chat_id,
        'text' => "<i>Seleccione su idioma / Select your language:</i>",
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [[
                ['text' => 'Español 🇪🇸', 'callback_data' => ''.$lang.'_es'],
                ['text' => 'English 🇬🇧', 'callback_data' => ''.$lang.'_en']
            ],
            [
                ['text' => 'Français 🇫🇷', 'callback_data' => ''.$lang.'_fr'],
                ['text' => 'Italiano 🇮🇹', 'callback_data' => ''.$lang.'_it']
            ],
            [
                ['text' => 'Deutsch 🇩🇪', 'callback_data' => ''.$lang.'_de'],
                ['text' => 'Português 🇵🇹', 'callback_data' => ''.$lang.'_pt']
            ]],
            'resize_keyboard' => true
            ])
    ]);
}
public function validateCreditCard($lista) {
    // Use regex to match credit card details in the input list
    if (preg_match_all("/(\d{15,16})[\s:|]*?(\d{2})[\s|]*?(\d{2,4})[\s|-]*?(\d{3,4})/", $lista, $matches)) {
        $creditcard = $matches[0][0];
        $cc = preg_replace("/[^0-9]/", "", $this->multiexplode(array(":", "|", "/", " "), $creditcard)[0]);
        $mes = $this->multiexplode(array(":", "|", "/", " "), $creditcard)[1];
        $ano = $this->multiexplode(array(":", "|", "/", " "), $creditcard)[2];
        $cvv = $this->multiexplode(array(":", "|", "/", " "), $creditcard)[3];
        
        $ano = (strlen($ano) == 4) ? $ano : '20' . $ano;


        // Concatenar los datos de la tarjeta en un formato específico
        $creditcard = $cc . '|' . $mes . '|' . $ano . '|' . $cvv;
    
        if (!$this->luhn_verification($cc)) {
            return "<i>Error Algoritmo luhn</i>"; // Return error if Luhn verification fails
        }
        }
        else{
            // Retornar un valor en caso de no encontrar una coincidencia
            return "<i>Error: CreditCard Regex</i>";
        }
        // Check if credit card number (cc) is missing or invalid
        if (empty($cc)) {
            
            return "<i>Error: CreditCard Regex</i>";
        }
    
        // Check if expiration month (mes) is missing or invalid
        if (empty($mes)) {
            return "<i>Error: CreditCard Regex</i>";
        }
    
        // Check if expiration year (ano) is missing or invalid
        if (empty($ano)) {
            return "<i>Error: CreditCard Regex</i>";
        }
    
        // Check if CVV is missing or invalid
        if (empty($cvv)) {
            return "<i>Error: CreditCard Regex</i>";
        }
    
            // Retornar los datos de la tarjeta
        return [
            'creditcard' => $creditcard, // Original credit card string
            'cc' => $cc,                 // Credit card number
            'mes' => $mes,               // Expiration month
            'ano' => $ano,               // Expiration year
            'cvv' => $cvv                // CVV
        ];
}


public function getCleanList($message, $replytomessageis) {
    $listan = $this->cleann($message);
    $listaq = $this->cleann($replytomessageis);
    
    if (empty($listan)) {
        return $listaq;
    } elseif (empty($listaq)) {
        return $listan;
    }
    
    return null; // Return null if both lists are empty
}

// Function to check if the BIN is banned
public function checkBinBanned($cc, $chat_id, $messageidtoedit) {
    $bannedFile = "bin_banned.txt";
    $bin_code = substr($cc, 0, 6);
    
    if (file_exists($bannedFile)) {
        $bannedBins = file($bannedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (in_array($bin_code, $bannedBins)) {
            return "<i>Bin Banned</i>"; // Return error message if BIN is banned
        }
    }
    
    return null; // Return null if BIN is not banned
}
// Luhn verification function to validate credit card numbers
function luhn_verification($num) {
    $num = strval($num);
    $num = array_map('intval', str_split($num));
    $check_digit = array_pop($num);
    $num = array_reverse($num);
    $total = 0;
    foreach ($num as $i => $digit) {
        if ($i % 2 == 0) {
            $digit = $digit * 2;
        }
        if ($digit > 9) {
            $digit = $digit - 9;
        }
        $total += $digit;
    }
    $total = $total * 9;
    return ($total % 10) == $check_digit;
  }
public function handleLanguageChange($callback_data, $callback_id, $callback_from, $callback_user_id, $callback_chat_id, $callback_message_id, $callback_username, $date, $hora, $statusMessage, $db) {
    $lang = str_replace(['lang_', 'langcmd_'], '', $callback_data);
    $messages = $this->loadLanguage($lang ?: 'es');

    if ($callback_from != $callback_user_id) {
        $this->callApi('answerCallbackQuery', [
            'callback_query_id' => $callback_id,
            'text' => $messages['message_footer'],
            'show_alert' => true
        ]);
        return;
    }

    $db->setUserLanguage($callback_user_id, $lang);
    $isExtended = strpos($callback_data, 'langcmd_') === 0;
    $this->sendFormattedMessage($messages, $callback_chat_id, $callback_message_id, $callback_username, $date, $hora, $statusMessage, $isExtended);
}

public function sendFormattedMessage($messages, $callback_chat_id, $callback_message_id, $callback_username, $date = null, $hora = null, $statusMessage, $isExtended = false) {
    if ($isExtended) {
        $welcomeMessage = $this->formatMessage($messages['extended_welcome'], "@".$callback_username);
        $greetingMessage = $messages['cmds_intro'];
    } else {
        $welcomeMessage = $this->formatMessage($messages['welcome'], $callback_username, $date, 'Venezuela, Caracas', $hora);
        $greetingMessage = $this->formatMessage($messages['greeting'], "@".$callback_username);
    }

    $buttons = $isExtended ? $messages['buttons'] : $messages['buttons_start'];

    $this->callApi('editMessageText', [
        'chat_id' => $callback_chat_id,
        'text' => "$welcomeMessage\n━━━━━━━━━━━━━\n$greetingMessage\n━━━━━━━━━━━━━\n$statusMessage",
        'parse_mode' => 'html',
        'message_id' => $callback_message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => $buttons,
            'resize_keyboard' => true
            ])
        ]);
}




    public function loadLanguage($lang = null) {
        $lang = $lang ?: $this->language; 
        $filePath = __DIR__."/../languages/{$lang}.php";
        if (file_exists($filePath)) {
            return include($filePath);
        }
        return include(__DIR__ . "/../languages/en.php"); 
    }
    public function loadPlantillas() {
        return include(__DIR__."/../Plantillas/plantillas.php"); 
    }

   
    public function obtenerMensaje(string $mensajeDia = "☀️ ¡Es hora de disfrutar del sol!", string $mensajeNoche = " ¡Es hora de descansar!"): array {
        date_default_timezone_set($this->config['timeZone'] ?? 'UTC');
    
        $fecha = date('Y-m-d');
        $hora = date('h:i a', time() - 3600*date('I'));
        $hora_num = date('G');
    
        $es_de_dia = ($hora_num >= 6 && $hora_num < 18);
    
        $mensajeBase = "{$hora}. ";
        $mensajeCompleto = $mensajeBase . ($es_de_dia ? $mensajeDia : $mensajeNoche);
    
        return [
            'mensaje' => $mensajeCompleto,
            'fecha' => $fecha,
            'hora' => $hora
        ];
    }
    

    }