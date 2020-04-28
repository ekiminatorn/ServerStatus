<?php

##
# This file is run by cron
##

include "settings.php";


#echo $config['pushover']['userKey'];

$data = file_get_contents(__DIR__ . '/json/murmur-stats.json');
$data = json_decode($data, true);

$users = $data['users_online'];
$bootedServers = $data['booted_servers']; 


//Populate the POST request to Pushover API
$data = array(
    "token" => $config['pushover']['appToken'],
    "user" => $config['pushover']['userKey'],
    "title" => "Murmur Stats",
    "text" => "Online: " . $users,
    "subtext" => "Booted: " . $bootedServers,
    "count" => $users,
    "percent" => $users

);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.pushover.net/1/glances.json");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);


?>
