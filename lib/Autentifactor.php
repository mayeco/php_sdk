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

    private function request($endpoint, $request_token, $body) {

        $client = new Client();

        $bearer = 'Bearer ' . $request_token;

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
     * Autenticacion de OTC
     * @return <bool>
     */
    public function validateOtc($code, $request_token) {
        return $this->request("/v1/users/otc", $request_token, ['code' => $code]);
    }

    /**
     * Delega autenticacion
     * @return <string>
     */
    public function delegate($email) {
        return $this->request("/v2/users/delegate", $this->token, ['account' => $email]);
    }

    /**
     * autenticacion
     * @return <string>
     */
    public function authenticate($username, $password) {
        return $this->request("/v2/users/authenticate", $this->token, ['email' => $username, 'password' => $password]);
    }
}
