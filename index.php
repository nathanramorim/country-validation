<?php 
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}

function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}

$ip =  get_ip_address();
$url = 'http://freegeoip.net/json/'.$ip;
$data = json_decode(file_get_contents($url));

#pegando informações referente ao IP de acesso.
foreach ($data as $key => $value) {
    $infos[$key] .= $value;
}

#os indices para buscar informações são: 
    #$infos['ip'], $infos['country_code'], $infos['country_name'], $infos['region_code'], $infos['region_name'] 
    #$infos['city'], $infos['zip_code'], $infos['time_zone'], $infos['latitude'], $infos['longitude'], $infos['metro_code']
    
#verificando qual pais você quer validar 'country_name'
$validation = ($infos['country_name'] == 'country_name' || $infos['country_name'] == 'country_name') ? true : false;

if($validation){
    $msg = ($infos['city'] != '') ? '<h1>Você é de '.$infos['city'].'</h1>' : '';
    echo $msg;
    echo '<h2> Seu endereço de IP está em '.$infos['country_name'].'</h2>';
}else{
    $msg = ($infos['city'] != '') ? '<h1>Você é de '.$infos['city'].'</h1>' : '';
    echo $msg;
    echo '<h2> Seu endereço de IP é de '.$infos['country_name'].'</h2>';
}


