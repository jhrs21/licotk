<?php

/**
 * PromoCode form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PromoCodeForm extends BasePromoCodeForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at'], $this['used_at'], $this['user_id'], $this['alpha_id'], $this['hash']);
        $this->setDefault('serial', Util::GenSecret(5, 1));
    }

}
