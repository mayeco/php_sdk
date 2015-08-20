<?php

// READ http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/

require __DIR__ . '/vendor/autoload.php';

$u = $POST_["user"];
$p = $POST_["password"];

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

$af_client = new \Autentifactor\Autentifactor('http://localhost', "MY_TOKEN");
// $token = $af_client->authenticate($u, $p);
$token = $af_client->delegate($u);

echo json_encode(array("token" => $token));
