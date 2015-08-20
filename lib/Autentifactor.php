<?php

namespace Autentifactor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * autentifactor
 */
class Autentifactor {

    private $host;
    private $token;

    public function __construct($host, $token) {
        $this->host = $host;
        $this->token = $token;
    }

    /**
     * Autenticacion de OTC
     * @return <bool>
     */
    public function validateOtc($code, $request_token) {

        $client = new Client();

        $body['code'] = $code;

        $bearer = 'Bearer ' . $request_token;

        $endpoint = "/v1/users/otc";

        $options = [
            'headers' => ['Authorization' => $bearer],
            'body' => json_encode($body),
          ];

        $res = $client->post($this->host . $endpoint, $options);
        // echo $res->getBody();
        // echo $res->getStatusCode();

        $auth_token = $res->getHeader('x-app-autentifactor-bearer');

        if ($auth_token == null) {
            return null;
        } else {
            return $auth_token;
        }
    }
    /**
     * Delega autenticacion
     * @return <string>
     */
    public function delegate($email){

        $client = new Client();

        $body['account'] = $email;

        $bearer = 'Bearer ' . $this->token;

        $endpoint = "/v2/users/delegate";

        $options = [
            'headers' => ['Authorization' => $bearer],
            'body' => json_encode($body),
          ];

        $res = $client->post($this->host . $endpoint, $options);
        // echo $res->getBody();
        // echo $res->getStatusCode();

        $af_2fa_token = $res->getHeader('x-app-autentifactor');

        return $af_2fa_token;
    }

    /**
     * autenticacion
     * @return <string>
     */
    public function authenticate($username, $password){
        $client = new GuzzleHttp\Client();

        $body['email'] = $username;
        $body['password'] = $password;

        $res = $client->post($this->host . "/v1/users/authenticate", [ 'json' => $body ]);
        // echo $res->getBody();
        // echo $res->getStatusCode();

        $af_2fa_token = $res->getHeader('x-app-autentifactor');

        return $af_2fa_token;
    }

}
