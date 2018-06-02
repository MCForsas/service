<?php
/*$location = file_get_contents('https://api.ipdata.co/'.$_SERVER['REMOTE_ADDR']);
print_r($location);
*/
$ip = '78.58.186.99';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.ipdata.co/".$ip);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Accept: application/json"
));

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response,true);
print_r($data);
//$lat = $data['latitude'];
//$lng = $data['longtitude'];
?>
