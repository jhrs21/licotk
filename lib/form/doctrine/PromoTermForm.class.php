<?php

/**
 * PromoTerm form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PromoTermForm extends BasePromoTermForm
{
    public function configure()
    {
        unset($this['promo_id'], $this['created_at'], $this['updated_at']);

        $this->widgetSchema['term'] = new sfWidgetFormInput(array(),array('maxlength' => 255, 'size' => 130,));
    }
}
