<!-- READ http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/-->
<?php

$u = $_POST["username"];
$p = $_POST["password"];

header("Content-type:application/json");

if ($u == NULL)  {
    header('X-PHP-Response-Code: 400', true, 400);
    echo json_encode(array("error" => "Missing username"));
    return;
}

if ($p == NULL)  {
    header('X-PHP-Response-Code: 400', true, 400);
    echo json_encode(array("error" => "Missing password"));
    return;
}

$data = array("email" => $u, "password" => $p);
$data_string = json_encode($data);
                                                                                   
$ch = curl_init("http://localhost/api/v1/users/authenticate");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                             
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);


$output = curl_exec($ch);
$info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);

list($header, $body) = explode("\r\n\r\n", $output, 2);

$header=explode("\r\n", $header);
array_shift($header);    //get rid of "HTTP/1.1 200 OK"
$resp_headers=array();
foreach ($header as $k=>$v)
{
    $v=explode(': ', $v, 2);
    $resp_headers[$v[0]]=$v[1];
}
echo json_encode(array("headers" => $resp_headers,"info" => $info, "data" => json_decode($body, true), "err" => $err));
curl_close($ch);
?>
