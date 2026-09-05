<?php

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
$configFile = "config.json";
$waryono = "cookies.txt";

const hitam  = "\033[0;30m";
const merah  = "\033[0;31m";
const hijau  = "\033[0;32m";
const kuning = "\033[0;33m";
const biru   = "\033[0;34m";
const cyan   = "\033[0;36m";
const putih  = "\033[0;37m";
const reset  = "\033[0m";
const bg_hitam  = "\033[40m";
const bg_merah  = "\033[41m";
const bg_hijau  = "\033[42m";
const bg_kuning = "\033[43m";
const bg_biru   = "\033[44m";
const bg_ungu   = "\033[45m";
const bg_cyan   = "\033[46m";
const bg_putih  = "\033[47m";

const script_name = "1xfaucet.com";
const host        = "https://1xfaucet.com";
const in      = "https://api.waryono.my.id/in.php";

function clear() {
    (PHP_OS == "Linux") ? system('clear') : pclose(popen('cls', 'w'));
}

function skibidixxx($url, $method = 'GET', $data = [], $headers = []) {
    while (true) {
        $ch = curl_init();
        $final_headers = [];
        foreach ($headers as $header) {
            $final_headers[] = $header;
        }
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => $final_headers,
            CURLOPT_CONNECTTIMEOUT => 999,
            CURLOPT_TIMEOUT        => 999,
            CURLOPT_COOKIEFILE => 'cookies.txt',
            CURLOPT_COOKIEJAR => 'cookies.txt'
        ];
        if (strtoupper($method) === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if ($response) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $header_size);
            $GLOBALS['last_url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
            return $body;
        } else {
            curl_close($ch);
            echo "\33[1;" . rand(30, 37) . "mwiwok detok";
            sleep(1);
            echo "\r \r";
            return "ngelek";
        }
    }
}

function timer($seconds, $prefix = "[!] please wait") {
    $wait_time = (int)$seconds;
    $frames = ['⣾', '⣽', '⣻', '⢿', '⡿', '⣟', '⣯', '⣷'];
    $frame_count = count($frames);
    $current_frame = 0;
    $frame_delay = 0.1;
    while ($wait_time > 0) {
        $start_time = microtime(true);
        while ((microtime(true) - $start_time) < 1) {
            $hours = floor($wait_time / 3600);
            $minutes = floor(($wait_time % 3600) / 60);
            $seconds_left = $wait_time % 60;
            $time_formatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds_left);
            $spinner = $frames[$current_frame];
            echo putih . $prefix . hijau . " $time_formatted " . putih . $spinner . "\r";
            usleep($frame_delay * 1000000);
            $current_frame = ($current_frame + 1) % $frame_count;
            if ((microtime(true) - $start_time) >= 1) {
                break;
            }
        }
        $wait_time--;
    }
    echo "\r                                     \r";
}

function getConfig($configFile) {
    if (!file_exists($configFile)) {
        echo putih . "API Key   : " . kuning;
        $apikey = trim(fgets(STDIN));
        echo putih . "Email     : " . kuning;
        $email = trim(fgets(STDIN));
        echo putih . "Password  : " . kuning;
        $password = trim(fgets(STDIN));
        $data = [
            "apikey"   => $apikey,
            "email"    => $email,
            "password" => $password
        ];
        file_put_contents($configFile, json_encode($data, JSON_PRETTY_PRINT));
        echo hijau . "Konfigurasi disimpan ke $configFile\n\n" . reset;
        sleep(3);
        return $data;
    }
    return json_decode(file_get_contents($configFile), true);
}

