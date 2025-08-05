<?php

class Fake
{
    public static function GetUser($country)
    {
 
        $get = file_get_contents('https://randomuser.me/api/1.2/?nat=US');
        $data = json_decode($get, true);
        $user = $data["results"][0];
        $providers = array('gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com');
        $provider = $providers[array_rand($providers)];
        $email = strtolower($user["name"]["first"]) . '.' . strtolower($user["name"]["last"]) .random_int(10,99999999). '@' . $provider;
        $get = strtoupper($get);

        preg_match_all("(\"first\":\"(.*)\")siU", $get, $matches1);
        $first = $matches1[1][0];
        preg_match_all("(\"last\":\"(.*)\")siU", $get, $matches1);
        $last = $matches1[1][0];
        preg_match_all("(\"username\":\"(.*)\")siU", $get, $matches1);
        $usrnme = $matches1[1][0];
        preg_match_all("(\"password\":\"(.*)\")siU", $get, $matches1);
        $pass = $matches1[1][0];
        $phone = "+1" . rand(200, 999) . rand(200, 999) . rand(1000, 9999);
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.bestrandoms.com/random-address-in-ca');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIE, 1); 
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'abbr='.$country.'&quantity=1');
        $resposta = curl_exec($ch);
        curl_close($ch);

        preg_match("/<span><b>Street:<\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $street = $matches[1];
        preg_match("/<span><b>City:<\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $city = $matches[1];
        preg_match("/<p><span><b>State\/province\/area: <\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $state = $matches[1];
        preg_match("/<span><b>Zip code:<\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $zip = $matches[1];
        preg_match("/<span><b>Country calling code:<\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $countryCode = $matches[1];
        preg_match("/<span><b>Country:<\/b>&nbsp;&nbsp;(.+?)<\/span>/", $resposta, $matches);
        $country = $matches[1];

        $states = [
            "Alabama" => "AL", "Alaska" => "AK", "Arizona" => "AR", "California" => "CA", 
            "Colorado" => "CO", "Connecticut" => "CT", "Delaware" => "DE", "District Of Columbia" => "DC", 
            "Florida" => "FL", "Georgia" => "GA", "Hawaii" => "HI", "Idaho" => "ID", "Illinois" => "IL", 
            "Indiana" => "IN", "Iowa" => "IA", "Kansas" => "KS", "Kentucky" => "KY", "Louisiana" => "LA", 
            "Maine" => "ME", "Maryland" => "MD", "Massachusetts" => "MA", "Michigan" => "MI", 
            "Minnesota" => "MN", "Mississippi" => "MS", "Missouri" => "MO", "Montana" => "MT", 
            "Nebraska" => "NE", "Nevada" => "NV", "New Hampshire" => "NH", "New Jersey" => "NJ", 
            "New Mexico" => "NM", "New York" => "NY", "North Carolina" => "NC", "North Dakota" => "ND", 
            "Ohio" => "OH", "Oklahoma" => "OK", "Oregon" => "OR", "Pennsylvania" => "PA", "Rhode Island" => "RI", 
            "South Carolina" => "SC", "South Dakota" => "SD", "Tennessee" => "TN", "Texas" => "TX", 
            "Utah" => "UT", "Vermont" => "VT", "Virginia" => "VA", "Washington" => "WA", 
            "West Virginia" => "WV", "Wisconsin" => "WI", "Wyoming" => "WY"
        ];

        $stateAbbr = $states[$state] ?? "KY"; 

        return [
            'first_name' => $first,
            'last_name' => $last,
            'username' => $usrnme,
            'email' => $email,
            'password' => $pass,
            'phone' => ['format2' => $phone],
            'userAgent' => $userAgent,
            'street' => $street,
            'country' => $country,
            'iso2' => $countryCode,
            'state' => $state,
            'state_id' => $stateAbbr,
            'city' => $city,
            'zip' => $zip
        ];
    }
}