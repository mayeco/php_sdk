<!-- READ http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/-->
<?php

require __DIR__ . '/lib/autentifactor_guzzle.php';

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

    $af_client = new autentifactor('http://localhost/api');
    // $token = $af_client->authenticate($u, $p);
    $token = $af_client->delegate($u);

    echo json_encode(array("token" => $token));
    return;
?>
