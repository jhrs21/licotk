<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epCouponRedeemForm
 *
 * @author Jacobo Martínez
 */
class epTagForm extends BaseForm {

    public function configure() {
        $asset = $this->getOption('asset');

        $attributes = array(
            'data-bvalidator' => 'required',
            'data-bvalidator-msg' => 'Campo requerido'
        );

        $this->widgetSchema['user_identifier'] = new sfWidgetFormInputText(array(), $attributes);

        $this->widgetSchema['promocode'] = new sfWidgetFormDoctrineChoice(
                        array(
                            'model' => 'PromoCode',
                            'method' => 'getPromoName',
                            'key_method' => 'getAlphaId',
                            'query' => Doctrine::getTable('PromoCode')->getPromoCodeByAssetAndActiveTagPeriodPromosQuery($asset),
                        ),
                        $attributes
        );

        $this->widgetSchema->setLabels(array('user_identifier' => 'Correo Electrónico o Tarjeta', 'promocode' => 'Promoción'));

        $this->setValidators(array(
            'user_identifier' => new sfValidatorString(array(), array('required' => 'Campo requerido')),
            'promocode' => new sfValidatorDoctrineChoice(
                    array(
                        'model' => 'PromoCode',
                        'query' => Doctrine::getTable('PromoCode')->getPromoCodeByAssetAndActiveTagPeriodPromosQuery($asset),
                        'column' => 'alpha_id',
                        'required' => true,
                    ),
                    array(
                        'required' => 'Campo requerido',
                        'invalid' => 'Promoción inválida'
                    )
            ),
        ));

        $this->validatorSchema->setPostValidator(new epValidatorTag());

        $this->widgetSchema->setNameFormat('epTagForm[%s]');

        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}