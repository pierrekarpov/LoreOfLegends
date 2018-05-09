<?php
require_once("curl_helper.php");

print "\n";

$fc = file_get_contents(".env");
$a = explode("\"", $fc);
$key = $a[1];


$action = "GET";
$summoner_name = "TheFrenchPopov";
$url = "https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $summoner_name;
echo "Trying to reach ...";
echo $url;
$parameters = array(
  "api_key" => $key
);
$result = CurlHelper::perform_http_request($action, $url, $parameters);

print "\n";
print_r($result);
print "\n";
?>
