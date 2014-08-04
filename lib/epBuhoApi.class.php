<?php

/**
 * Description of epBuhoApi
 *
 * @author jacobo
 */
class epBuhoApi {

    protected $cH; //A cURL handle
    protected $buhoCredentials; //A string containing the required credentials
    protected $buhoURL;
    protected $buhoActionsURLs = array(
        'get_user' => 'query/get_user',
        'login' => 'loging/login',
        'create' => 'creation/create',
        'update' => 'updating/update',
        'verify' => 'creation/verify',
        'reset_password' => 'updating/reset_password',
        'update_password' => 'updating/update_password'
    );

    public function __construct() {
        $this->buhoCredentials = '&client_name=' . sfConfig::get('app_elbuho_user') . '&client_password=' . sfConfig::get('app_elbuho_api_key');
        $this->buhoURL = sfConfig::get('app_elbuho_url', 'http://190.9.43.239/elbuho/index.php/elbuho_api_v2');
    }

    public function buhoGetUser($values) {
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['get_user']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $this->buhoCredentials
                . '&user=' . $values['user']
        );

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoLogin($values) { 
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['login']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $this->buhoCredentials
                . '&email=' . $values['email']
                . '&password=' . $values['password']
        );
	
        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
	//var_dump($result, $this); die;
curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoCreateUser($values) {
        $this->newSession();

        $postValues = $this->buhoCredentials
                . '&email=' . $values['email']
                . '&password=' . $values['password']
                . '&info[fullname]=' . $values['fullname']
                . '&info[mobile_phone]=' . (array_key_exists('mobile_phone', $values) ? $values['mobile_phone'] : '')
                . '&info[land_phone]=' . (array_key_exists('land_phone', $values) ? $values['land_phone'] : '')
                . '&info[birthday]=' . $values['birthday']
                . '&info[identifier]=' . (array_key_exists('identifier', $values) ? $values['identifier'] : '')
                . '&info[gender]=' . (array_key_exists('gender', $values) ? $values['gender'] : '');

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['create']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $postValues);

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoUpdateUser($values) {
        $this->newSession();

        $postValues = $this->buhoCredentials
                . '&user=' . $values['user']
                . '&info[fullname]=' . (array_key_exists('fullname', $values) ? $values['fullname'] : '')
                . '&info[mobile_phone]=' . (array_key_exists('mobile_phone', $values) ? $values['mobile_phone'] : '')
                . '&info[land_phone]=' . (array_key_exists('land_phone', $values) ? $values['land_phone'] : '')
                . '&info[birthday]=' . (array_key_exists('birthday', $values) ? $values['birthday'] : '')
                . '&info[identifier]=' . (array_key_exists('identifier', $values) ? $values['identifier'] : '')
                . '&info[gender]=' . (array_key_exists('gender', $values) ? $values['gender'] : '');

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['update']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $postValues);

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoVerify($values) {
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['verify']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $this->buhoCredentials
                . '&user=' . $values['user']
                . '&validator=' . $values['validator']
        );

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoResetPassword($values) {
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['reset_password']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $this->buhoCredentials
                . '&user=' . $values['user']
        );

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    public function buhoUpdatePassword($values) {
        $this->newSession();

        curl_setopt($this->cH, CURLOPT_URL, $this->buhoURL . '/' . $this->buhoActionsURLs['update_password']);
        curl_setopt($this->cH, CURLOPT_POSTFIELDS, $this->buhoCredentials
                . '&user=' . $values['user']
                . '&validator=' . $values['validator']
                . '&new_password=' . $values['new_password']
        );

        $result = curl_exec($this->cH);
        $error = curl_error($this->cH);
        curl_close($this->cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

    protected function newSession() {
        $this->cH = curl_init();
        curl_setopt($this->cH, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->cH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cH, CURLOPT_POST, 1);
        curl_setopt($this->cH, CURLOPT_CUSTOMREQUEST, 'POST');
    }

}

?>
