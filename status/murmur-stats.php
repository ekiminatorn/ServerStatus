<?php

include "settings.php";

function getStats($host, $login)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERPWD, $login);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

    $url = "https://" . $host . "/stats/";

    curl_setopt($ch, CURLOPT_URL, $url);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $response = json_decode($response, true);

    return $response;
}

$users = 0;
$bootedServers = 0;
$allServers = 0;
$individualServers = array();

foreach ($config['hosts'] as $host){
    if (!$host['disabled']){
        $result = getStats($host['hostname'], $host['apiLogin']);

        $allServers += $result['all_servers'];
        $bootedServers += $result['booted_servers'];
        $users += $result['users_online'];

        $array = array(
            "all_servers" => $result['all_servers'],
            "booted_servers" => $result['booted_servers'],
            "users_online" => $result['users_online'],
            "friendlyName" => $host['friendlyName']
        );

        array_push($individualServers, $array);

    }
}
//Combine all stats into one array, so we can JSON it to a file
$stats = array();

$stats['users_online'] = $users;
$stats['booted_servers'] = $bootedServers;
$stats['all_servers'] = $allServers;
array_push($stats, $individualServers);


file_put_contents(__DIR__ . '/json/murmur-stats.json', json_encode($stats));