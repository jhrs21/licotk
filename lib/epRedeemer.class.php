<?php

/**
 * Description of epBuhoApi
 *
 * @author jacobo
 */
class epRedeemer {

    protected $cH; //cURL handler
    protected $redeemerConfig; //RedeemerConfig object
    protected $redeemerParams; //RedeemerParams collection

    public function __construct(PromoRedeemerConfig $redeemer) {
        $this->redeemerConfig = $redeemer;
    }

    public function redeem(sfGuardUser $user, PromoPrize $prize, $transactionId = false) {
        if (!$transactionId) {
            $transactionId = Util::gen_uuid(hash('sha256', time() . rand(11111, 99999)));
        }
        
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->redeemerConfig->getUrl());
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, 
                $this->redeemerConfig->getCredentials()
                .'&user='            . $user->getEmail()
                .'&prize='           . $prize->getAlphaId()
                .'&transaction_id='  . $transactionId
                .'&action=PremioLTv2'
        );

        $result = curl_exec($this->cH);
        
        if ($error = $this->executionErrorOccured()) {
            return $this->setExecutionError($error);
        }
        
        curl_close($this->cH);
        //$pp = strip_tags($result);
        $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);
        return $response;
    }

    protected function newSession() {
        $this->cH = curl_init();
        curl_setopt($this->cH, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->cH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cH, CURLOPT_POST, 1);
        curl_setopt($this->cH, CURLOPT_CUSTOMREQUEST, 'POST');
    }
    
    protected function executionErrorOccured() {
        $error = curl_errno($this->cH);
        
        if ($error == 0) {
            return false;
        }
        
        return $error;
    }
    
    protected function setExecutionError($error) {
        $result = array();
        $result['success'] = 0;
        $result['error'] = array('code' => $error, 'message' => curl_error($this->cH), 'type' => 'cUrlError');
        
        return $result;
    }
}