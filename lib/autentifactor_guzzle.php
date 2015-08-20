<?php
require 'vendor/autoload.php';
use GuzzleHttp\Exception\ClientException;
/**
 * autentifactor
 */

class autentifactor {

    private $host = "";
    
    public function __construct($host) {
        $this->host = $host;
    }


    /**
     * Autenticacion de OTC
     * @return <bool>
     */    
    public function validateOtc($code, $request_token) {

        $client = new GuzzleHttp\Client();

        $body['code'] = $code;

        $bearer = 'Bearer ' . $request_token;
        
        $res = $client->post($this->host . "/v1/users/otc", ['Authorization' => $bearer], [ 'body' => json_encode($body) ]);
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
        $API_TOKEN = "...API TOKEN...";

        $client = new GuzzleHttp\Client();

        $body['account'] = $email;

        $bearer = 'Bearer ' . $API_TOKEN;
        
        $res = $client->post($this->host . "/v2/users/delegate", ['Authorization' => $bearer], [ 'body' => json_encode($body) ]);
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

        $res = $client->post($this->host . "/v1/users/authenticate", [ 'body' => json_encode($body) ]);
        // echo $res->getBody();
        // echo $res->getStatusCode();

        $af_2fa_token = $res->getHeader('x-app-autentifactor');

        return $af_2fa_token;
    }    
    
}

?>
