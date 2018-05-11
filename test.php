<?php
require_once("curl_helper.php");


// FLOW:
// get summoner name from user
// get champion name from user
//
// get summoner id from Riot
// get champion id from Riot
// get match history
// filter in games with champion as ally
// filter in games with champion as enemy
//
// confirm or deny the meme


$fc = file_get_contents(".env");
$a = explode("\"", $fc);
$key = $a[1];

// get summoner name from user
$summoner_name = "TheFrenchPopov";
$action = "GET";
$summoner_by_name_url = "https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $summoner_name;
$parameters = array(
  "api_key" => $key
);
$json1 = CurlHelper::perform_http_request($action, $summoner_by_name_url, $parameters);
$json1 = json_decode($json1, true);
$summoner_id = $json1["id"];
$account_id = $json1["accountId"];

// get champion name from user
$champion_name = "Yasuo";
$champion_id = -1;
$action = "GET";
$champion_list_url = "https://na1.api.riotgames.com/lol/static-data/v3/champions";
$parameters = array(
  "api_key" => $key,
  "locale" => "en_US",
  "dataById" => "true"
);
$json2 = CurlHelper::perform_http_request($action, $champion_list_url, $parameters);
$json2 = json_decode($json2, true);

foreach ($json2["data"] as $k => $v) {
  if ($v["name"] == $champion_name) {
    $champion_id = $k;
  }
}

// get match history
$action = "GET";
$match_list_url = "https://na1.api.riotgames.com/lol/match/v3/matchlists/by-account/" . $account_id;
$parameters = array(
  "api_key" => $key,
  "champion" => $champion_id
);
$json3 = CurlHelper::perform_http_request($action, $match_list_url, $parameters);
$json3 = json_decode($json3, true);

$game_ids = array();
foreach ($json3["matches"] as $k => $v) {
  array_push($game_ids, $v["gameId"]);
}

$total = count($game_ids);

foreach ($game_ids as $gid) {
  $action = "GET";
  $match_list_url = "https://na1.api.riotgames.com/lol/match/v3/matches/" . $gid;
  $parameters = array(
    "api_key" => $key
  );
  $json4 = CurlHelper::perform_http_request($action, $match_list_url, $parameters);
  print $json4;
  $json4 = json_decode($json4, true);

  print $json4["participantIdentities"];
  foreach ($json4["participantIdentities"] as $pa) {
    foreach ($pa as $p => $pk) {
      print $p . $pk["accountId"] . "\n";
      if ($pk["accountId"] == $account_id) {

      }
    }
  }
  // foreach ($json4["stats"]["participantIdentities"] as $key => $value) {
  //   print $key . $value;
  //   // code...
  // }
}

print "\n";