function cloud($apikey, $sitekey) {
    $headers = ["Content-Type: application/json"];
    $body = json_encode([
        "apikey"  => $apikey,
        "methods" => "turnstile",
        "domain"  => host,
        "sitekey" => $sitekey,
        "action"  => "submit",
        "json"    => 1
    ]);
    $request = skibidixxx(in, "POST", $body, $headers);
    if (strpos($request, "ERROR_WRONG_METHOD") !== false)              { echo putih."Error: ".merah."ERROR_WRONG_METHOD\n"; exit; }
    if (strpos($request, "ERROR_KEY_DOES_NOT_EXIST") !== false)        { echo putih."Error: ".merah."ERROR_KEY_DOES_NOT_EXIST\n"; exit; }
    if (strpos($request, "ERROR_METHOD_NOT_SPECIFIED") !== false)      { echo putih."Error: ".merah."ERROR_METHOD_NOT_SPECIFIED\n"; exit; }
    if (strpos($request, "ERROR_NO_SUCH_METHOD") !== false)            { echo putih."Error: ".merah."ERROR_NO_SUCH_METHOD\n"; exit; }
    if (strpos($request, "ERROR_DATABASE_CONNECTION_FAILED") !== false){ echo putih."Error: ".merah."ERROR_DATABASE_CONNECTION_FAILED\n"; exit; }
    if (strpos($request, "ERROR_TOO_MANY_REQUESTS") !== false) {
        echo putih."Error: ".merah."ERROR_TOO_MANY_REQUESTS";
        sleep(1.8); echo "\r                                               \r";
        return "ERROR_TOO_MANY_REQUESTS";
    }
    if (strpos($request, "ERROR_WRONG_USER_KEY") !== false)  { echo putih."Error: ".merah."ERROR_WRONG_USER_KEY\n"; exit; }
    if (strpos($request, "ERROR_ZERO_BALANCE") !== false)    { echo putih."Error: ".merah."ERROR_ZERO_BALANCE\n"; exit; }
    if (strpos($request, "ERROR_BAD_PARAMETERS") !== false)  { echo putih."Error: ".merah."ERROR_BAD_PARAMETERS\n"; exit; }
    if (strpos($request, "ERROR_EMPTY_IMAGE") !== false)     { echo putih."Error: ".merah."ERROR_EMPTY_IMAGE\n"; exit; }
    if (strpos($request, "ERROR_UNKNOWN") !== false)         { echo putih."Error: ".merah."ERROR_UNKNOWN\n"; exit; }

    $json = json_decode($request, true);
    $id   = $json["request"];

    reload:
    timer(3, "  cf");
    $url    = "https://api.waryono.my.id/res.php?apikey=".$apikey."&action=get&id=".$id."&json=1";
    $result = skibidixxx($url, "GET", []);

    if (strpos($result, "ERROR_BAD_PARAMETERS") !== false)        { echo putih."Error: ".merah."ERROR_BAD_PARAMETERS\n"; exit; }
    if (strpos($result, "Database connection failed") !== false)   { echo putih."Error: ".merah."Database connection failed\n"; exit; }
    if (strpos($result, "WRONG_CAPTCHA_ID") !== false) {
        echo putih."Error: ".merah."WRONG_CAPTCHA_ID";
        sleep(1.8); echo "\r                                               \r";
        return "WRONG_CAPTCHA_ID";
    }
    if (strpos($result, "ERROR_SOLVE_PENDING") !== false) {
        echo putih."Error: ".merah."ERROR_SOLVE_PENDING";
        sleep(1.8); echo "\r                                               \r";
        return "ERROR_SOLVE_PENDING";
    }
    if (strpos($result, "CAPCHA_NOT_READY") !== false) {
        echo putih."Error: ".merah."CAPCHA_NOT_READY";
        sleep(1.8); echo "\r                                               \r";
        goto reload;
    }
    if (strpos($result, "ERROR_CAPTCHA_UNSOLVABLE") !== false) {
        echo putih."Error: ".merah."ERROR_CAPTCHA_UNSOLVABLE";
        sleep(1.8); echo "\r                                               \r";
        return "ERROR_CAPTCHA_UNSOLVABLE";
    }
    if (strpos($result, "ERROR_BAD_REQUEST") !== false)    { echo "Error: ".merah."ERROR_BAD_REQUEST\n"; exit; }
    if (strpos($result, "INTENAL_SERVER_ERROR") !== false) {
        echo "Errro: ".merah."INTENAL_SERVER_ERROR";
        sleep(1.8); echo "\r                                               \r";
        return "INTENAL_SERVER_ERROR";
    }

    $json = json_decode($result, true);
    $res  = $json["request"];
    return ["turnstile" => $res];
}

