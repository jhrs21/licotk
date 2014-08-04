<?php

/**
 * Description of epBuhoConexion
 *
 * @author jacobo
 */
class epTDApi {

    protected $cH; //A cURL handle
    //protected $tdCredentials; //A string
    protected $tdURL = 'https://www.tudescuenton.com/api/control.php';
    protected $cliente = 'LEALTAG';
    protected $clave_priv = '877e1a188460ca23cd7646d70af385a8';

    public function __construct() {
        $this->cliente = sfConfig::get('app_td_user');
        $this->clave_priv = sfConfig::get('app_td_api_key');
//        $this->tdCredentials = '&client_name=' . sfConfig::get('app_elbuho_user') . '&client_password=' . sfConfig::get('app_elbuho_api_key');
    }

    public function tdSendPrize($values) {
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->tdURL);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, "action=PremioLT"
                . "&cliente=" . $this->cliente
                . "&clave_priv=" . $this->clave_priv
                . "&hash=" . $values['hash']
                . "&email=" . $values['email']
                . "&id_promocion=" . $values['promo']
                . "&id_premio=" . $values['premio']
        );

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags( $result );
        return json_decode($pp, true);
    }

    protected function newSession() {
        $this->cH = curl_init();
        curl_setopt($this->cH, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->cH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cH, CURLOPT_POST, 1);
        curl_setopt($this->cH, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->cH, CURLOPT_SSL_VERIFYHOST, 2);
    }

}

?>
