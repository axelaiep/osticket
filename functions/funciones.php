<?php

use Firebase\JWT\JWT;

function tokenJWT($encriptar) {
    require_once './dist/php-jwt-master/src/JWT.php';
    // $encriptar = 'hola';
    $time = time(); //fecha y hora actual en segundos
    $key = 'ejemplo';
    $token = array(
        // 'iat' => $time, // tiempo que inició el token
        // 'exp' => $time + (60 * 60), // tiempo que expirará el token (1 hora)
        'password' => $encriptar,
    );

    $jwt = JWT::encode($token, $key); // Codificamos el token
    // $decoded = JWT::decode($jwt, $key, array('HS256')); //Decodifica token
    print_r($jwt);
    print_r($decoded);
    return $jwt;
}


function getRealIP() {

    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }
}

function detecta_ip_restringida($ip){

    $min    = ip2long('10.10.0.1');
    $max    = ip2long('10.10.254.254');
    $needle = ip2long($ip);            
    return (($needle >= $min) AND ($needle <= $max));
}

$browser = new Wolfcast\BrowserDetection();

//Add support for a "custom" Web browser on the fly:
$browser->addCustomBrowserDetection('Vivaldi');

$userAgent       = $browser->getUserAgent();               //string
$browserName     = $browser->getName();                    //string
$browserVer      = $browser->getVersion();                 //string
$platformFamily  = $browser->getPlatform();                //string
$platformVer     = $browser->getPlatformVersion(true);     //string
$platformName    = $browser->getPlatformVersion();         //string
$platformIs64bit = $browser->is64bitPlatform();            //boolean
$isMobile        = $browser->isMobile();                   //boolean
$isRobot         = $browser->isRobot();                    //boolean
$isInIECompat    = $browser->isInIECompatibilityView();    //boolean
$strEmulatedIE   = $browser->getIECompatibilityView();     //string
$arrayEmulatedIE = $browser->getIECompatibilityView(true); //array('browser' => '', 'version' => '')
$isChromeFrame   = $browser->isChromeFrame();              //boolean

//Test if the user uses Microsoft Edge
if ($browser->getName() == Wolfcast\BrowserDetection::BROWSER_EDGE) { echo 'Usted usa Edge!'; }

//Test if the user uses specific versions of Internet Explorer
if ($browser->getName() == Wolfcast\BrowserDetection::BROWSER_IE) {
    //As you can see you can compare major and minor versions under a string format '#.#.#' (no limit in depth)
    if ($browser->compareVersions($browser->getVersion(), '11.0.0.0') < 0) { echo 'You are using IE < 11.'; }
    if ($browser->compareVersions($browser->getVersion(), '11.0.0') == 0) { echo 'You are using IE 11.';}
    if ($browser->compareVersions($browser->getVersion(), '11.0') > 0) { echo 'You are using IE > 11.';}
    if ($browser->compareVersions($browser->getVersion(), '11') >= 0) { echo 'You are using IE 11 or greater.'; }
}

//Test a new user agent and output the instance of BrowserDetection as a string
$browser->setUserAgent('Mozilla/5.0 (Windows NT 6.3; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
//echo $browser;

// echo "<pre>";
// print_r($_POST);
// print_r(compact('userAgent','browserName','browserVer','platformFamily','platformVer','platformName','platformIs64bit','isMobile','isRobot','isInIECompat','strEmulatedIE','arrayEmulatedIE','isChromeFrame'));
// print_r($browser);
// exit;

function file_get_contents_curl( $url ) {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
    $data = curl_exec( $ch );
    curl_close( $ch );
    return $data;
}


function getLocalizacion() {


    if(detecta_ip_restringida(getRealIP()) == false) {
        $dip = getRealIP();
        // $dip = '181.226.244.239';
        // $ips_local = ip_exist($dip);  // Busca en data local si la ip existe

        if (!empty($ips_local)) {
          
            $city = utf8_encode($ips_local[0]);
            $region  = utf8_encode($ips_local[1]);
            $country = utf8_encode($ips_local[2]); 
            $loc = $ips_local[3];
            $name = utf8_encode($ips_local[4]);
            $timezone = $ips_local[5];
            $postal = $ips_local[6];
            $asn = $ips_local[7];
            $type =$ips_local[8];
            $org = utf8_encode($ips_local[9]);
                
        }else{

            /*
            *   Si no encuentra Ip en base de datos local
            *   ocupa la api para conseguir la geolocaliacion de la ip
            */

            //free plan                                        
            $json = file_get_contents("http://ipinfo.io/".$dip);
            $details = (array) json_decode($json);
            //basic plan
            // $json_string = "https://ipinfo.io/".$dip."/json?token=d3ec1474cececb";           
            // $jsondata = file_get_contents_curl($json_string);
            // $details = json_decode($jsondata, true);
            // print_r($details);
            if(array_key_exists("ip",$details)) $ip=$details["ip"];  
            if(array_key_exists("city",$details)) $city=$details["city"];
            if(array_key_exists("region",$details)) $region=$details["region"];
            if(array_key_exists("country",$details)) $country=$details["country"];
            if(array_key_exists("loc",$details)) $loc=$details["loc"];
            if(array_key_exists("org",$details)) $org=$details["org"];
            if(array_key_exists("timezone",$details)) $timezone=$details["timezone"];
            if(array_key_exists("postal",$details)) $postal=$details["postal"];
            if(array_key_exists("asn",$details)) $asn=$details["asn"]["asn"];
            if(array_key_exists("asn",$details)) $name=$details["asn"]["name"];
            if(array_key_exists("asn",$details)) $type=$details["asn"]["type"];

            //echo "Proveedor de internet: ".$name."<br>";

       }

    } else {
        $dip = getRealIP();

        $details = [
            'ip' => $dip,
            'city' => 'AIEP',
            'region' => 'AIEP',
            'country' => 'AIEP',
            'loc' => 'AIEP',
            'org' => 'AIEP',
        ];
        // echo "Ip Privada acceso desde la red AIEP <br>";
        // $city = "AIEP";
        // $region  = "AIEP";
        // $country = "AIEP"; 
        // $loc = "AIEP";
        // $org = "AIEP";
    }

    // print_r($dip);
    return $details;
    // exit;
}