function antibot($apikey,$main,
	$id1,$id2,$id3,
        $img1,$img2,$img3
) {
    $headers = ["Content-Type: application/json"];
    $body    = json_encode([
        "apikey"  => $apikey,
        "methods" => "antibot",
        "main"    => $main,
        $id1      => $img1,
        $id2      => $img2,
        $id3      => $img3,
	    "json" => 1
    ]);
    $request = skibidixxx(in, "POST", $body, $headers);
    if (strpos($request, "ERROR_WRONG_METHOD") !== false) { echo putih."Error: ".merah."ERROR_WRONG_METHOD\n"; exit; }
    if (strpos($request, "ERROR_KEY_DOES_NOT_EXIST") !== false) { echo putih."Error: ".merah."ERROR_KEY_DOES_NOT_EXIST\n"; exit; }
    if (strpos($request, "ERROR_METHOD_NOT_SPECIFIED") !== false) { echo putih."Error: ".merah."ERROR_METHOD_NOT_SPECIFIED\n"; exit; }
    if (strpos($request, "ERROR_NO_SUCH_METHOD") !== false) { echo putih."Error: ".merah."ERROR_NO_SUCH_METHOD\n"; exit; }
    if (strpos($request, "ERROR_DATABASE_CONNECTION_FAILED") !== false) { echo putih."Error: ".merah."ERROR_DATABASE_CONNECTION_FAILED\n"; exit; }
    if (strpos($request, "ERROR_TOO_MANY_REQUESTS") !== false) { echo putih."Error: ".merah."ERROR_TOO_MANY_REQUESTS"; sleep(1.8); echo "\r                                               \r"; return "ERROR_TOO_MANY_REQUESTS"; }
    if (strpos($request, "ERROR_WRONG_USER_KEY") !== false) { echo putih."Error: ".merah."ERROR_WRONG_USER_KEY\n"; exit; }
    if (strpos($request, "ERROR_ZERO_BALANCE") !== false) { echo putih."Error: ".merah."ERROR_ZERO_BALANCE\n"; exit; }
    if (strpos($request, "ERROR_BAD_PARAMETERS") !== false) { echo putih."Error: ".merah."ERROR_BAD_PARAMETERS\n"; exit; }
    if (strpos($request, "ERROR_EMPTY_IMAGE") !== false) { echo putih."Error: ".merah."ERROR_EMPTY_IMAGE\n"; exit; }
    if (strpos($request, "ERROR_UNKNOWN") !== false) { echo putih."Error: ".merah."ERROR_UNKNOWN\n"; exit; }
    $json = json_decode($request, true);
    echo $id = $json["request"];
    reload:
    timer(2," anti.");
    $url = "https://api.waryono.my.id/res.php?apikey=".$apikey."&action=get&id=".$id."&json=1";
    $result = skibidixxx($url, "GET", []);
    if (strpos($result, "ERROR_BAD_PARAMETERS") !== false) { echo putih."Error: ".merah."ERROR_BAD_PARAMETERS\n"; exit; }
    if (strpos($result, "Database connection failed") !== false) { echo putih."Error: ".merah."Database connection failed\n"; exit; }
    if (strpos($result, "WRONG_CAPTCHA_ID") !== false) { echo putih."Error: ".merah."WRONG_CAPTCHA_ID"; sleep(1.8); echo "\r                                               \r"; return "WRONG_CAPTCHA_ID"; }
    if (strpos($result, "ERROR_SOLVE_PENDING") !== false) { echo putih."Error: ".merah."ERROR_SOLVE_PENDING"; sleep(1.8); echo "\r                                               \r"; return "ERROR_SOLVE_PENDING"; }
    if (strpos($result, "CAPCHA_NOT_READY") !== false) { echo putih."Error: ".merah."CAPCHA_NOT_READY"; sleep(1.8); echo "\r                                               \r"; goto reload; }
    if (strpos($result, "ERROR_CAPTCHA_UNSOLVABLE") !== false) { echo putih."Error: ".merah."ERROR_CAPTCHA_UNSOLVABLE"; sleep(1.8); echo "\r                                               \r"; return "ERROR_CAPTCHA_UNSOLVABLE"; }
    if (strpos($result, "ERROR_BAD_REQUEST") !== false) { echo "Error: ".merah."ERROR_BAD_REQUEST\n"; exit; }
    if (strpos($result, "INTENAL_SERVER_ERROR") !== false) { echo "Errro: ".merah."INTENAL_SERVER_ERROR"; sleep(1.8); echo "\r                                               \r"; return "INTENAL_SERVER_ERROR"; }
    $json = json_decode($result, true);
    $res = $json["request"];
    return ["anti" => $res];
}

