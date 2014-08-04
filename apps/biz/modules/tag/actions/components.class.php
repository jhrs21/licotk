<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineApplyPlugin/modules/sfApply/lib/BasesfApplyComponents.class.php');

class tagComponents extends BasesfApplyComponents {

    public function executeGeneratorQR(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $this->suffix = '';
        $this->vcode = '';
        $promo = Doctrine::getTable('Promo')->findOneBy('id', $this->promoid);
        //$promo = $this->getRoute()->getObject();
        $pc = Doctrine::getTable('PromoCode')->findOneByTypeAndPromoIdAndStatusAndDigital('validation_required', $promo->getId(), 'active', true);
        
        if (!$pc) {
            $this->getUser()->setFlash('no_pc_validation_required', 'No posees esta funcionalidad. Por favor solicitarla a su administrador del sistema');
            return;
        }
        while (($new_code = Util::GenSecret(5, 0)) && (count(Doctrine::getTable('ValidationCode')->findBy('code', $new_code)) != 0)) {
            
        }
        while (($new_serial = Util::GenSecret(5, 1)) && (count(Doctrine::getTable('ValidationCode')->findByPromoCodeIdAndSerial($pc->getId(), $new_serial)) != 0)) {
            
        }
        
        $validation_code = new ValidationCode();
        $validation_code->setActive(true);
        $validation_code->setPromoCodeId($pc->getId());
        $validation_code->setCode($new_code);
        $validation_code->setSerial($new_serial);
        $validation_code->save();
        $string = str_replace(
                array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ'), array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'n', 'N'), $user->getAffiliate()
        );
        $this->vcode = $validation_code->getCode();
        require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
        $this->suffix = '/qr-pc/' . $string . '-generadorQR.png';
        $filename = sfConfig::get('sf_web_dir') . $this->suffix;
        $data = "http://www.lealtag.com/" . $pc->getAlphaId() . "?vcode=" . $validation_code->getCode();
        $errorCorrectionLevel = "L";
        $matrixPointSize = "8";
//	if (ob_get_length() > 0) {
//	        ob_end_clean();
//	}
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
    }

}

?>