function p(&$a,&$b){
$a = [
"host: 1xfaucet.com",
"user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36",
"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,q=0.8,application/signed-exchange;v=b3;q=0.7",
"referer: https://1xfaucet.com/",
"cookie: faucet_ad_click=1"
];
$b = [
"host: 1xfaucet.com",
"content-type: application/x-www-form-urlencoded",
"user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36",
"origin: https://1xfaucet.com",
"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,q=0.8,application/signed-exchange;v=b3;q=0.7",
"referer: https://1xfaucet.com/",
"cookie: faucet_ad_click=1"
];

}

home:
clear();
$config   = getConfig($configFile);
$apikey   = $config['apikey'];
$email    = $config['email'];
$password = $config['password'];

clear();

p($a,$b);
$dash = skibidixxx(host."/dashboard", "GET", [], $a);
if (strpos($dash, "Logout") !== false) {
	preg_match('/id="headerBalanceTokens"[^>]*>\s*([^<]+)\s*<\/strong>/i', $dash, $tokens_match);
	$balance_tokens = trim($tokens_match[1] ?? '0 tokens');
	preg_match('/id="headerBalanceConverted"[^>]*>\s*([^<]+)\s*<\/span>/i', $dash, $converted_match);
	$balance_converted = trim($converted_match[1] ?? '≈ 0 BTC');
	echo putih . "Balance   " . hijau . $balance_tokens . "\n";
	echo putih . "Converted " . hijau . $balance_converted . "\n\n";

asu:
$faucet = skibidixxx(host."/faucet", "GET", [], $a);
if (strpos($faucet, 'var wait') !== false) {
    preg_match('/var\s+wait\s*=\s*(\d+)/i', $faucet, $wait_match);
    $wait_sec = isset($wait_match[1]) ? (int)$wait_match[1] : 0;
    timer($wait_sec, " wait..");
    goto asu;
}

preg_match('/(\d+)\/(\d+)/', $faucet, $claims_match);
$claims_left  = isset($claims_match[1]) ? (int)$claims_match[1] : null;
$claims_total = isset($claims_match[2]) ? (int)$claims_match[2] : null;
if ($claims_left !== null) {

    if ($claims_left <= 1) {
        echo putih . "[INFO] Daily claim limit has been reached!\n";
        exit;
    }
}

preg_match('/name="csrf_token_name"\s+(?:id="token"\s+)?value="([^"]+)"/i', $faucet, $csrf_match);
$csrf_token = $csrf_match[1] ?? '';
preg_match('/<input[^>]+name="token"\s+value="([^"]+)"/i', $faucet, $token_match);
$token = $token_match[1] ?? '';
preg_match('/class="cf-turnstile"\s+data-sitekey="([^"]+)"/i', $faucet, $sitekey_match);
if (!empty($sitekey_match[1])) {
    $sitekey = $sitekey_match[1];
	$pattern_main = '/Please click on the Anti-Bot links in the following order.*?<img src="data:image\/png;base64,([^"]+)"/s';
	if (preg_match($pattern_main, $faucet, $matches_main)) {
	    $main = $matches_main[1];
	}
	$pattern_antibot = '/<a href=\\\\"#\\\\" rel=\\\\"(\d+)\\\\"><img src=\\\\"data:image\/png;base64,([^\"]+)\\\\"/i';
	preg_match_all($pattern_antibot, $faucet, $matches);
	$id1  = $matches[1][0] ?? '';
	$img1 = $matches[2][0] ?? '';
	$id2  = $matches[1][1] ?? '';
	$img2 = $matches[2][1] ?? '';
	$id3  = $matches[1][2] ?? '';
	$img3 = $matches[2][2] ?? '';
	$anti = antibot($apikey,$main,$id1,$id2,$id3,$img1,$img2,$img3);
	if (is_array($anti)) {
   		$nilai = $anti["anti"];
   		$bot = str_replace(',', ' ', $nilai);
   		goto next;

	} elseif (in_array($anti, ["WRONG_CAPTCHA_ID", "ERROR_CAPTCHA_UNSOLVABLE", "ERROR_TOO_MANY_REQUESTS", "ERROR_SOLVE_PENDING", "INTENAL_SERVER_ERROR"])) {
		goto asu;

	} else {
	echo putih."Error: ".merah." Tidak di ketahui!! coba lagi...\n";
	goto asu;
  }
	
next:
	 $bypass = cloud($apikey, $sitekey);
	 if (is_array($bypass)) {
	 	$data = http_build_query([
	 		  "antibotlinks" => $bot,
	 		  "csrf_token_name" => $csrf_token,
	 		  "token" => $token,
	 		  "captcha" => "turnstile",
	 		  "cf-turnstile-response" => $bypass["turnstile"]
	 	]);
	 	$claim = skibidixxx(host."/faucet/verify", "POST", $data, $b);
		if (preg_match('/FaucetNotif\.modal\s*\(\s*["\']success["\']\s*,\s*["\']([^"\']+)["\']/i', $claim, $s_match)) {
            $msg = trim($s_match[1]);
            echo putih . "[SUCCESS] " . hijau . $msg . "\n";
            goto asu;
        }
        elseif (strpos($claim, 'You have reached claim') !== false || preg_match('/You have reached claim/i', $claim)) {
            echo kuning . "You have reached claim #5 of the day. Complete at least one PTC ad today to keep claiming the faucet.\n";
            echo putih."Enter when finished..";
            trim(fgets(STDIN));
            goto asu;
            
        }
        elseif (preg_match('/class="alert[^"]*alert-danger"[^>]*>\s*(?:<i[^>]*><\/i>)?\s*([^<]+)/i', $claim, $e_match)) {
            $error_msg = trim($e_match[1]);
            echo putih . "[ERROR] " . kuning . $error_msg . "\n";
            goto asu;
        }
        else {
            echo putih . "[ERROR] " . merah . "Respons tidak diketahui / Gagal Parse!\n";
            goto asu;
        }
    } elseif (in_array($bypass, ["WRONG_CAPTCHA_ID", "ERROR_CAPTCHA_UNSOLVABLE", "ERROR_TOO_MANY_REQUESTS", "ERROR_SOLVE_PENDING", "INTENAL_SERVER_ERROR"])) {
	        goto asu;

	} else {
	        echo putih."Error: ".merah." Tidak di ketahui!! coba lagi...\n";
	        goto asu;

}

} else {
    $sitekey = '';
	echo putih. "Warning:".kuning." Sitekey Turnstile tidak ditemukan!\n";
	exit;
}

  }
  else {
  	clear();
  	echo putih."login required!...\n";

	po:
	p($a,$b);
  	$login = skibidixxx(host."/login", "GET", [], $a);
  	preg_match('/name="csrf_token_name"\s+value="([^"]+)"/i', $login, $csrf_match);
  	$csrf_token = $csrf_match[1] ?? '';
  	preg_match('/class="cf-turnstile"\s+data-sitekey="([^"]+)"/i', $login, $sitekey_match);
  	  	if (!empty($sitekey_match[1])) {
  	    $sitekey = $sitekey_match[1];
	    $bypass = cloud($apikey, $sitekey);
	    if (is_array($bypass)) {
  	    $data = http_build_query([
  	    	  "csrf_token_name" => $csrf_token,
  	    	  "email" => $email,
  	    	  "password" => $password,
  	    	  "remember" =>  "1",
  	    	  "captcha" => "turnstile",
  	    	  "cf-turnstile-response" => $bypass["turnstile"]
  	    ]);
  	    $verify = skibidixxx(host."/auth/login", "POST", $data, $b);
	if (strpos($verify, 'Dashboard | 1XFaucet') !== false) {
    	echo putih. "[SUCCESS]".hijau." Login Verifikasi Berhasil!\n";
    	sleep(4); goto home;
	} 
	elseif (preg_match('/class="alert[^"]*alert-danger"[^>]*>\s*(?:<i[^>]*><\/i>)?\s*([^<]+)/i', $verify, $e_match)) {
    	$error_msg = trim($e_match[1]);
    	echo putih . "[ERROR] " .merah. $error_msg . "\n";
    	@unlink($waryono);@unlink($configFile); exit;
	} 
	else {
	    echo kuning . "Status respons tidak diketahui / Gagal Parse!\n";
	    @unlink($waryono);
	    exit;
	}  	    

    } elseif (in_array($bypass, ["WRONG_CAPTCHA_ID", "ERROR_CAPTCHA_UNSOLVABLE", "ERROR_TOO_MANY_REQUESTS", "ERROR_SOLVE_PENDING", "INTENAL_SERVER_ERROR"])) {
	        goto po;

	   } else {
	        echo putih."Error: ".merah." Tidak di ketahui!! coba lagi...\n";
	        goto po;

	    }

  	} else {
  	    $sitekey = '';
  	    echo kuning . "[!] Turnstile Sitekey tidak ditemukan / Halaman tidak pakai Captcha!\n";
  	}
  }
